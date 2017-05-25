<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Http_Request;
use Jet\Http_Headers;
use Jet\Application;
use Jet\Mvc_Factory;
use Jet\Mvc_Layout;
use Jet\Locale;
use Jet\Session;
use Jet\Translator;
use Jet\Translator_Backend_PHPFiles;
use Jet\Tr;

/** @noinspection PhpIncludeInspection */
require JET_APP_INSTALLER_PATH.'Classes/Step/Controller.php';

/**
 *
 */
class Installer
{

	const SITE_ID = 'example';

	/**
	 * @var array
	 */
	protected static $steps = [];

	/**
	 * @var Installer_Step_Controller[]
	 */
	protected static $step_controllers = [];

	/**
	 * @var Locale[]
	 */
	protected static $available_locales = [];

	/**
	 * @var array
	 */
	protected static $selected_locales = [];

	/**
	 * @var Locale
	 */
	protected static $current_locale;

	/**
	 * @var string
	 */
	protected static $current_step_name;


	/**
	 * @var Mvc_Layout
	 */
	protected static $layout;

	/**
	 * @param array $steps
	 */
	public static function setSteps( array $steps )
	{
		static::$steps = $steps;
		static::$step_controllers = [];
	}

	/**
	 * @return Locale[]
	 */
	public static function getAvailableLocales()
	{
		return self::$available_locales;
	}

	/**
	 * @param array $available_locales
	 */
	public static function setAvailableLocales( array $available_locales )
	{
		$ls = [];

		foreach( $available_locales as $locale ) {
			$locale = new Locale( $locale );
			$ls[(string)$locale] = $locale;
		}


		self::$available_locales = $ls;
	}

	/**
	 * @return Locale[]
	 */
	public static function getSelectedLocales()
	{
		if( !self::$selected_locales ) {
			self::$selected_locales = static::getSession()->getValue( 'selected_locales' );

			if( !self::$selected_locales ) {
				static::setSelectedLocales( [ static::getCurrentLocale()->toString() ] );
			}
		}

		return self::$selected_locales;
	}

	/**
	 * @param Locale[] $selected_locales
	 */
	public static function setSelectedLocales( array $selected_locales )
	{
		self::$selected_locales = [];

		foreach( $selected_locales as $locale ) {
			$locale = new Locale( $locale );

			self::$selected_locales[$locale->toString()] = $locale;
		}

		static::getSession()->setValue( 'selected_locales', self::$selected_locales );
	}

	/**
	 * @return Session
	 */
	public static function getSession()
	{
		return new Session( '_installer' );
	}

	/**
	 * @return Locale
	 */
	public static function getCurrentLocale()
	{
		if( !static::$current_locale ) {
			$session = static::getSession();

			if( $session->getValueExists( 'current_locale' ) ) {
				static::$current_locale = $session->getValue( 'current_locale' );
			} else {
				foreach( static::$available_locales as $locale ) {
					static::setCurrentLocale( $locale );
					break;
				}
			}

		}


		return static::$current_locale;
	}

	/**
	 * @param Locale $locale
	 */
	public static function setCurrentLocale( Locale $locale )
	{
		static::getSession()->setValue( 'current_locale', $locale );
		static::$current_locale = $locale;
	}

	/**
	 *
	 */
	public static function main()
	{
		Http_Request::initialize( true );
		Application::setConfigFilePath( static::getTmpConfigFilePath() );

		static::initStepControllers();

		$GET = Http_Request::GET();
		if( $GET->exists( 'step' ) ) {

			$steps = [];
			$first_step = null;
			foreach( static::$step_controllers as $controller ) {
				if( !$first_step ) {
					$first_step = $controller->getName();
				}
				if( $controller->getIsFuture() ) {
					break;
				}
				$steps[] = $controller->getName();
			}

			$step = $GET->getString( 'step', $first_step, $steps );

			static::setCurrentStepName( $step );
			Http_Headers::movedTemporary( '?' );

		}

		static::initTranslator();

		static::getCurrentStepController()->main();

		static::getLayout()->setVar( 'steps', static::$step_controllers );

		Translator::setCurrentNamespace( Translator::COMMON_NAMESPACE );
		echo static::getLayout()->render();

		exit();
	}

	/**
	 * @return string
	 */
	public static function getTmpConfigFilePath()
	{
		return JET_PATH_TMP.'config_install.php';
	}

	/**
	 *
	 */
	protected static function initStepControllers()
	{

		$steps = static::$steps;

		static::$step_controllers = [];

		while( $steps ) {
			$step_name = array_shift( $steps );

			$step_base_path = JET_APP_INSTALLER_PATH.'Step/'.$step_name.'/';

			/** @noinspection PhpIncludeInspection */
			require_once $step_base_path.'Controller.php';

			$class_name = __NAMESPACE__.'\\Installer_Step_'.$step_name.'_Controller';

			/**
			 * @var Installer_Step_CreateAdministrator_Controller $controller
			 */
			$controller = new $class_name( $step_name, $step_base_path );


			static::$step_controllers[$step_name] = $controller;

			$steps_after = $controller->getStepsAfter();

			if( $steps_after ) {
				foreach( $steps_after as $step_after ) {
					array_unshift( $steps, $step_after );
				}
			}
		}

		$got_current = false;
		$current_step_name = static::getCurrentStepName();

		$steps_map = [];

		foreach( static::$step_controllers as $controller ) {
			if( !$controller->getIsAvailable() ) {
				continue;
			}

			$steps_map[] = $controller->getName();
		}

		$c = 0;
		$i = 0;
		$steps_count = count( static::$step_controllers );
		foreach( static::$step_controllers as $controller ) {
			$c++;

			if( $controller->getIsAvailable() ) {
				$is_current = ( $controller->getName()==$current_step_name );
				if( $is_current ) {
					$got_current = true;
					$is_prev = false;
					$is_next = false;

					if( $i>0 ) {
						static::$step_controllers[$steps_map[$i-1]]->setIsPrevious( true );
					}

					if( $i<=( $steps_count-1 ) ) {
						if( isset( $steps_map[$i+1] ) ) {
							static::$step_controllers[$steps_map[$i+1]]->setIsComing( true );
						}
					}

				} else {
					if( $got_current ) {
						$is_prev = false;
						$is_next = true;

					} else {
						$is_prev = true;
						$is_next = false;
					}
				}

				$controller->setIsCurrent( $is_current );
				$controller->setIsFuture( $is_next );
				$controller->setIsPast( $is_prev );

				$i++;
			} else {
				$controller->setIsPast( true );
			}


			$controller->setIsLast( $steps_count==$c );

		}

	}

	/**
	 * @return string
	 */
	public static function getCurrentStepName()
	{

		if( !static::$current_step_name ) {

			$session = static::getSession();

			$steps = array_keys( static::$step_controllers );
			$first_step = $steps[0];


			if( !$session->getValueExists( 'current_step' ) ) {
				$session->setValue( 'current_step', $first_step );
			}

			static::$current_step_name = $session->getValue( 'current_step' );
		}

		return static::$current_step_name;
	}

	/**
	 * @param string $current_step_name
	 */
	public static function setCurrentStepName( $current_step_name )
	{

		static::$current_step_name = $current_step_name;
		static::getSession()->setValue( 'current_step', $current_step_name );

		static::initStepControllers();
	}

	/**
	 *
	 */
	public static function initTranslator()
	{

		Translator::setAutoAppendUnknownPhrase( true );

		/**
		 * @var Translator_Backend_PHPFiles $backend
		 */
		$backend = Translator::getBackend();
		$backend->setDictionariesBasePath( JET_APP_INSTALLER_PATH.'dictionaries/' );

		Locale::setCurrentLocale( static::getCurrentLocale() );
		Translator::setCurrentLocale( static::getCurrentLocale() );
		Translator::setCurrentNamespace( static::getCurrentStepName() );

	}

	/**
	 * @return Installer_Step_Controller
	 */
	public static function getCurrentStepController()
	{
		return static::getStepControllerInstance( static::getCurrentStepName() );
	}

	/**
	 * @param $step_name
	 *
	 * @return Installer_Step_Controller|null
	 */
	protected static function getStepControllerInstance( $step_name )
	{
		if( !isset( static::$step_controllers[$step_name] ) ) {
			return null;
		}

		return static::$step_controllers[$step_name];
	}

	/**
	 * @return Mvc_Layout
	 */
	public static function getLayout()
	{

		if( !static::$layout ) {
			static::$layout = Mvc_Factory::getLayoutInstance( JET_APP_INSTALLER_PATH.'layout/', 'default' );
		}

		return static::$layout;
	}

	/**
	 * @return Installer_Step_Controller|null
	 */
	public static function getPreviousController()
	{
		foreach( static::$step_controllers as $controller ) {
			if( $controller->getIsPrevious() ) {
				return $controller;
			}
		}

		return null;
	}


	/**
	 *
	 */
	public static function goToNext()
	{

		static::initStepControllers();

		$coming = static::getComingController();
		if( $coming ) {
			static::setCurrentStepName( $coming->getName() );
			Http_Headers::movedTemporary( '?' );
		}

	}

	/**
	 * @return Installer_Step_Controller|null
	 */
	public static function getComingController()
	{
		foreach( static::$step_controllers as $controller ) {
			if( $controller->getIsComing() ) {
				return $controller;
			}
		}

		return null;
	}

	/**
	 * @return string
	 */
	public static function buttonBack()
	{
		?>
		<a href="?">
			<button type="submit" class="btn btn-warning">
				<i class="glyphicon glyphicon-chevron-left"></i>
				<?=Tr::_( 'Go Back', [], Tr::COMMON_NAMESPACE );?>
			</button>
		</a>
		<?php
		return '';
	}

	/**
	 *
	 * @return string
	 */
	public static function continueForm()
	{
		?>
		<form method="post">
		<input type="hidden" name="go" value="1">
		<?php static::buttonNext();?>
		</form>
		<?php
		return '';
	}

	/**
	 * @return string
	 */
	public static function buttonNext()
	{
		?>
		<button type="submit" class="btn btn-primary">
			<?=Tr::_( 'Continue', [], Tr::COMMON_NAMESPACE );?><i class="glyphicon glyphicon-chevron-right"></i>
		</button>
		<?php
		return '';
	}

	/**
	 * @return string
	 */
	public static function buttonNextSkipIt()
	{
		?>
		<button type="submit" class="btn btn-info">
			<?=Tr::_( 'Skip this step', [], Tr::COMMON_NAMESPACE );?><i class="glyphicon glyphicon-chevron-right"></i>
		</button>
		<?php
		return '';
	}

}
