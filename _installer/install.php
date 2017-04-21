<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Installer
 */
namespace JetExampleApp;

if( version_compare(PHP_VERSION, '5.5.4', '<') ) {
	if(!headers_sent()) {
		header('HTTP/1.1 500 Internal Server Error');
	}
	trigger_error('PHP 5.5.4 or above is required', E_USER_ERROR);
	die();
}

define('JET_EXAMPLE_APP_INSTALLER_PATH', JET_BASE_PATH.'_installer/');
define('JET_EXAMPLE_APP_INSTALLER_DATA_PATH', JET_EXAMPLE_APP_INSTALLER_PATH.'data/');

require 'classes/Installer.php';

/** @noinspection PhpTraditionalSyntaxArrayLiteralInspection */
Installer::setSteps(
	array(
		'Welcome',
		'SystemCheck',
		'DirsCheck',
		'SelectDbType',
		'CreateDB',
		'SelectLocales',
		'CreateSite',
		'InstallModules',
		'CreateAdministrator',
		'Final'
	)
);

/** @noinspection PhpTraditionalSyntaxArrayLiteralInspection */
Installer::setAvailableLocales(array(
	'en_US',
	'cs_CZ'
));

//TODO: konfigurace mailu webu
//TODO: instalace slovniku

Installer::main();