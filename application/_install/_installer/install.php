<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Installer
 */
namespace Jet;

if( version_compare(PHP_VERSION, '5.4.4', '<') ) {
	if(!headers_sent()) {
		header('HTTP/1.1 500 Internal Server Error');
	}
	trigger_error('PHP 5.4.4 or above is required', E_USER_ERROR);
	die();
}

define('JET_INSTALLER_PATH', JET_APPLICATION_PATH.'_install/_installer/');
define('JET_INSTALLER_URI', JET_BASE_URI.'application/_install/_installer/');

require 'Installer.php';
(new Installer())->main();