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

class Mvc_Router_Config_Default extends Mvc_Router_Config_Abstract {

	/**
	 *
	 * @var string
	 */
	protected static $__config_data_path = 'core_router';

	/**
	 * @var array
	 */
	protected static $__config_properties_definition = array(
		'cache_enabled' => array(
			'type' => self::TYPE_BOOL,
			'default_value' => true,
			'is_required' => true,
			'form_field_label' => 'Enable cache: '
		),
		'cache_backend_type' => array(
			'type' => self::TYPE_STRING,
			'default_value' => 'MySQL',
			'is_required' => true,
			'form_field_label' => 'Cache backend type: ',
			'form_field_type' => 'Select',
			'form_field_get_select_options_callback' => array('Jet\\Mvc_Router_Config_Default', 'getCacheBackendTypesList')
		),
		'default_site_UI_manager_module_name' => array(
			'type' => self::TYPE_STRING,
			'default_value' => 'Jet\\SiteUIDefault',
			'description' => 'Default site UI manager module name',
			'is_required' => true,
			'form_field_label' => 'Default site UI manager module: ',
			'form_field_type' => 'Select',
			'form_field_get_select_options_callback' => array('Jet\\Mvc_Router_Config_Default', 'getSiteUIManagerModulesList')
		),
		'default_admin_UI_manager_module_name' => array(
			'type' => self::TYPE_STRING,
			'default_value' => 'Jet\\AdminUIDefault',
			'description' => 'Default admin UI manager module name',
			'is_required' => true,
			'form_field_label' => 'Default administration UI manager module: ',
			'form_field_type' => 'Select',
			'form_field_get_select_options_callback' => array('Jet\\Mvc_Router_Config_Default', 'getAdminUIManagerModulesList')
		),
		'default_auth_manager_module_name' => array(
			'type' => self::TYPE_STRING,
			'default_value' => 'Jet\\AuthDefault',
			'description' => 'Default authentication and authorization manager module name',
			'is_required' => true,
			'form_field_label' => 'Authentication and authorization manager module: ',
			'form_field_type' => 'Select',
			'form_field_get_select_options_callback' => array('Jet\\Mvc_Router_Config_Default', 'getAuthManagerModulesList')

		),
	);

	/**
	 * @var bool
	 */
	protected $cache_enabled;
	/**
	 * @var string
	 */
	protected $cache_backend_type;
	/**
	 * @var array
	 */
	protected $cache_backend_options;
	/**
	 * @var string
	 */
	protected $default_site_UI_manager_module_name;
	/**
	 * @var string
	 */
	protected $default_admin_UI_manager_module_name;
	/**
	 * @var string
	 */
	protected $default_auth_manager_module_name;

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
	public function getDefaultAdminUIManagerModuleName() {
		return $this->default_admin_UI_manager_module_name;
	}

	/**
	 * @return string
	 */
	public function getDefaultSiteUIManagerModuleName() {
		return $this->default_site_UI_manager_module_name;
	}

	/**
	 * @return string
	 */
	public function getDefaultAuthManagerModuleName() {
		return $this->default_auth_manager_module_name;
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
	public static function getSiteUIManagerModulesList() {
		$result = array();
		$modules = Application_Modules::getActivatedModulesList();
		foreach($modules as $module_info) {
			/**
			 * @var Application_Modules_Module_Info $module_info
			 */
			if(!$module_info->getIsSiteUIManagerModule()) {
				continue;
			}

			$result[$module_info->getName()] = $module_info->getLabel();

		}

		return $result;
	}

	/**
	 * @static
	 * @return array
	 */
	public static function getAdminUIManagerModulesList() {
		$result = array();
		$modules = Application_Modules::getActivatedModulesList();
		foreach($modules as $module) {
			if(!$module->getIsAdminUIManagerModule()) {
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
	public static function getAuthManagerModulesList() {
		$result = array();
		$modules = Application_Modules::getActivatedModulesList();
		foreach($modules as $module) {
			/**
			 * @var Application_Modules_Module_Info $module
			 */
			if(!$module->getIsAuthManagerModule()) {
				continue;
			}

			$result[$module->getName()] = $module->getLabel();

		}

		return $result;
	}
}