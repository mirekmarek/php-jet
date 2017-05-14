<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/** @noinspection PhpIncludeInspection */
require JET_PATH_LIBRARY.'Jet/Debug/ErrorHandler.php';

require JET_PATH_LIBRARY.'Jet/Debug/ErrorHandler/Handler/Log.php';
Debug_ErrorHandler_Handler_Log::register();


if( JET_DEVEL_MODE ) {
	require JET_PATH_LIBRARY.'Jet/Debug/ErrorHandler/Handler/Display.php';
	Debug_ErrorHandler_Handler_Display::register();
}

Debug_ErrorHandler::initialize();
