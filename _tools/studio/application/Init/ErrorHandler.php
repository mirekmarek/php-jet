<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\Debug_ErrorHandler;
use Jet\SysConf_PATH;
use Jet\SysConf_Jet;

//Debug_Profiler::blockStart('INIT - ErrorHandler');

require SysConf_PATH::LIBRARY().'Jet/Debug.php';
require SysConf_PATH::LIBRARY().'Jet/Debug/ErrorHandler.php';

require SysConf_PATH::APPLICATION().'ErrorHandlers/Log.php';
require SysConf_PATH::APPLICATION().'ErrorHandlers/Display.php';
require SysConf_PATH::APPLICATION().'ErrorHandlers/ErrorPage.php';



ErrorHandler_Log::register();

if( SysConf_Jet::DEVEL_MODE() ) {
	ErrorHandler_Display::register();
} else {
	ErrorHandler_ErrorPage::register();
}

Debug_ErrorHandler::initialize();

//Debug_Profiler::blockEnd('INIT - ErrorHandler');
