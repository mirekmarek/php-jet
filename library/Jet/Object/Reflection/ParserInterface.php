<?php
/**
 *
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Object
 * @subpackage Reflection
 */

namespace Jet;

interface Object_Reflection_ParserInterface {
	/**
	 * @param array &$reflection_data
	 * @param string $class_name
	 * @param string $key
	 * @param string $definition
	 * @param mixed $value
	 * @return
	 */
	public static function parseClassDocComment(&$reflection_data, $class_name, $key, $definition, $value);

	/**
	 * @param array &$reflection_data
	 * @param string $class_name
	 * @param string $property_name
	 * @param string $key
	 * @param string $definition
	 * @param mixed $value
	 * @return
	 */
	public static function parsePropertyDocComment(&$reflection_data, $class_name, $property_name, $key, $definition, $value);
}