<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace Jet;

/**
 *
 */
class Mvc_Layout_Exception extends Exception
{

	const CODE_SETTING_PROTECTED_MEMBERS_NOT_ALLOWED = 1;
	const CODE_FILE_DOES_NOT_EXIST = 2;
	const CODE_FILE_IS_NOT_READABLE = 3;
	const CODE_INVALID_LAYOUT_FILE_PATH = 4;
}