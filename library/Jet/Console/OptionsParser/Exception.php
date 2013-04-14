<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Console
 * @subpackage OptionsParser
 */
namespace Jet;

class Console_OptionsParser_Exception extends Exception {
	const CODE_UNKNOWN_TYPE = 1;
	const ILEGAL_SHORT_OPTION_DEFINITION = 2;
	const INVALID_OPTIONS = 3;
}