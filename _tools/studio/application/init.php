<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetStudio;

use Jet\Factory_Application;
use Jet\Application_Modules_Handler_Default;
use Jet\Config;
use Jet\Factory_DataModel;
use Jet\Http_Request;
use Jet\Locale;
use Jet\SysConf_Jet_Form;
use Jet\SysConf_Jet_Http;
use Jet\SysConf_Jet_UI;
use Jet\Translator;
use Jet\Factory_Mvc;
use Jet\Application_Modules;


require __DIR__.'/config/Path.php';
require __DIR__.'/config/URI.php';
require __DIR__.'/config/Jet.php';

require ProjectConf_Path::getApplication().'Init/Cache/MVC.php';

require __DIR__.'/Init/PHP.php';
require __DIR__.'/Init/ErrorHandler.php';
require __DIR__.'/Init/Autoloader.php';


SysConf_Jet_UI::setViewsDir( __DIR__.'/views/ui/' );
SysConf_Jet_Form::setDefaultViewsDir( __DIR__.'/views/form/' );

Http_Request::initialize( SysConf_Jet_Http::getHideRequest() );

Locale::setCurrentLocale( Application::getCurrentLocale() );
Translator::setCurrentLocale( Application::getCurrentLocale() );

AccessControl::handle();

Config::setBeTolerant(true);

Project::setApplicationNamespace('JetApplication');

Factory_DataModel::setPropertyDefinitionClassNamePrefix(__NAMESPACE__.'\DataModel_Definition_Property_');
Factory_DataModel::setModelDefinitionClassNamePrefix(__NAMESPACE__.'\DataModel_Definition_Model_');
Factory_Mvc::setBaseClassName( Bases_Base::class );
Factory_Mvc::setPageClassName( Pages_Page::class );
Factory_Mvc::setPageContentClassName( Pages_Page_Content::class );
Factory_Application::setModuleManifestClassName( Modules_Manifest::class );

Config::setConfigDirPath( ProjectConf_Path::getConfig() );

/**
 * @var Application_Modules_Handler_Default $modules_handler
 */
$modules_handler = Application_Modules::getHandler();
$modules_handler->setActivatedModulesListFilePath( ProjectConf_Path::getData().'activated_modules_list.php' );
$modules_handler->setInstalledModulesListFilePath( ProjectConf_Path::getData().'installed_modules_list.php' );

