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
	 * @var Config_Module
	 */
	protected $config;

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
	 * @param Mvc_Dispatcher_Abstract $dispatcher
	 * @param string $service_type
	 *
	 * @return string
	 */
	protected function getControllerClassName(
		/** @noinspection PhpUnusedParameterInspection */
		Mvc_Dispatcher_Abstract $dispatcher,
		$service_type
	) {
		$controller_suffix = 'Controller_'.$service_type;

		$controller_class_name = JET_APPLICATION_MODULE_NAMESPACE.'\\'.$this->module_manifest->getName().'\\'.$controller_suffix;

		return $controller_class_name;
	}

	/**
	 * Returns controller instance
	 *
	 * @param Mvc_Dispatcher_Abstract $dispatcher
	 * @param string $service_type
	 *
	 * @throws Mvc_Dispatcher_Exception
	 * @internal param Mvc_Dispatcher_Queue_Item $queue_item
	 *
	 * @return Mvc_Controller_Abstract
	 */
	public function getControllerInstance( Mvc_Dispatcher_Abstract $dispatcher, $service_type ) {

		$controller_class_name = $this->getControllerClassName( $dispatcher, $service_type );

		$controller = new $controller_class_name( $dispatcher, $this );

		if (!$controller instanceof Mvc_Controller_Abstract) {
			throw new Mvc_Dispatcher_Exception(
				'Controller \''.$controller_class_name.'\' is not an instance of Mvc_Controller_Abstract',
				Mvc_Dispatcher_Exception::CODE_INVALID_CONTROLLER_CLASS
			);
		}

		return $controller;
	}

	/**
	 * Calls the action
	 *
	 * @param Mvc_Controller_Abstract $controller
	 * @param string $action
	 * @param array $action_parameters (optional)  @see Mvc_Dispatcher_QueueItem::$action_parameters
	 *
	 * @throws Mvc_Dispatcher_Exception
	 */
	public function callControllerAction( Mvc_Controller_Abstract $controller, $action, array $action_parameters=array() ) {
		$method = $action.'_Action';

		if( !method_exists($controller, $method) ) {
			throw new Mvc_Dispatcher_Exception(
				'Controller method '. get_class($controller).'::'.$method.'() does not exist',
				Mvc_Dispatcher_Exception::CODE_ACTION_DOES_NOT_EXIST
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
	 * @param bool $log_if_false
	 *
	 * @throws Application_Modules_Exception
	 * @return bool
	 */
	public function checkAclCanDoAction( $action, $log_if_false=true ) {
		if(!isset($this->ACL_actions[$action])) {
			throw new Application_Modules_Exception(
				'Unknown ACL action \''.$action.'\'. Please add record to '.get_class($this).'::$ACL_actions ',
				Application_Modules_Exception::CODE_UNKNOWN_ACL_ACTION
			);
		}

		$module_name = $this->module_manifest->getName();
		return Auth::getCurrentUserHasPrivilege(
				Auth::PRIVILEGE_MODULE_ACTION,
				$module_name.':'.$action,
				$log_if_false
			);
	}


	/**
	 * Gets module config
	 *
	 * @throws Application_Modules_Exception
	 *
	 * @return Config_Module
	 */
	public function getConfig(){
		if(!$this->config) {
			$module_name = $this->module_manifest->getName();

			$class_name = 'JetApplicationModule_'.$module_name.'_Config';

			$this->config = new $class_name( $module_name );

			if(!($this->config instanceof Config_Module)) {
				throw new Application_Modules_Exception(
					'Module \''.$module_name.'\' config class \''.$class_name.'\' must be instance of \Jet\Config_Module !',
					Application_Modules_Exception::CODE_INVALID_MODULE_CONFIG_CLASS
				);
			}
		}

		return $this->config;
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
		return JET_MODULES_URI.$this->module_manifest->getName().'/public/';
	}

	/**
	 * Gets URI to this module AJAX action (page-uri/_ajax_/[module name]/[action])
	 *
	 * @param string $action
	 * @param array $path_fragments(optional), default: no fragments
	 * @param array $GET_params(optional), default: no parameters
	 * @param string|mixed $page_ID (optional), default: current page
	 * @param Locale|string $locale(optional), default: current locale
	 * @param string|mixed $site_ID (optional), default: current site ID
	 *
	 * @return string
	 */
	public function getAjaxURI(
		$action,
		$path_fragments=array(),
		$GET_params=array(),
		$page_ID = null,
		$locale = null,
		$site_ID = null
	){
		return $this->getServiceURI(
				Mvc_Router::SERVICE_TYPE_AJAX,
				$action,
				$path_fragments,
				$GET_params,
				$page_ID,
				$locale,
				$site_ID
			);
	}

	/**
	 * Gets URI to this module REST action (page-uri/_rest_/[module name]/[action])
	 *
	 * @param string $object_name
	 * @param array $path_fragments(optional), default: no fragments
	 * @param array $GET_params(optional), default: no parameters
	 * @param string|mixed $page_ID (optional), default: current page
	 * @param Locale|string $locale(optional), default: current locale
	 * @param string|mixed $site_ID (optional), default: current site ID
	 *
	 * @return string
	 */
	public function getRestURI(
		$object_name,
		$path_fragments=array(),
		$GET_params=array(),
		$page_ID = null,
		$locale = null,
		$site_ID = null
	){
		return $this->getServiceURI(
			Mvc_Router::SERVICE_TYPE_REST,
			$object_name,
			$path_fragments,
			$GET_params,
			$page_ID,
			$locale,
			$site_ID
		);
	}

	/**
	 * Gets URI to this module SYS action (page-uri/_sys_/[module name]/[action])
	 *
	 * @param string $action
	 * @param array $path_fragments(optional), default: no fragments
	 * @param array $GET_params(optional), default: no parameters
	 * @param string|mixed $page_ID (optional), default: current page
	 * @param Locale|string $locale(optional), default: current locale
	 * @param string|mixed $site_ID (optional), default: current site ID
	 *
	 * @return string
	 */
	public function getSysURI(
		$action,
		$path_fragments=array(),
		$GET_params=array(),
		$page_ID = null,
		$locale = null,
		$site_ID = null
	){
		return $this->getServiceURI(
			Mvc_Router::SERVICE_TYPE_SYS,
			$action,
			$path_fragments,
			$GET_params,
			$page_ID,
			$locale,
			$site_ID
		);
	}







	/**
	 * Gets URL to this module AJAX action (http://site/page/_ajax_/[module name]/[action])
	 *
	 * @param string $action
	 * @param array $path_fragments(optional), default: no fragments
	 * @param array $GET_params(optional), default: no parameters
	 * @param string|mixed $page_ID (optional), default: current page
	 * @param Locale|string $locale(optional), default: current locale
	 * @param string|mixed $site_ID (optional), default: current site ID
	 *
	 * @return string
	 */
	public function getAjaxURL(
		$action,
		$path_fragments=array(),
		$GET_params=array(),
		$page_ID = null,
		$locale = null,
		$site_ID = null
	){
		return $this->getServiceURL(
			Mvc_Router::SERVICE_TYPE_AJAX,
			$action,
			$path_fragments,
			$GET_params,
			$page_ID,
			$locale,
			$site_ID
		);
	}

	/**
	 * Gets URL to this module REST action (http://site/page/_rest_/[module name]/[action])
	 *
	 * @param string $action
	 * @param array $path_fragments(optional), default: no fragments
	 * @param array $GET_params(optional), default: no parameters
	 * @param string|mixed $page_ID (optional), default: current page
	 * @param Locale|string $locale(optional), default: current locale
	 * @param string|mixed $site_ID (optional), default: current site ID
	 *
	 * @return string
	 */
	public function getRestURL(
		$action,
		$path_fragments=array(),
		$GET_params=array(),
		$page_ID = null,
		$locale = null,
		$site_ID = null
	){
		return $this->getServiceURL(
			Mvc_Router::SERVICE_TYPE_REST,
			$action,
			$path_fragments,
			$GET_params,
			$page_ID,
			$locale,
			$site_ID
		);
	}

	/**
	 * Gets URL to this module SYS action (http://site/page/_sys_/[module name]/[action])
	 *
	 * @param string $action
	 * @param array $path_fragments(optional), default: no fragments
	 * @param array $GET_params(optional), default: no parameters
	 * @param string|mixed $page_ID (optional), default: current page
	 * @param Locale|string $locale(optional), default: current locale
	 * @param string|mixed $site_ID (optional), default: current site ID
	 *
	 * @return string
	 */
	public function getSysURL(
		$action,
		$path_fragments=array(),
		$GET_params=array(),
		$page_ID = null,
		$locale = null,
		$site_ID = null
	){
		return $this->getServiceURL(
			Mvc_Router::SERVICE_TYPE_SYS,
			$action,
			$path_fragments,
			$GET_params,
			$page_ID,
			$locale,
			$site_ID
		);
	}

	/**
	 * Gets URI to this module service action (page-uri/[service]/[module name]/[action])
	 *
	 * @param string $service_type
	 * @param string $action
	 * @param array $path_fragments(optional), default: no fragments
	 * @param array $GET_params(optional), default: no parameters
	 * @param string|mixed $page_ID (optional), default: current page
	 * @param Locale|string $locale(optional), default: current locale
	 * @param string|mixed $site_ID (optional), default: current site ID
	 *
	 * @return string
	 */
	protected function getServiceURI(
		$service_type,
		$action,
		$path_fragments=array(),
		$GET_params=array(),
		$page_ID = null,
		$locale = null,
		$site_ID = null
	){
		if(!$page_ID) {
			$page_ID = Mvc::getCurrentPageID();
		}
		if(!$site_ID) {
			$site_ID = Mvc::getCurrentSiteID();
		}
		if(!$locale) {
			$locale = Mvc::getCurrentLocale();
		}

		$URI = Mvc_Pages::getURI($page_ID, $locale, $site_ID);

		$sm = Mvc_Router::getCurrentRouterInstance()->getServiceTypesPathFragmentsMap();
		$URI .= $sm[$service_type].'/';

		$URI .= str_replace('\\', '.', $this->module_manifest->getName()).'/';
		$URI .= $action.'/';

		if($path_fragments) {
			$URI .= implode('/', $path_fragments).'/';
		}

		if($GET_params) {
			foreach($GET_params as $k=>$v) {
				$GET_params[$k] = $k.'='.rawurlencode($v);
			}
			$URI .= '?'.implode('&', $GET_params);
		}
		return $URI;
	}


	/**
	 * Gets URL to this module service action (http://site/page/[service]/[module name]/[action])
	 *
	 * @param string $service_type
	 * @param string $action
	 * @param array $path_fragments(optional), default: no fragments
	 * @param array $GET_params(optional), default: no parameters
	 * @param string|mixed $page_ID (optional), default: current page
	 * @param Locale|string $locale(optional), default: current locale
	 * @param string|mixed $site_ID (optional), default: current site ID
	 *
	 * @return string
	 */
	protected function getServiceURL(
		$service_type,
		$action,
		$path_fragments=array(),
		$GET_params=array(),
		$page_ID = null,
		$locale = null,
		$site_ID = null
	){
		if(!$page_ID) {
			$page_ID = Mvc::getCurrentPageID();
		}
		if(!$site_ID) {
			$site_ID = Mvc::getCurrentSiteID();
		}
		if(!$locale) {
			$locale = Mvc::getCurrentLocale();
		}

		$URL = Mvc_Pages::getURL($page_ID, $locale, $site_ID);

		$sm = Mvc_Router::getCurrentRouterInstance()->getServiceTypesPathFragmentsMap();
		$URL .= $sm[$service_type].'/';

		$URL .= $this->module_manifest->getName().'/';
		$URL .= $action.'/';

		if($path_fragments) {
			$URL .= implode('/', $path_fragments).'/';
		}

		if($GET_params) {
			foreach($GET_params as $k=>$v) {
				$GET_params[$k] = $k.'='.rawurlencode($v);
			}
			$URL .= '?'.implode('&', $GET_params);
		}
		return $URL;
	}

}