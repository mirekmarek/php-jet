<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Object
 * @subpackage Object_Reflection
 */
namespace Jet;

class BaseObject_Reflection_Exception extends Exception {
	const CODE_UNKNOWN_CLASS_DEFINITION = 10;
	const CODE_UNKNOWN_PROPERTY_DEFINITION = 20;
}