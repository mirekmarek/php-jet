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
 * @package Javascript
 * @subpackage Javascript_Factory
 */
namespace Jet;

class Javascript_Factory extends Factory {

	/**
	 * @var string
	 */
	protected static $JS_lib_class_name_preffix = "Jet\\Javascript_Lib_";

	/**
	 * @param string $JS_lib_class_name_preffix
	 */
	public static function setJSLibClassNamePreffix($JS_lib_class_name_preffix) {
		self::$JS_lib_class_name_preffix = $JS_lib_class_name_preffix;
	}

	/**
	 * @return string
	 */
	public static function getJSLibClassNamePreffix() {
		return self::$JS_lib_class_name_preffix;
	}

	/**
	 * Returns instance of Javascript class
	 * @see Factory
	 *
	 * @param string $javascript_framework_name
	 * @param Mvc_Layout $layout
	 *
	 * @throws Javascript_Exception
	 * @return Javascript_Lib_Abstract
	 */
	public static function getJavascriptLibInstance( $javascript_framework_name, Mvc_Layout $layout ) {
		$default_class_name = static::$JS_lib_class_name_preffix.$javascript_framework_name;

		$class_name =  static::getClassName( $default_class_name );

		$instance = new $class_name( $layout );
		static::checkInstance( $default_class_name, $instance );
		return $instance;
	}

}