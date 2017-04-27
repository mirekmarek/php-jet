<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetExampleApp;

define('JET_APP_INSTALLER_PATH', JET_BASE_PATH.'_installer/');
define('JET_APP_INSTALLER_DATA_PATH', JET_APP_INSTALLER_PATH.'data/');

require 'classes/Installer.php';

Installer::setSteps(
	[
		'Welcome',
		'SystemCheck',
		'DirsCheck',
		'SelectDbType',
		'CreateDB',
		'SelectLocales',
		'CreateSite',
		'Mailing',
		'InstallModules',
		'CreateAdministrator',
		'Final'
	]
);

Installer::setAvailableLocales([
	'en_US',
	'cs_CZ'
]);
