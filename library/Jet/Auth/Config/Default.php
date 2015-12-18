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
 * Class Auth_Config_Default
 *
 * @JetConfig:data_path = 'auth'
 */
class Auth_Config_Default extends Auth_Config_Abstract {


	/**
	 * @JetConfig:type = Config::TYPE_STRING
	 * @JetConfig:default_value = 'AuthDefault'
	 * @JetConfig:description = 'Default Authentication and Authorization Controller module name'
	 * @JetConfig:is_required = true
	 * @JetConfig:form_field_label = 'Authentication and Authorization Controller module: '
	 * @JetConfig:form_field_type = 'Select'
	 * @JetConfig:form_field_get_select_options_callback = ['Auth_Config_Default', 'getAuthControllerModulesList']
	 * 
	 * @var string
	 */
	protected $default_auth_controller_module_name;


	/**
	 * @return string
	 */
	public function getDefaultAuthControllerModuleName() {
		return $this->default_auth_controller_module_name;
	}


	/**
	 * @static
	 * @return array
	 */
	public static function getAuthControllerModulesList() {
		$result = [];
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