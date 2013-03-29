<?php
/**
 *
 *
 *
 * Dispatcher handle exception
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
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