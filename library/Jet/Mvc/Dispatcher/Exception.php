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
 * @subpackage Mvc_Dispatcher
 */
namespace Jet;

class Mvc_Dispatcher_Exception extends Exception {
	const CODE_INVALID_CONTROLLER_CLASS = 2;
	const CODE_CONTROLLER_CLASS_DOES_NOT_EXIST = 3;
	const CODE_ACTION_DOES_NOT_EXIST = 4;

	const CODE_DISPATCHER_IS_NOT_INITIALIZED = 100;

}