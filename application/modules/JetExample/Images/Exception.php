<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\Images;
use Jet\Exception as Jet_Exception;

class Exception extends Jet_Exception {
	const CODE_IMAGE_ALLREADY_EXIST = 1;
	const CODE_ILLEGAL_THUMBNAIL_DIMENSION = 100;
}