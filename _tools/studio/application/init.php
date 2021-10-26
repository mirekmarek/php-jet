<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetStudio;

use Jet\Factory_Application;
use Jet\Config;
use Jet\Factory_DataModel;
use Jet\Http_Request;
use Jet\Locale;
use Jet\SysConf_Jet_Form;
use Jet\SysConf_Jet_Http;
use Jet\SysConf_Jet_Modules;
use Jet\SysConf_Jet_UI;
use Jet\Translator;
use Jet\Factory_Mvc;


require __DIR__.'/config/Path.php';
require __DIR__.'/config/URI.php';
require __DIR__.'/config/Jet.php';

require ProjectConf_Path::getApplication().'Init/Cache/MVC.php';

require __DIR__.'/Init/PHP.php';
require __DIR__.'/Init/ErrorHandler.php';
require __DIR__.'/Init/Autoloader.php';



Http_Request::initialize( SysConf_Jet_Http::getHideRequest() );

Locale::setCurrentLocale( Application::getCurrentLocale() );
Translator::setCurrentLocale( Application::getCurrentLocale() );

SysConf_Jet_UI::setViewsDir( __DIR__.'/views/ui/' );
SysConf_Jet_Form::setDefaultViewsDir( __DIR__.'/views/form/' );

AccessControl::handle();

SysConf_Jet_Modules::setActivatedModulesListFilePath( ProjectConf_Path::getData().'activated_modules_list.php' );
SysConf_Jet_Modules::setInstalledModulesListFilePath( ProjectConf_Path::getData().'installed_modules_list.php' );


Config::setBeTolerant(true);
Config::setConfigDirPath( ProjectConf_Path::getConfig() );

Project::setApplicationNamespace('JetApplication');

Factory_DataModel::setPropertyDefinitionClassNamePrefix(__NAMESPACE__.'\DataModel_Definition_Property_');
Factory_DataModel::setModelDefinitionClassNamePrefix(__NAMESPACE__.'\DataModel_Definition_Model_');
Factory_Mvc::setBaseClassName( Bases_Base::class );
Factory_Mvc::setPageClassName( Pages_Page::class );
Factory_Mvc::setPageContentClassName( Pages_Page_Content::class );
Factory_Application::setModuleManifestClassName( Modules_Manifest::class );
