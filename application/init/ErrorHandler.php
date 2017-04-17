<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package application bootstrap
 */
namespace Jet;

/** @noinspection PhpIncludeInspection */
require JET_LIBRARY_PATH . 'Jet/Debug/ErrorHandler.php';
Debug_ErrorHandler::registerHandler(
	'Log',
	__NAMESPACE__.'\Debug_ErrorHandler_Handler_Log',
	JET_LIBRARY_PATH.'Jet/Debug/ErrorHandler/Handler/Log.php',
	[
		/** options */
	]
);

if(JET_DEVEL_MODE) {
	Debug_ErrorHandler::registerHandler(
		'Display',
		__NAMESPACE__.'\Debug_ErrorHandler_Handler_Display',
		JET_LIBRARY_PATH.'Jet/Debug/ErrorHandler/Handler/Display.php',
		[
			/** options */
		]
	);

}

Debug_ErrorHandler::initialize();
