<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Config;

define( 'JET_APP_INSTALLER_PATH', JET_PATH_BASE.'_installer/' );

require 'Classes/Installer.php';

Config::setBeTolerant( true );

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
		'Final',
	]
);

Installer::setAvailableLocales(
	[
		'en_US', 'cs_CZ',
	]
);
