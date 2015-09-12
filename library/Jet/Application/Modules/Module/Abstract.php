<?php
/**
 *
 *
 *
 * Basic module class. Each module must extend this class.
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Application
 * @subpackage Application_Modules
 */
namespace Jet;

abstract class Application_Modules_Module_Abstract extends Object {

	const INSTALL_DIR = '_install/';
	const INSTALL_DICTIONARIES_PATH = '_install/dictionaries/';
	const INSTALL_SCRIPT_PATH = '_install/install.php';
	const UNINSTALL_SCRIPT_PATH = '_install/uninstall.php';

	const VIEWS_DIR = 'views/';
	const LAYOUTS_DIR = 'layouts/';

	/**
	*
	* @var Application_Modules_Module_Manifest
	*/
	protected $module_manifest;

	/**
	 * @var Config[]
	 */
	protected $config = array();

	/**
	 * action => Human readable action description
	 *
	 * Example:
	 *
	 * <code>
	 * protected static $ACL_actions = array(
	 *      'get_data_module_action' => 'Get data',
	 *      'update_record_module_action' => 'Update data',
	 *      'add_record_module_action' => 'Add new data',
	 *      'delete_record_module_action' => 'Delete data'
	 * );
	 * </code>
	 *
	 * @var array
	 */
	protected $ACL_actions = array(
	);


	/**
	 * @param Application_Modules_Module_Manifest $manifest
	 */
	final function __construct( Application_Modules_Module_Manifest $manifest ) {
		$this->module_manifest = $manifest;
		$this->initialize();
	}

	/**
	 * Initialization method
	 */
	protected function initialize() {
	}

	/**
	 * Returns module views directory
	 *
	 * @return string
	 */
	public function getViewsDir() {
		return $this->module_manifest->getModuleDir().static::VIEWS_DIR;
	}

	/**
	 * Returns module layouts directory
	 *
	 * @return string
	 */
	public function getLayoutsDir() {
		return $this->module_manifest->getModuleDir().static::LAYOUTS_DIR;
	}

	/**
	 * @param string $service_type
	 *
	 * @return string
	 */
	protected function getControllerClassName(
		$service_type
	) {
		$controller_suffix = 'Controller_'.$service_type;

		$controller_class_name = JET_APPLICATION_MODULE_NAMESPACE.'\\'.$this->module_manifest->getName().'\\'.$controller_suffix;

		return $controller_class_name;
	}

	/**
	 * Returns controller instance
	 *
	 * @param string $service_type
	 *
	 * @throws Exception
	 *
	 * @return Mvc_Controller_Abstract
	 */
	public function getControllerInstance( $service_type ) {

		$controller_class_name = $this->getControllerClassName( $service_type );

		$controller = new $controller_class_name( $this );

		if (!$controller instanceof Mvc_Controller_Abstract) {
			throw new Exception(
				'Controller \''.$controller_class_name.'\' is not an instance of Mvc_Controller_Abstract'
			);
		}

		$controller->initialize();

		return $controller;
	}

	/**
	 * Calls the action
	 *
	 * @param Mvc_Controller_Abstract $controller
	 * @param string $action
	 * @param array $action_parameters (optional)  @see Mvc_Dispatcher_QueueItem::$action_parameters
	 *
	 * @throws Exception
	 */
	public function callControllerAction( Mvc_Controller_Abstract $controller, $action, array $action_parameters=array() ) {
		$method = $action.'_Action';

		if( !method_exists($controller, $method) ) {
			throw new Exception(
				'Controller method '. get_class($controller).'::'.$method.'() does not exist'
			);
		}

		if(!$controller->checkACL($action, $action_parameters)) {
			return;
		}

		call_user_func_array(array( $controller, $method ), $action_parameters);
	}

	/**
	 * @throws Application_Modules_Exception
	 */
	public function install() {
		$module_dir = $this->module_manifest->getModuleDir();
		$install_script = $module_dir . static::INSTALL_SCRIPT_PATH;

		if(file_exists($install_script)) {
			try {

				/** @noinspection PhpUnusedLocalVariableInspection */
				$module_instance = $this;

				/** @noinspection PhpIncludeInspection */
				require_once $install_script;

			} catch(\Exception $e){

				throw new Application_Modules_Exception(
					'Error while processing installation script: '.get_class($e).'::'.$e->getMessage(),
					Application_Modules_Exception::CODE_FAILED_TO_INSTALL_MODULE
				);
			}
		}

		$this->installDictionaries();

	}

	/**
	 *
	 */
	public function installDictionaries() {
		$module_dir = $this->module_manifest->getModuleDir();
		$dictionaries_path = $module_dir . static::INSTALL_DICTIONARIES_PATH;

		if(!IO_Dir::exists($dictionaries_path)) {
			return;
		}

		$list = IO_Dir::getList( $dictionaries_path, '*.php' );

		$tr_backend_type = 'PHPFiles';

		$backend = Translator_Factory::getBackendInstance( $tr_backend_type );

		$module_name = $this->getModuleManifest()->getName();

		foreach( $list as $path=>$file_name ) {
			list($locale) = explode('.', $file_name);
			$locale = new Locale($locale);

			$dictionary = $backend->loadDictionary( $module_name, $locale, $path );

			$backend->saveDictionary( $dictionary );
		}

	}

	/**
	 * @throws Application_Modules_Exception
	 */
	public function uninstall() {
		$module_dir = $this->module_manifest->getModuleDir();

		$uninstall_script = $module_dir . static::UNINSTALL_SCRIPT_PATH;

		if(file_exists($uninstall_script)) {
			try {

				/** @noinspection PhpUnusedLocalVariableInspection */
				$module_instance = $this;

				/** @noinspection PhpIncludeInspection */
				require_once $uninstall_script;

			} catch(\Exception $e){
				throw new Application_Modules_Exception(
					'Error while processing uninstall script: '.get_class($e).'::'.$e->getMessage(),
					Application_Modules_Exception::CODE_FAILED_TO_UNINSTALL_MODULE
				);
			}
		}
	}


	/**
	 * @see Jet\Mvc_Modules_Module::$ACL_actions_check_map
	 *
	 * @return array
	 */
	public function getAclActions() {
		return $this->ACL_actions;
	}

	/**
	 * @param string $action
	 * @param Auth_Role_Privilege_ContextObject_Interface $action_context (optional)
	 * @param bool $log_if_false (optional, default: true)
	 *
	 * @throws Application_Modules_Exception
	 * @internal param array $action_parameters
	 * @return bool
	 */
	public function checkAclCanDoAction( $action, Auth_Role_Privilege_ContextObject_Interface $action_context=null, $log_if_false=true ) {
		$ACL_actions = $this->getAclActions();

		if(!isset($ACL_actions[$action])) {
			throw new Application_Modules_Exception(
				'Unknown ACL action \''.$action.'\'. Please add record to '.get_class($this).'::$ACL_actions ',
				Application_Modules_Exception::CODE_UNKNOWN_ACL_ACTION
			);
		}


		$module_name = $this->module_manifest->getName();
		return Auth::getCurrentUserHasPrivilege(
				Auth::PRIVILEGE_MODULE_ACTION,
				$module_name.':'.$action,
				$action_context,
				$log_if_false
			);
	}


	/**
	 * Gets module config
	 *
	 *
	 * @param string $config_name (optional, default: main)
	 *
	 * @return Config
	 */
	public function getConfig( $config_name = 'main' ){

		if(!isset($this->config[$config_name]) ) {

			$class_name = get_called_class();

			$class_name = substr($class_name, 0, strrpos($class_name, '\\')).'\Config';

			$this->config[$config_name] = new $class_name( $this->getModuleManifest()->getModuleDir().'config/'.$config_name.'.php' );

		}

		return $this->config[$config_name];
	}

	/**
	 * @return Application_Modules_Module_Manifest
	 */
	public function getModuleManifest() {
		return $this->module_manifest;
	}

	/**
	 * @return string
	 */
	public function getPublicURI() {
		return JET_MODULES_URI.$this->module_manifest->getUriReadyName().'/public/';
	}

}