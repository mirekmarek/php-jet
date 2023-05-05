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
class Autoloader_Exception extends Exception
{
	public const CODE_UNABLE_TO_DETERMINE_SCRIPT_PATH = 100;
	public const CODE_SCRIPT_DOES_NOT_EXIST = 101;
	public const CODE_INVALID_CLASS_DOES_NOT_EXIST = 102;
}