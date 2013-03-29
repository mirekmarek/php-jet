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
 * @subpackage Javascript_Lib_TinyMCE
 */
namespace Jet;

class Javascript_Lib_TinyMCE_Exception extends Exception {

	const CODE_EDITOR_CONFIGURATION_MISSING = 1;
	const CODE_UNKNOWN_EDITOR_CONFIGURATION = 2;

}