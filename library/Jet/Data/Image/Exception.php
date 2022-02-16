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
class Data_Image_Exception extends Exception
{
	const CODE_IMAGE_FILE_DOES_NOT_EXIST = 1;
	const CODE_IMAGE_FILE_IS_NOT_READABLE = 2;
	const CODE_UNSUPPORTED_IMAGE_TYPE = 3;

}