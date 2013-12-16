<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Javascript
 * @subpackage Javascript_Exception
 */
namespace Jet;

class Javascript_Exception extends Exception {

	const CODE_INVALID_JS_LIB_PATH = 50;
	const CODE_JS_NOT_FOUND = 51;
	const CODE_JS_SCRIPT_READ_FAILED = 52;
	const CODE_UNKNOWN_JS_LIB_OPTION = 100;

}