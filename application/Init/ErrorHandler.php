<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Debug_ErrorHandler;

//Debug_Profiler::blockStart('INIT - ErrorHandler');

require JET_PATH_LIBRARY.'Jet/Debug.php';
require JET_PATH_LIBRARY.'Jet/Debug/ErrorHandler.php';


require JET_PATH_APPLICATION.'ErrorHandlers/Log.php';
require JET_PATH_APPLICATION.'ErrorHandlers/Display.php';
require JET_PATH_APPLICATION.'ErrorHandlers/ErrorPage.php';



ErrorHandler_Log::register();

if( JET_DEVEL_MODE ) {
	ErrorHandler_Display::register();
} else {
	ErrorHandler_ErrorPage::register();
}

Debug_ErrorHandler::initialize();

//Debug_Profiler::blockEnd('INIT - ErrorHandler');
