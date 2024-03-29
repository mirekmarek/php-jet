<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

require_once __DIR__.'/../../Exception.php';

/**
 *
 */
class IO_Dir_Exception extends Exception
{

	public const CODE_CREATE_FAILED = 1;
	public const CODE_OPEN_FAILED = 2;
	public const CODE_REMOVE_FAILED = 3;
	public const CODE_COPY_FAILED = 4;
	public const CODE_MOVE_FAILED = 5;

}