<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplication\Installer;

use Jet\Config;
use Jet\Form;
use Jet\SysConf_Path;
use Jet\UI;
use Jet\SysConf_Jet;

Config::setBeTolerant( true );

Form::setDefaultViewsDir( __DIR__.'/views/Form/' );
UI::setViewsDir( __DIR__.'/views/UI/' );

require 'Classes/Installer.php';

SysConf_Jet::setCSSPackagerEnabled( false );
SysConf_Jet::setJSPackagerEnabled( false );

Installer::setBasePath( SysConf_Path::getBase().'_installer/' );

Installer::setSteps(
	[
		'Welcome',
		'SystemCheck',
		'DirsCheck',
		'SelectDbType',
		'CreateDB',
		'SelectLocales',
		'CreateBases',
		'Mailing',
		'InstallModules',
		'CreateAdministrator',
		'ConfigureStudio',
		'Final',
	]
);


Installer::setAvailableLocales(
	[
		'en_US', 'cs_CZ',
	]
);
