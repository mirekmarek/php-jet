<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetExampleApp;

use Jet\Debug_ErrorHandler;

/** @noinspection PhpIncludeInspection */
require JET_PATH_LIBRARY.'Jet/Debug.php';
/** @noinspection PhpIncludeInspection */
require JET_PATH_LIBRARY.'Jet/Debug/ErrorHandler.php';


/** @noinspection PhpIncludeInspection */
require JET_PATH_APPLICATION.'error_handlers/Log.php';
/** @noinspection PhpIncludeInspection */
require JET_PATH_APPLICATION.'error_handlers/Display.php';



ErrorHandler_Log::register();

if( JET_DEVEL_MODE ) {
	ErrorHandler_Display::register();
}

Debug_ErrorHandler::initialize();
