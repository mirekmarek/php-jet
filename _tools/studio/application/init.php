<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\Application_Factory;
use Jet\Application_Modules_Handler_Default;
use Jet\Config;
use Jet\DataModel_Factory;
use Jet\Http_Request;
use Jet\Locale;
use Jet\SysConf_Jet;
use Jet\Translator;
use Jet\Mvc_Factory;
use Jet\Application_Modules;


require __DIR__.'/config/Path.php';
require __DIR__.'/config/URI.php';
require __DIR__.'/config/Jet.php';

require ProjectConf_Path::getApplication().'Init/Cache/MVC.php';

require __DIR__.'/Init/PHP.php';
require __DIR__.'/Init/ErrorHandler.php';
require __DIR__.'/Init/Autoloader.php';



Http_Request::initialize( SysConf_Jet::isHideHttpRequest() );

Locale::setCurrentLocale( Application::getCurrentLocale() );
Translator::setCurrentLocale( Application::getCurrentLocale() );

AccessControl::handle();

Config::setBeTolerant(true);

Project::setApplicationNamespace('JetApplication');

DataModel_Factory::setPropertyDefinitionClassNamePrefix(__NAMESPACE__.'\DataModel_Definition_Property_');
DataModel_Factory::setModelDefinitionClassNamePrefix(__NAMESPACE__.'\DataModel_Definition_Model_');


Mvc_Factory::setSiteClassName('JetStudio\\Sites_Site');
Mvc_Factory::setPageClassName('JetStudio\\Pages_Page');
Mvc_Factory::setPageContentClassName('JetStudio\\Pages_Page_Content');


Config::setBeTolerant( true );
Config::setConfigDirPath( ProjectConf_Path::getConfig() );

/**
 * @var Application_Modules_Handler_Default $modules_handler
 */
$modules_handler = Application_Modules::getHandler();
$modules_handler->setActivatedModulesListFilePath( ProjectConf_Path::getData().'activated_modules_list.php' );
$modules_handler->setInstalledModulesListFilePath( ProjectConf_Path::getData().'installed_modules_list.php' );

Application_Factory::setModuleManifestClassName(__NAMESPACE__.'\Modules_Manifest');
Application_Modules::setBasePath( ProjectConf_Path::getApplication().'Modules/' );
