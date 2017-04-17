<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Installer
 */
namespace JetExampleApp;

use Jet\Http_Request;
use Jet\Http_Headers;
use Jet\Application;
use Jet\Mvc_Layout;
use Jet\Locale;
use Jet\Translator;
use Jet\Translator_Config;
use Jet\Translator_Backend_PHPFiles_Config;
use Jet\Translator_Backend_PHPFiles;
use Jet\Tr;

/** @noinspection PhpIncludeInspection */
require JET_EXAMPLE_APP_INSTALLER_PATH.'classes/Step/Controller.php';



class Installer {


	/**
	 * @var array
	 */
	protected static $steps = [];

	/**
	 * @var Installer_Step_Controller[]
	 */
	protected static $step_controllers = [];

	/**
	 * @var array
	 */
	protected static $translations = [];

	/**
	 * @var Locale
	 */
	protected static $current_locale;

	/**
	 * @var string
	 */
	protected static $current_step_name;

	/**
	 * @var Installer_Step_Controller
	 */
	protected static $current_step_controller;

	/**
	 * @var string
	 */
	protected static $next_step_name;

	/**
	 * @var Installer_Step_Controller
	 */
	protected static $next_step_controller;


	/**
	 * @var Mvc_Layout
	 */
	protected static $layout;

	/**
	 * @param array $steps
	 */
	public static function setSteps( array $steps)
	{
		static::$steps = $steps;
		static::$step_controllers = [];
	}


	/**
	 * @return array
	 */
	public static function getSteps() {
		return static::$steps;
	}

	/**
	 * @return array
	 */
	public static function getTranslations()
	{
		return self::$translations;
	}

	/**
	 * @param array $translations
	 */
	public static function setTranslations(array $translations)
	{
		self::$translations = $translations;

		if(!static::$current_locale) {
			$default_locale = array_keys($translations)[0];

			static::$current_locale = new Locale( $default_locale );
		}
	}


	/**
	 * @return string
	 */
	public static function getTmpConfigFilePath() {
		return JET_TMP_PATH.'config_install.php';
	}

	/**
	 *
	 */
	public static function main() {
		Http_Request::initialize(true);
		Application::setConfigFilePath( static::getTmpConfigFilePath() );


		static::initTranslator();
		session_start();
		if(empty($_SESSION['current_locale'])) {
			$_SESSION['current_locale'] = static::$current_locale;
		} else {
			static::$current_locale = $_SESSION['current_locale'];
		}

		static::$layout = new Mvc_Layout(JET_EXAMPLE_APP_INSTALLER_PATH.'layout/', 'default');

		list($first_step) = static::$steps;

		if(empty($_SESSION['current_step'])) {
			$_SESSION['current_step'] = $first_step;
		}

		if(Http_Request::GET()->exists('step')) {
			$_SESSION['current_step'] = Http_Request::GET()->getString('step', $first_step);
		}

		Translator::setCurrentLocale($_SESSION['current_locale']);
		static::getStepControllers( $_SESSION['current_step'] );
		Translator::setCurrentNamespace(static::$current_step_name);

		static::$current_step_controller->main();

		static::$layout->setVar('steps', static::$step_controllers);

		Translator::setCurrentNamespace(Translator::COMMON_NAMESPACE);
		echo static::$layout->render();

		exit();
	}

	/**
	 * @return Locale
	 */
	public static function getCurrentLocale() {
		return static::$current_locale;
	}

	/**
	 * @param Locale $locale
	 */
	public static function setCurrentLocale( Locale $locale ) {
		static::$current_locale = $locale;
		$_SESSION['current_locale'] = $locale;
	}


	/**
	 *
	 */
	public static function initTranslator() {
		$config = new Translator_Config(true);
		$config->setData( [
			'backend_type' => 'PHPFiles',
			'auto_append_unknown_phrase' => true
		], false);

		Translator::setConfig($config);


		$backend_config = new Translator_Backend_PHPFiles_Config(true);
		$backend_config->setData( [
							'dictionaries_path' => '%JET_APPLICATION_PATH%_install/_installer/dictionaries/%TRANSLATOR_NAMESPACE%/%TRANSLATOR_LOCALE%.php'
		], false);

		$backend = new Translator_Backend_PHPFiles($backend_config);
		Translator::setBackendInstance($backend);
	}

	/**
	 * @param string $current_step_name
	 */
	protected static function getStepControllers( $current_step_name=null ) {
		$got_current = false;
		$steps = static::$steps;

		if(!$current_step_name) {
			$current_step_name = static::$current_step_name;
		}


		while($steps) {
			$_step_name = array_shift($steps);

			$step_base_path = JET_EXAMPLE_APP_INSTALLER_PATH.'Step/'.$_step_name.'/';

			/** @noinspection PhpIncludeInspection */
			require_once $step_base_path.'Controller.php';

			$class_name = __NAMESPACE__.'\\Installer_Step_'.$_step_name.'_Controller';

			$is_current = ($_step_name==$current_step_name);
			if($is_current) {
				static::$current_step_name = $current_step_name;
				$got_current = true;
				$is_prev = false;
				$is_next = false;
			} else {
				if($got_current) {
					$is_prev = false;
					$is_next = true;

				} else {
					$is_prev = true;
					$is_next = false;
				}
			}

			$URL = '?step='.$_step_name;

			$is_last = ($steps);

			static::$step_controllers[$_step_name] = new $class_name( $step_base_path, $is_prev, $is_current, $is_next, $is_last, $URL );

			if($is_current) {
				static::$current_step_controller = static::$step_controllers[$_step_name];
			}

			$steps_after = static::$step_controllers[$_step_name]->getStepsAfter();

			if($steps_after) {
				foreach($steps_after as $sa) {
					array_unshift($steps, $sa);
				}
				if($is_last) {
					static::$step_controllers[$_step_name]->setIsLast(false);
				}

			}


			if($is_next && !static::$next_step_controller) {
				static::$next_step_name = $_step_name;
				static::$next_step_controller = static::$step_controllers[$_step_name];
			}

		}

	}

	/**
	 * @param $step_name
	 *
	 * @return Installer_Step_Controller
	 */
	protected function getStepControllerInstance( $step_name ) {
		if(isset(static::$step_controllers[$step_name])) {
			return static::$step_controllers[$step_name];
		}

		return static::$step_controllers[$step_name];
	}

	/**
	 * @return string
	 */
	public static function getCurrentStepName() {
		return static::$current_step_name;
	}

	/**
	 * @return Installer_Step_Controller
	 */
	public static function getCurrentStepController() {
		return static::$current_step_controller;
	}

	/**
	 * @return Mvc_Layout
	 */
	public static function getLayout() {
		return static::$layout;
	}

	/**
	 *
	 */
	public static function goNext() {
        static::$next_step_controller = null;

		static::getStepControllers();

		Http_Headers::movedTemporary( static::$next_step_controller->getURL() );
	}


	/**
	 * @return string
	 */
	public static function buttonBack() {
		?>
		<a href="?"><button type="submit" class="btn btn-warning">
			<i class="glyphicon glyphicon-chevron-left"></i>
			<?=Tr::_('Go Back', [], Tr::COMMON_NAMESPACE);?>
		</button></a>
		<?php
		return '';
	}

	/**
	 * @return string
	 */
	public static function buttonNext() {
		?>
		<button type="submit" class="btn btn-primary">
			<?=Tr::_('Go Ahead', [], Tr::COMMON_NAMESPACE);?><i class="glyphicon glyphicon-chevron-right"></i>
		</button>
		<?php
		return '';
	}

	/**
	 * @return string
	 */
	public static function buttonNextSkipIt() {
		?>
		<button type="submit" class="btn btn-info">
			<?=Tr::_('Skip this step', [], Tr::COMMON_NAMESPACE);?><i class="glyphicon glyphicon-chevron-right"></i>
		</button>
		<?php
		return '';
	}

}
