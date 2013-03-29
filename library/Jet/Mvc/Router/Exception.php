<?php
/**
 *
 *
 *
 * Router handle exception
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
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
	const CODE_INVALID_ADMIN_UI_CLASS = 50;
	const CODE_INVALID_SITE_UI_CLASS = 60;
}