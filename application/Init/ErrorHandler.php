<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Debug_ErrorHandler;
use Jet\SysConf_Path;
use Jet\SysConf_Jet;

//Debug_Profiler::blockStart('INIT - ErrorHandler');

require SysConf_Path::getLibrary() . 'Jet/Debug.php';
require SysConf_Path::getLibrary() . 'Jet/Debug/ErrorHandler.php';


require SysConf_Path::getApplication() . 'ErrorHandlers/Log.php';
require SysConf_Path::getApplication() . 'ErrorHandlers/Display.php';
require SysConf_Path::getApplication() . 'ErrorHandlers/ErrorPage.php';


ErrorHandler_Log::register();

if( SysConf_Jet::isDevelMode() ) {
	ErrorHandler_Display::register();
} else {
	ErrorHandler_ErrorPage::register();
}

Debug_ErrorHandler::initialize();

//Debug_Profiler::blockEnd('INIT - ErrorHandler');
