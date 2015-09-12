<?php
/**
 *
 *
 *
 * Router handle exception
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Router
 */
namespace Jet;

class Mvc_Router_Exception extends Exception {
	const CODE_URL_NOT_DEFINED = 20;
	const CODE_UNABLE_TO_PARSE_URL = 33;
	const CODE_UNKNOWN_SCHEME = 40;
}