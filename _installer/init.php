<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplication\Installer;

use Jet\Config;
use Jet\SysConf_Jet_Form;
use Jet\SysConf_Jet_UI;
use Jet\SysConf_Path;
use Jet\SysConf_Jet_PackageCreator_CSS;
use Jet\SysConf_Jet_PackageCreator_JavaScript;

Config::setBeTolerant( true );

SysConf_Jet_Form::setDefaultViewsDir( __DIR__.'/views/form/' );
SysConf_Jet_UI::setViewsDir( __DIR__.'/views/ui/' );


require 'Classes/Installer.php';

require_once 'Classes/IntlMock.php';

SysConf_Jet_PackageCreator_CSS::setEnabled( false );
SysConf_Jet_PackageCreator_JavaScript::setEnabled( false );

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
		'CreateVisitor',
		'CreateRESTClient',
		'ConfigureStudio',
		'Final',
	]
);


Installer::setAvailableLocales(
	[
		'en_US', 'cs_CZ',
	]
);
