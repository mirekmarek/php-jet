<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetStudio;

use Jet\Config;
use Jet\Http_Request;
use Jet\Locale;
use Jet\SysConf_Jet_Form;
use Jet\SysConf_Jet_Modules;
use Jet\SysConf_Jet_UI;
use Jet\SysConf_Path;
use Jet\Translator;


require __DIR__.'/config/Path.php';
require __DIR__.'/config/URI.php';
require __DIR__.'/config/Jet.php';

require JetStudio_Conf_Path::getApplication().'Init/Cache/MVC.php';

require __DIR__.'/Init/PHP.php';
require __DIR__.'/Init/ErrorHandler.php';
require __DIR__.'/Init/Autoloader.php';



Http_Request::initialize();

Locale::setCurrentLocale( JetStudio::getCurrentLocale() );
Translator::setCurrentLocale( JetStudio::getCurrentLocale() );

SysConf_Jet_UI::setViewsDir( __DIR__.'/views/ui/' );
SysConf_Jet_Form::setDefaultViewsDir( __DIR__.'/views/form/' );


SysConf_Jet_Modules::setActivatedModulesListFilePath( JetStudio_Conf_Path::getData().'activated_modules_list.php' );
SysConf_Jet_Modules::setInstalledModulesListFilePath( JetStudio_Conf_Path::getData().'installed_modules_list.php' );


Config::setBeTolerant(true);
SysConf_Path::setConfig( JetStudio_Conf_Path::getConfig() );

JetStudio::setApplicationNamespace('JetApplication');
