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

	const CODE_READ_FAILED = 1;
	const CODE_WRITE_FAILED = 2;
	const CODE_CHMOD_FAILED = 3;
	const CODE_DELETE_FAILED = 4;
	const CODE_COPY_FAILED = 5;

	const CODE_IS_NOT_UPLOADED_FILE = 100;

	const CODE_GET_FILE_SIZE_FAILED = 200;

}