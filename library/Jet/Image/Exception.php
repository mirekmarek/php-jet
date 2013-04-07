<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Image
 * @subpackage Image_Exception
 */
namespace Jet;

class Image_Exception extends Exception {
	const CODE_IMAGE_FILE_DOES_NOT_EXIST = 1;
	const CODE_IMAGE_FILE_IS_NOT_READABLE = 2;
	const CODE_UNSUPORTED_IMAGE_TYPE = 3;

}