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
 * @subpackage Mvc_Controller
 */
namespace Jet;

class Mvc_Controller_Exception extends Exception {

	const CODE_UNKNOWN_ACL_ACTION = 1;
	const CODE_INVALID_RESPONSE_CODE = 2;

}