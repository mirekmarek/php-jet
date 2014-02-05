<?php
/**
 *
 *
 *
 *  Many of the system components uses the factory class and method.
 *
 *  Example:
 *  We want to create an instance of the Site (@see Jet\Site).
 *
 * Do not do this:
 *
 *  $site_data  = new Jet\Mvc_Sites_Site_Default();
 *
 * But use the method of the factory class Jet\Mvc_Sites_Factory:
 *
 *  $site_data = Jet\Mvc_Sites_Factory::getSiteInstance();
 *
 *
 *  The purpose is to ensure exchangeable system components and overall system flexibility.
 *
 *  Example: Do you want to implement your own Site?
 *  In addition to creating your own module (a class that will implement a specific interface or extend an abstract class) is sufficient to overload the original class:
 *
 *  Jet\Mvc_Sites_Factory: setSiteClass( 'JetApplicationModule_MySite_MySite');
 *
 * Next possibility how to specify class overload is 'factory_overload_map' directive in the modules manifest file. @see Mvc_Modules_ModuleInfo
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Factory
 */

namespace Jet;

class Factory extends Object implements Object_Reflection_ParserInterface {

	/**
	 * Map of overloaded classes where the key is the original class name and the value is new class name
	 *
	 * @var array [string=>string]
	 */
	protected static $overload_map = null;


	/**
	 * @see Jet\Factory
	 *
	 * @param string $original_name
	 * @param string $overloaded_name
	 */
	public static function setClassName( $original_name, $overloaded_name ) {

		if(self::$overload_map === null) {
			self::initOverloadMap();
		}

		self::$overload_map[$original_name] = $overloaded_name;
	}

	/**
	 * @see Jet\Factory
	 *
	 * @param string $original_name
	 * @return string
	 */
	public static function getClassName( $original_name ) {

		if(self::$overload_map === null) {
			self::initOverloadMap();
		}

		if( isset(self::$overload_map[$original_name]) ) {
			return self::$overload_map[$original_name];
		}

		return $original_name;
	}


	/**
	 * Try to get instance by factory if the class has defined @JetFactory:class and @JetFactory:method
	 * Otherwise returns new $class_name
	 *
	 * @param string $class_name
	 *
	 * @throws Factory_Exception
	 * @return object
	 */
	public static function getInstance( $class_name ) {

		$definition = Factory_ClassDefinition::getClassDefinition( $class_name );

		$factory_class_name = $definition->getFactoryClass();
		$factory_method = $definition->getFactoryMethod();

		if(
			$factory_class_name &&
			$factory_method
		){
			$factory_callback = array( $factory_class_name, $factory_method );

			if(!is_callable($factory_callback)){
				throw new Factory_Exception(
					$factory_callback[0].'::'.$factory_callback[1].' is not valid factory callback.',
					Factory_Exception::CODE_INVALID_CALLBACK
				);
			}

			return $factory_callback();
		}


		$orig_name = $class_name;
		// get real class name
		$class_name = self::getClassName($orig_name);

		return new $class_name();
	}

	/**
	 * Checks if the instance is instance of @JetFactory::must_be_instance_of class
	 *
	 * @param string $default_class_name
	 * @param Object_Interface|Object $instance
	 *
	 * @throws Factory_Exception
	 */
	public static function checkInstance( $default_class_name, Object_Interface $instance ) {

		$required_class = Factory_ClassDefinition::getClassDefinition( $default_class_name )->getFactoryMandatoryParentClass();

		if(!$required_class){
			throw new Factory_Exception(
				$default_class_name.' @JetFactory:mandatory_parent_class must be defined.',
				Factory_Exception::CODE_MISSING_INSTANCEOF_CLASS_NAME
			);
		}


		if(!($instance instanceof $required_class) ) {
			throw new Factory_Exception(
				'Class ' . get_class($instance) . ' must be descendant of '.$required_class.'.',
				Factory_Exception::CODE_INVALID_CLASS_INSTANCE
			);
		}
	}


	/**
	 * Loads overload map from installed and activated modules
	 *
	 */
	protected static function initOverloadMap() {
		if( self::$overload_map !== null ) {
			return;
		}

		self::$overload_map = array();
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

	/**
	 * @param &$reflection_data
	 * @param string $key
	 * @param string $definition
	 * @param mixed $value
	 *
	 * @throws Object_Reflection_Exception
	 */
	public static function parseClassDocComment( &$reflection_data, $key, $definition, $value ) {
		Factory_ClassDefinition::parseClassDocComment( $reflection_data, $key, $definition, $value );
	}

	/**
	 * @param array &$reflection_data
	 * @param string $property_name
	 * @param string $key
	 * @param string $definition
	 * @param mixed $value
	 *
	 * @throws Object_Reflection_Exception
	 */
	public static function parsePropertyDocComment( &$reflection_data,$property_name, $key, $definition, $value ) {
		Factory_ClassDefinition::parsePropertyDocComment( $reflection_data,$property_name, $key, $definition, $value );
	}

}