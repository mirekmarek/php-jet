<?php
/**
 *
 *
 *
 * General object exception
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Object
 * @subpackage Object_Exception
 */
namespace Jet;

class BaseObject_Exception extends Exception {

	const CODE_UNDEFINED_PROPERTY = 1;
	const CODE_ACCESS_PROTECTED_PROPERTY = 2;


}