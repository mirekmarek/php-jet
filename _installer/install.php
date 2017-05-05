<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetExampleApp;

if( version_compare( PHP_VERSION, '5.5.4', '<' ) ) {
	if( !headers_sent() ) {
		header( 'HTTP/1.1 500 Internal Server Error' );
	}
	trigger_error( 'PHP 5.5.4 or above is required', E_USER_ERROR );
	die();
}

require 'init.php';

Installer::main();