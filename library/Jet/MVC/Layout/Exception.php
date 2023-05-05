<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class MVC_Layout_Exception extends Exception
{

	public const CODE_SETTING_PROTECTED_MEMBERS_NOT_ALLOWED = 1;
	public const CODE_FILE_DOES_NOT_EXIST = 2;
	public const CODE_FILE_IS_NOT_READABLE = 3;
	public const CODE_INVALID_LAYOUT_FILE_PATH = 4;
}