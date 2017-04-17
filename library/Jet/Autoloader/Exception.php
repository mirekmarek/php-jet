<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Autoloader
 * @subpackage Autoloader_Abstract
 */

namespace Jet;
class Autoloader_Exception extends Exception {

	const CODE_INVALID_AUTOLOADER_CLASS_DOES_NOT_EXIST = 1;
	const CODE_INVALID_AUTOLOADER_CLASS = 2;

	const CODE_INVALID_CLASS_DOES_NOT_EXIST = 100;

}