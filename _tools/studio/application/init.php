<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\Application_Factory;
use Jet\Config;
use Jet\Http_Request;
use Jet\Locale;
use Jet\Translator;
use Jet\Mvc_Factory;
use Jet\SysConf_PATH;
use Jet\Application_Modules;

const JET_PROJECT_APPLICATION_NAMESPACE = 'JetApplication';

require __DIR__.'/config/PATH.php';
require __DIR__.'/config/URI.php';

require __DIR__.'/config/jet.php';
require __DIR__.'/config/js_css.php';

require __DIR__.'/Init/PHP.php';
require __DIR__.'/Init/ErrorHandler.php';
require __DIR__.'/Init/Autoloader.php';





Mvc_Factory::setSiteClassName('JetStudio\\Sites_Site');
Mvc_Factory::setPageClassName('JetStudio\\Pages_Page');
Mvc_Factory::setPageContentClassName('JetStudio\\Pages_Page_Content');

//Config::setBeTolerant( true );
//Config::setConfigDirPath( ProjectConf_PATH::CONFIG() );
Application_Factory::setModuleManifestClassName(__NAMESPACE__.'\Modules_Manifest');
Application_Modules::setBasePath( ProjectConf_PATH::APPLICATION().'Modules/' );


Http_Request::initialize( JET_HIDE_HTTP_REQUEST );

Locale::setCurrentLocale( Application::getCurrentLocale() );
Translator::setCurrentLocale( Application::getCurrentLocale() );

Config::setBeTolerant(true);