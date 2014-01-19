<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Installer
 */
namespace Jet;

require JET_INSTALLER_PATH.'Step/Controller.php';

class Installer {

	protected static $steps = array(
		'Welcome',
		'SystemCheck',
		'DirsCheck',
		'Main',
		'Translator',
		'Redis',
		'Memcache',
		'DB',
		'DataModelMain',
		'InstallModules',
		'CreateDB',
		'CoreRouter',
		'CreateAdministrator',
		'CreateSite',
		//'jsLibs',
		'Final'
	);

	/**
	 * @var string
	 */
	protected $current_step_name;

	/**
	 * @var Installer_Step_Controller
	 */
	protected $current_step_controller;

	/**
	 * @var string
	 */
	protected $next_step_name;

	/**
	 * @var Installer_Step_Controller
	 */
	protected $next_step_controller;


	/**
	 * @var Mvc_Layout
	 */
	protected $layout;

	/**
	 * @var Installer_Step_Controller[]
	 */
	protected $step_controllers = array();

	public function getTmpConfigFilePath() {
		return JET_TMP_PATH.'config_install.php';
	}

	public function main() {
		Http_Request::initialize(true);
		Config::setApplicationConfigFilePath( $this->getTmpConfigFilePath() );


		static::initTranslator();
		session_start();
		if(empty($_SESSION['current_locale'])) {
			$_SESSION['current_locale'] = new Locale('en_US');
		}

		$this->layout = new Mvc_Layout(JET_INSTALLER_PATH.'layout/', 'default');

		list($first_step) = self::$steps;

		if(empty($_SESSION['current_step'])) {
			$_SESSION['current_step'] = $first_step;
		}

		if(Http_Request::GET()->exists('step')) {
			$_SESSION['current_step'] = Http_Request::GET()->getString('step', $first_step);
		}

		Translator::setCurrentLocale($_SESSION['current_locale']);
		$this->getStepControllers( $_SESSION['current_step'] );
		Translator::setCurrentNamespace($this->current_step_name);

		$this->current_step_controller->main();

		$this->layout->setVar('steps', $this->step_controllers);

		Translator::setCurrentNamespace(Translator::COMMON_NAMESPACE);
		echo $this->layout->render();

		exit();
	}

	public function getCurrentLocale() {
		return $_SESSION['current_locale'];
	}

	public function setCurrentLocale( Locale $locale ) {
		$_SESSION['current_locale'] = $locale;
	}


	public static function initTranslator() {
		$config = new Translator_Config(true);
		$config->setData( new Data_Array(array(
			'backend_type' => 'PHPFiles',
			'auto_append_unknown_phrase' => true
		)));
		Translator::setConfig($config);


		$backend_config = new Translator_Backend_PHPFiles_Config(true);
		$backend_config->setData( new Data_Array(array( 'translator' => array('backend_options'=>array(
			'dictionaries_path' => '%JET_APPLICATION_PATH%_installer/dictionaries/%TRANSLATOR_NAMESPACE%/%TRANSLATOR_LOCALE%.php'
		)))));

		$backend = new Translator_Backend_PHPFiles($backend_config);
		Translator::setBackendInstance($backend);
	}

	protected function getStepControllers( $current_step_name=null ) {
		$got_current = false;
		$steps = self::$steps;

		if(!$current_step_name) {
			$current_step_name = $this->current_step_name;
		}


		while($steps) {
			$_step_name = array_shift($steps);

			$step_base_path = JET_INSTALLER_PATH.'Step/'.$_step_name.'/';

			require_once $step_base_path.'Controller.php';

			$class_name = __NAMESPACE__.'\\Installer_Step_'.$_step_name.'_Controller';

			$is_current = ($_step_name==$current_step_name);
			if($is_current) {
				$this->current_step_name = $current_step_name;
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

			$this->step_controllers[$_step_name] = new $class_name( $this,  $step_base_path, $is_prev, $is_current, $is_next, $is_last, $URL );

			if($is_current) {
				$this->current_step_controller = $this->step_controllers[$_step_name];
			}

			$steps_after = $this->step_controllers[$_step_name]->getStepsAfter();

			if($steps_after) {
				foreach($steps_after as $sa) {
					array_unshift($steps, $sa);
				}
				if($is_last) {
					$this->step_controllers[$_step_name]->setIsLast(false);
				}

				//var_dump($steps);die();
			}


			if($is_next && !$this->next_step_controller) {
				$this->next_step_name = $_step_name;
				$this->next_step_controller = $this->step_controllers[$_step_name];
			}

		}

	}

	/**
	 * @param $step_name
	 *
	 * @return Installer_Step_Controller
	 */
	protected function getStepControllerInstance( $step_name ) {
		if(isset($this->step_controllers[$step_name])) {
			return $this->step_controllers[$step_name];
		}

		return $this->step_controllers[$step_name];
	}

	/**
	 * @return string
	 */
	public function getCurrentStepName() {
		return $this->current_step_name;
	}

	/**
	 * @return Installer_Step_Controller
	 */
	public function getCurrentStepController() {
		return $this->current_step_controller;
	}

	/**
	 * @return Mvc_Layout
	 */
	public function getLayout() {
		return $this->layout;
	}

	/**
	 * @return array
	 */
	public static function getSteps() {
		return self::$steps;
	}

	public function goNext() {
		$this->getStepControllers();

		Http_Headers::movedTemporary( $this->next_step_controller->getURL() );
	}


}
