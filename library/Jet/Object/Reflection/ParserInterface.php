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
	 * @param string $key
	 * @param string $definition
	 * @param mixed $raw_value
	 * @param mixed $value
	 *
	 * @throws Object_Reflection_Exception
	 */
	public static function parseClassDocComment( &$reflection_data, $key, $definition, $raw_value, $value );

	/**
	 * @param array &$reflection_data
	 * @param string $property_name
	 * @param string $key
	 * @param string $definition
	 * @param mixed $raw_value
	 * @param mixed $value
	 *
	 * @throws Object_Reflection_Exception
	 */
	public static function parsePropertyDocComment( &$reflection_data, $property_name, $key, $definition, $raw_value, $value );
}