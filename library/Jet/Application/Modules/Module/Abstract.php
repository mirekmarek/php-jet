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
	/**
	*
	* @var Application_Modules_Module_Info
	*/
	protected $module_info;

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
	 * @param Application_Modules_Module_Info $module_info
	 */
	final function __construct( Application_Modules_Module_Info $module_info ) {
		$this->module_info = $module_info;
		$this->initialize();
	}

	/**
	 * Initialization method 
	 */
	protected function initialize() {
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

		$module_name = $this->module_info->getName();
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
			$module_name = $this->module_info->getName();

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
	 * @return Application_Modules_Module_Info
	 */
	public function getModuleInfo() {
		return $this->module_info;
	}

	/**
	 * @return string
	 */
	public function getPublicURI() {
		return JET_MODULES_URI.$this->module_info->getName().'/public/';
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

		$URI .= str_replace('\\', '.', $this->module_info->getName()).'/';
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

		$URL .= $this->module_info->getName().'/';
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