<?php
/**
 *
 *
 *
 * Pages Handler exception
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Pages
 */
namespace Jet;

class Mvc_Pages_Handler_Exception extends Exception {
	const CODE_HANDLER_ERROR = 100;
	const CODE_UNKNOWN_SITE = 101;
}