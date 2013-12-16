<?php
/**
 *
 *
 *
 * Dispatcher handle exception
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Controller
 */
namespace Jet;

class Mvc_Controller_Exception extends Exception {

	const CODE_UNKNOWN_ACL_ACTION = 1;
	const CODE_INVALID_RESPONSE_CODE = 2;

}