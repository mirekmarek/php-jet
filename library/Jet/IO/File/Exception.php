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
class IO_File_Exception extends Exception
{

	public const CODE_READ_FAILED = 1;
	public const CODE_WRITE_FAILED = 2;
	public const CODE_CHMOD_FAILED = 3;
	public const CODE_DELETE_FAILED = 4;
	public const CODE_COPY_FAILED = 5;

	public const CODE_IS_NOT_UPLOADED_FILE = 100;

	public const CODE_GET_FILE_SIZE_FAILED = 200;

}