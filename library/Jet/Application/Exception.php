<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Application
 * @subpackage Application_Exception
 */
namespace Jet;

class Application_Exception extends Exception {
	const CODE_ENVIRONMENT_NOT_SET = 1;
	const CODE_INVALID_ENVIRONMENT_NAME = 2;
}