<?php
/**
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
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