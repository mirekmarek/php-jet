<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Db
 * @subpackage Db_Exception
 */
namespace Jet;

class Db_Exception extends Exception {

	const CODE_UNKNOWN_CONNECTION = 1;
	const CODE_CONFIG_ERROR = 3;

}