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
 * @subpackage Object_Reflection
 */
namespace Jet;

class Object_Reflection_Exception extends Exception {
	const CODE_UNKNOWN_CLASS_DEFINITION = 10;
	const CODE_UNKNOWN_PROPERTY_DEFINITION = 20;
}