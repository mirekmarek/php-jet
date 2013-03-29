<?php
/**
 *
 *
 *
 * View exception
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Layout
 */
namespace Jet;

class Mvc_Layout_Exception extends Exception {

	const CODE_SETTING_PROTECTED_MEMBERS_NOT_ALLOWED = 1;
	const CODE_FILE_DOES_NOT_EXIST = 2;
	const CODE_FILE_IS_NOT_READABLE = 3;
	const CODE_INVALID_LAYOUT_FILE_PATH = 4;
}