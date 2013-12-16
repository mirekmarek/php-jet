<?php
/**
 *
 *
 *
 * Sites Handler handle exception
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Sites
 */
namespace Jet;

class Mvc_Sites_Handler_Exception extends Exception {
	const CODE_UNKNOWN_SITE = 2;
	const CODE_INVALID_PAGE_DATA = 3;
	const CODE_INVALID_SITE_DATA = 4;

}