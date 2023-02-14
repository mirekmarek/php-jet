<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication\Installer;

use Jet\Http_Request;
use Jet\Http_Headers;
use Jet\Factory_MVC;
use Jet\MVC_Layout;
use Jet\Locale;
use Jet\MVC_View;
use Jet\SysConf_Jet_Translator;
use Jet\Session;
use Jet\Translator;
use Jet\SysConf_Path;


require 'Step/Controller.php';

/**
 *
 */
class Installer
{

	/**
	 * @var array
	 */
	protected static array $steps = [];

	/**
	 * @var Installer_Step_Controller[]
	 */
	protected static array $step_controllers = [];

	/**
	 * @var Locale[]
	 */
	protected static array $available_locales = [];

	/**
	 * @var array
	 */
	protected static array $selected_locales = [];

	/**
	 * @var ?Locale
	 */
	protected static ?Locale $current_locale = null;

	/**
	 * @var string
	 */
	protected static string $current_step_name = '';

	/**
	 * @var string
	 */
	protected static string $base_path = '';

	/**
	 * @var ?MVC_Layout
	 */
	protected static ?MVC_Layout $layout = null;

	/**
	 * @param array $steps
	 */
	public static function setSteps( array $steps ): void
	{
		static::$steps = $steps;
		static::$step_controllers = [];
	}

	/**
	 * @return Locale[]
	 */
	public static function getAvailableLocales(): array
	{
		return self::$available_locales;
	}

	/**
	 * @param array $available_locales
	 */
	public static function setAvailableLocales( array $available_locales ): void
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
	public static function getSelectedLocales(): array
	{
		if( !self::$selected_locales ) {
			self::$selected_locales = static::getSession()->getValue( 'selected_locales', [static::getCurrentLocale()->toString()] );
		}

		return self::$selected_locales;
	}

	/**
	 * @param Locale[] $selected_locales
	 */
	public static function setSelectedLocales( array $selected_locales ): void
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
	public static function getSession(): Session
	{
		return new Session( '_installer_' );
	}

	/**
	 * @return Locale
	 */
	public static function getCurrentLocale(): Locale
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
	public static function setCurrentLocale( Locale $locale ): void
	{
		static::getSession()->setValue( 'current_locale', $locale );
		static::$current_locale = $locale;
	}

	/**
	 *
	 */
	public static function main(): void
	{
		Http_Request::initialize( true );

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

		Translator::setCurrentDictionary( Translator::COMMON_DICTIONARY );
		echo static::getLayout()->render();

		exit();
	}


	/**
	 *
	 */
	protected static function initStepControllers(): void
	{

		$steps = static::$steps;

		static::$step_controllers = [];

		while( $steps ) {
			$step_name = array_shift( $steps );

			$step_base_path = static::getBasePath() . 'Step/' . $step_name . '/';
			
			require_once $step_base_path . 'Controller.php';

			$class_name = __NAMESPACE__ . '\\Installer_Step_' . $step_name . '_Controller';

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
				$is_current = ($controller->getName() == $current_step_name);
				if( $is_current ) {
					$got_current = true;
					$is_prev = false;
					$is_next = false;

					if( $i > 0 ) {
						static::$step_controllers[$steps_map[$i - 1]]->setIsPrevious( true );
					}

					if( $i <= ($steps_count - 1) ) {
						if( isset( $steps_map[$i + 1] ) ) {
							static::$step_controllers[$steps_map[$i + 1]]->setIsComing( true );
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


			$controller->setIsLast( $steps_count == $c );

		}

	}

	/**
	 * @return string
	 */
	public static function getCurrentStepName() : string
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
	public static function setCurrentStepName( string $current_step_name ): void
	{

		static::$current_step_name = $current_step_name;
		static::getSession()->setValue( 'current_step', $current_step_name );

		static::initStepControllers();
	}

	/**
	 *
	 */
	public static function initTranslator(): void
	{

		SysConf_Jet_Translator::setAutoAppendUnknownPhrase(true);
		SysConf_Path::setDictionaries( static::getBasePath() . 'dictionaries/' );

		Locale::setCurrentLocale( static::getCurrentLocale() );
		Translator::setCurrentLocale( static::getCurrentLocale() );
		Translator::setCurrentDictionary( static::getCurrentStepName() );

	}

	/**
	 * @return Installer_Step_Controller
	 */
	public static function getCurrentStepController(): Installer_Step_Controller
	{
		return static::getStepControllerInstance( static::getCurrentStepName() );
	}

	/**
	 * @param $step_name
	 *
	 * @return Installer_Step_Controller|null
	 */
	protected static function getStepControllerInstance( $step_name ): Installer_Step_Controller|null
	{
		if( !isset( static::$step_controllers[$step_name] ) ) {
			return null;
		}

		return static::$step_controllers[$step_name];
	}

	/**
	 * @return MVC_Layout
	 */
	public static function getLayout(): MVC_Layout
	{

		if( !static::$layout ) {
			static::$layout = Factory_MVC::getLayoutInstance( static::getBasePath() . 'layout/', 'default' );
		}

		return static::$layout;
	}

	/**
	 * @return Installer_Step_Controller|null
	 */
	public static function getPreviousController(): Installer_Step_Controller|null
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
	public static function goToNext(): void
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
	public static function getComingController(): Installer_Step_Controller|null
	{
		foreach( static::$step_controllers as $controller ) {
			if( $controller->getIsComing() ) {
				return $controller;
			}
		}

		return null;
	}

	/**
	 * @return MVC_View
	 */
	public static function getView(): MVC_View
	{
		return new MVC_View( static::getBasePath() . 'views/' );
	}


	/**
	 * @return string
	 */
	public static function buttonBack(): string
	{
		return static::getView()->render( 'button/back' );
	}

	/**
	 * @return string
	 */
	public static function buttonNext(): string
	{
		return static::getView()->render( 'button/next-anchor' );
	}
	
	/**
	 * @return string
	 */
	public static function buttonNextSubmit(): string
	{
		return static::getView()->render( 'button/next-submit-button' );
	}
	
	
	/**
	 * @return string
	 */
	public static function getBasePath(): string
	{
		return static::$base_path;
	}

	/**
	 * @param string $base_path
	 */
	public static function setBasePath( string $base_path ): void
	{
		static::$base_path = $base_path;
	}

}
