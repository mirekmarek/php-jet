<?php
/**
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Memcache
 * @subpackage Memcache_Exception
 */
namespace Jet;

class Memcache_Exception extends Exception {

	const CODE_UNKNOWN_CONNECTION = 1;
	const CODE_CONFIG_ERROR = 3;

	const CODE_UNABLE_TO_CONNECT = 100;
}