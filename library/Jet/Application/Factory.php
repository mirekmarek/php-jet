<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

class Application_Factory extends Factory {
	const DEFAULT_MODULE_INFO_CLASS_NAME = 'Jet\Application_Modules_Module_Info';

	/**
	 * @param string $module_name
	 *
	 * @return Application_Modules_Module_Info
	 */
	public static function getModuleInfoInstance( $module_name ) {
		$class_name =  static::getClassName( static::DEFAULT_MODULE_INFO_CLASS_NAME );
		$instance = new $class_name( $module_name );
		self::checkInstance(static::DEFAULT_MODULE_INFO_CLASS_NAME, $instance);
		return $instance;
	}

	/**
	 * @param string $class_name
	 */
	public static function setModuleInfoClassName( $class_name ) {
		self::setClassName( static::DEFAULT_MODULE_INFO_CLASS_NAME , $class_name);
	}
}