<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

if( version_compare( PHP_VERSION, '5.6.4', '<' ) ) {
	if( !headers_sent() ) {
		header( 'HTTP/1.1 500 Internal Server Error' );
	}
	trigger_error( 'PHP 5.6.4 or above is required', E_USER_ERROR );
	die();
}

require 'init.php';

Installer::main();