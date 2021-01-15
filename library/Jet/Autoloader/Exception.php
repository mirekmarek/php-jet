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
class Autoloader_Exception extends Exception
{
	const CODE_UNABLE_TO_DETERMINE_SCRIPT_PATH = 100;
	const CODE_SCRIPT_DOES_NOT_EXIST = 101;
	const CODE_INVALID_CLASS_DOES_NOT_EXIST = 102;
}