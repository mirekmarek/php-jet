<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Exception;

/**
 *
 */
class Content_Gallery_Exception extends Exception
{
	const CODE_IMAGE_ALREADY_EXIST = 1;
	const CODE_ILLEGAL_THUMBNAIL_DIMENSION = 100;
}