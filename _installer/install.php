<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

if( version_compare( PHP_VERSION, '7.4.1', '<' ) ) {
	if( !headers_sent() ) {
		header( 'HTTP/1.1 500 Internal Server Error' );
	}
	trigger_error( 'PHP 7.4.1 or above is required', E_USER_ERROR );
	die();
}

require 'init.php';

Installer::main();