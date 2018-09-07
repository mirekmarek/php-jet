<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class IO_Dir_Exception extends Exception
{

	const CODE_CREATE_FAILED = 1;
	const CODE_OPEN_FAILED = 2;
	const CODE_REMOVE_FAILED = 3;
	const CODE_COPY_FAILED = 4;
	const CODE_MOVE_FAILED = 5;

}