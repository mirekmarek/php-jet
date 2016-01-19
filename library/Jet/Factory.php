<?php
/**
 *
 *
 *
 *  Many of the system components uses the factory class and method.
 *
 *  Example:
 *  We want to create an instance of the Site
 *
 * Do not do this:
 *
 *  $site_data  = new Mvc_Site();
 *
 * But use the method of the factory class Mvc_Sites_Factory:
 *
 *  $site_data = Mvc_Factory::getSiteInstance();
 *
 *
 *  The purpose is to ensure exchangeable system components and overall system flexibility.
 *
 *  Example: Do you want to implement your own Site?
 *  In addition to creating your own module (a class that will implement a specific interface or extend an abstract class) is sufficient to overload the original class:
 *
 *  Mvc_Sites_Factory::setSiteClass( 'module:My.Module\My_Site');
 *
 * Next possibility how to specify class overload is 'factory_overload_map' directive in the modules manifest file. @see Mvc_Modules_ModuleInfo
 *
 *
 * @copyright Copyright (c) 2011-2015 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Factory
 */

namespace Jet;

class Factory extends Object {

	/**
	 * Map of overloaded classes where the key is the original class name and the value is new class name
	 *
	 * @var array [string=>string]
	 */
	protected static $overload_map = null;

	/**
	 * @see Factory
	 *
	 * @param string $default_name
	 * @param string $overloaded_name
	 */
	public static function setClassName( $default_name, $overloaded_name ) {


		if(self::$overload_map === null) {
			self::initOverloadMap();
		}

		self::$overload_map[$default_name] = $overloaded_name;
	}

	/**
	 * @see Factory
	 *
	 * @param string $default_name
	 * @return string
	 */
	public static function getClassName( $default_name ) {

		if(self::$overload_map === null) {
			self::initOverloadMap();
		}

		if( isset(self::$overload_map[$default_name]) ) {
			return self::$overload_map[$default_name];
		}

		if(strpos($default_name,'\\')===false) {
			$default_name = __NAMESPACE__.'\\'.$default_name;
		}
		return $default_name;
	}

	/**
	 * Loads overload map from installed and activated modules
	 *
	 */
	protected static function initOverloadMap() {
		if( self::$overload_map !== null ) {
			return;
		}

		self::$overload_map = [];
		$activated_modules_list = Application_Modules::getActivatedModulesList();

		foreach($activated_modules_list as $module_manifest) {
			/**
			 * @var Application_Modules_Module_Manifest $module_manifest
			 */
			if( $module_manifest->getFactoryOverloadMap() ) {
				self::$overload_map = $module_manifest->getFactoryOverloadMap() + self::$overload_map;
			}
		}

	}

}