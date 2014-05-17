<?php
/**
 *
 *
 *
 * Default router config class
 *
 * @see Mvc/readme.txt
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Router
 */
namespace Jet;

/**
 * Class Mvc_Router_Config_Default
 *
 * @JetConfig:data_path = 'mvc_router'
 */
class Mvc_Router_Config_Default extends Mvc_Router_Config_Abstract {

	/**
	 * @JetConfig:type = Jet\Config::TYPE_BOOL
	 * @JetConfig:default_value = true
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_label = 'Enable cache: '
	 * 
	 * @var bool
	 */
	protected $cache_enabled;
	
	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:default_value = 'MySQL'
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_label = 'Cache backend type: '
	 * @JetConfig:form_field_type = 'Select'
	 * @JetConfig:form_field_get_select_options_callback = ['Jet\Mvc_Router_Config_Default', 'getCacheBackendTypesList']
	 * 
	 * @var string
	 */
	protected $cache_backend_type;
	
	/**
	 * @var array
	 */
	protected $cache_backend_options;
	
	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:default_value = 'Jet\SiteUIDefault'
	 * @JetConfig:description = 'Default site Front Controller module name'
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_label = 'Default site Front Controller module: '
	 * @JetConfig:form_field_type = 'Select'
	 * @JetConfig:form_field_get_select_options_callback = ['Jet\Mvc_Router_Config_Default', 'getSiteFrontControllerModulesList']
	 * 
	 * @var string
	 */
	protected $default_site_front_controller_module_name;
	
	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:default_value = 'Jet\AdminUIDefault'
	 * @JetConfig:description = 'Default admin Front Controller module name'
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_label = 'Default administration Front Controller module: '
	 * @JetConfig:form_field_type = 'Select'
	 * @JetConfig:form_field_get_select_options_callback = ['Jet\Mvc_Router_Config_Default', 'getAdminFrontControllerModulesList']
	 * 
	 * @var string
	 */
	protected $default_admin_front_controller_module_name;
	
	/**
	 * @JetConfig:type = Jet\Config::TYPE_STRING
	 * @JetConfig:default_value = 'Jet\AuthDefault'
	 * @JetConfig:description = 'Default Authentication and Authorization Controller module name'
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_label = 'Authentication and Authorization Controller module: '
	 * @JetConfig:form_field_type = 'Select'
	 * @JetConfig:form_field_get_select_options_callback = ['Jet\Mvc_Router_Config_Default', 'getAuthControllerModulesList']
	 * 
	 * @var string
	 */
	protected $default_auth_controller_module_name;

	/**
	 * @return array
	 */
	public function getCacheBackendOptions() {
		return $this->cache_backend_options;
	}

	/**
	 * @return string
	 */
	public function getCacheBackendType() {
		return $this->cache_backend_type;
	}

	/**
	 * @return boolean
	 */
	public function getCacheEnabled() {
		return $this->cache_enabled;
	}

	/**
	 * @return string
	 */
	public function getDefaultAdminFrontControllerModuleName() {
		return $this->default_admin_front_controller_module_name;
	}

	/**
	 * @return string
	 */
	public function getDefaultSiteFrontControllerModuleName() {
		return $this->default_site_front_controller_module_name;
	}

	/**
	 * @return string
	 */
	public function getDefaultAuthControllerModuleName() {
		return $this->default_auth_controller_module_name;
	}

	/**
	 * @return array
	 */
	public static function getCacheBackendTypesList() {
		return static::getAvailableHandlersList( JET_LIBRARY_PATH.'Jet/Mvc/Router/Cache/Backend/' );
	}

	/**
	 * @static
	 * @return array
	 */
	public static function getSiteFrontControllerModulesList() {
		$result = array();
		$modules = Application_Modules::getActivatedModulesList();
		foreach($modules as $module_manifest) {
			/**
			 * @var Application_Modules_Module_Manifest $module_manifest
			 */
			if(!$module_manifest->getIsSiteFrontController()) {
				continue;
			}

			$result[$module_manifest->getName()] = $module_manifest->getLabel();

		}

		return $result;
	}

	/**
	 * @static
	 * @return array
	 */
	public static function getAdminFrontControllerModulesList() {
		$result = array();

		/**
		 * @var Application_Modules_Module_Manifest[] $modules
		 */
		$modules = Application_Modules::getActivatedModulesList();
		foreach($modules as $module) {
			if(!$module->getIsAdminFrontController()) {
				continue;
			}

			$result[$module->getName()] = $module->getLabel();

		}

		return $result;
	}

	/**
	 * @static
	 * @return array
	 */
	public static function getAuthControllerModulesList() {
		$result = array();
		$modules = Application_Modules::getActivatedModulesList();
		foreach($modules as $module) {
			/**
			 * @var Application_Modules_Module_Manifest $module
			 */
			if(!$module->getIsAuthController()) {
				continue;
			}

			$result[$module->getName()] = $module->getLabel();

		}

		return $result;
	}
}