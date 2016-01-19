<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 */
namespace Jet;

class Data_Image_Exception extends Exception {
	const CODE_IMAGE_FILE_DOES_NOT_EXIST = 1;
	const CODE_IMAGE_FILE_IS_NOT_READABLE = 2;
	const CODE_UNSUPPORTED_IMAGE_TYPE = 3;

}