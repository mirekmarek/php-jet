<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Javascript
 * @subpackage Javascript_Factory
 */
namespace Jet;

class Javascript_Factory extends Factory {
	const DEFAULT_JS_LIB_CLASS_PREFIX = "Jet\\Javascript_Lib_";

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
		$class_name = static::DEFAULT_JS_LIB_CLASS_PREFIX.$javascript_framework_name;

		$class_name =  Factory::getClassName( $class_name );

		$instance = new $class_name( $layout );
		static::checkInstance(static::DEFAULT_JS_LIB_CLASS_PREFIX.$javascript_framework_name, $instance);

		return $instance;
	}

}