<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Debug_ErrorHandler;
use Jet\PATH;

//Debug_Profiler::blockStart('INIT - ErrorHandler');

require PATH::LIBRARY().'Jet/Debug.php';
require PATH::LIBRARY().'Jet/Debug/ErrorHandler.php';


require PATH::APPLICATION().'ErrorHandlers/Log.php';
require PATH::APPLICATION().'ErrorHandlers/Display.php';
require PATH::APPLICATION().'ErrorHandlers/ErrorPage.php';



ErrorHandler_Log::register();

if( JET_DEVEL_MODE ) {
	ErrorHandler_Display::register();
} else {
	ErrorHandler_ErrorPage::register();
}

Debug_ErrorHandler::initialize();

//Debug_Profiler::blockEnd('INIT - ErrorHandler');
