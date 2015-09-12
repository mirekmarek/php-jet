<?php
/**
 *
 *
 *
 * Sites Data handle exception
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

class Mvc_Site_Exception extends Exception {

        const CODE_DATA_CHECK_FATAL_ERROR = 20;

	const CODE_URL_NOT_DEFINED = 100;
	const CODE_URL_INVALID_FORMAT = 101;
	const CODE_URL_ALREADY_ADDED = 102;

}