<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetStudio;

use Jet\DataModel;
use Jet\Factory_Application;
use Jet\Config;
use Jet\Factory_DataModel;
use Jet\Http_Request;
use Jet\Locale;
use Jet\SysConf_Jet_Form;
use Jet\SysConf_Jet_Modules;
use Jet\SysConf_Jet_UI;
use Jet\SysConf_Path;
use Jet\Translator;
use Jet\Factory_MVC;


require __DIR__.'/config/Path.php';
require __DIR__.'/config/URI.php';
require __DIR__.'/config/Jet.php';

require ProjectConf_Path::getApplication().'Init/Cache/MVC.php';

require __DIR__.'/Init/PHP.php';
require __DIR__.'/Init/ErrorHandler.php';
require __DIR__.'/Init/Autoloader.php';



Http_Request::initialize();

Locale::setCurrentLocale( Application::getCurrentLocale() );
Translator::setCurrentLocale( Application::getCurrentLocale() );

SysConf_Jet_UI::setViewsDir( __DIR__.'/views/ui/' );
SysConf_Jet_Form::setDefaultViewsDir( __DIR__.'/views/form/' );

AccessControl::handle();

SysConf_Jet_Modules::setActivatedModulesListFilePath( ProjectConf_Path::getData().'activated_modules_list.php' );
SysConf_Jet_Modules::setInstalledModulesListFilePath( ProjectConf_Path::getData().'installed_modules_list.php' );


Config::setBeTolerant(true);
SysConf_Path::setConfig( ProjectConf_Path::getConfig() );

Project::setApplicationNamespace('JetApplication');

$property_definition_class_names = [
	DataModel::TYPE_ID               => DataModel_Definition_Property_Id::class,
	DataModel::TYPE_ID_AUTOINCREMENT => DataModel_Definition_Property_IdAutoIncrement::class,
	DataModel::TYPE_STRING           => DataModel_Definition_Property_String::class,
	DataModel::TYPE_BOOL             => DataModel_Definition_Property_Bool::class,
	DataModel::TYPE_INT              => DataModel_Definition_Property_Int::class,
	DataModel::TYPE_FLOAT            => DataModel_Definition_Property_Float::class,
	DataModel::TYPE_LOCALE           => DataModel_Definition_Property_Locale::class,
	DataModel::TYPE_DATE             => DataModel_Definition_Property_Date::class,
	DataModel::TYPE_DATE_TIME        => DataModel_Definition_Property_DateTime::class,
	DataModel::TYPE_CUSTOM_DATA      => DataModel_Definition_Property_CustomData::class,
	DataModel::TYPE_DATA_MODEL       => DataModel_Definition_Property_DataModel::class,
];
foreach($property_definition_class_names as $type=>$class_name) {
	Factory_DataModel::setPropertyDefinitionClassName($type, $class_name);
}

$model_definition_class_names = [
	DataModel::MODEL_TYPE_MAIN         => DataModel_Definition_Model_Main::class,
	DataModel::MODEL_TYPE_RELATED_1TO1 => DataModel_Definition_Model_Related_1to1::class,
	DataModel::MODEL_TYPE_RELATED_1TON => DataModel_Definition_Model_Related_1toN::class,
];

foreach($model_definition_class_names as $type=>$class_name) {
	Factory_DataModel::setModelDefinitionClassName($type, $class_name);
}

Factory_MVC::setBaseClassName( Bases_Base::class );
Factory_MVC::setPageClassName( Pages_Page::class );
Factory_MVC::setPageContentClassName( Pages_Page_Content::class );
Factory_Application::setModuleManifestClassName( Modules_Manifest::class );
