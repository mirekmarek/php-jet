<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Content\Images;

use Jet\Exception as Jet_Exception;

/**
 *
 */
class Exception extends Jet_Exception
{
	const CODE_IMAGE_ALREADY_EXIST = 1;
	const CODE_ILLEGAL_THUMBNAIL_DIMENSION = 100;
}