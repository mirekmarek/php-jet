<?php
namespace Jet;

require JET_LIBRARY_PATH."Jet/Debug/ErrorHandler.php";
Debug_ErrorHandler::registerHandler(
	"Log",
	"Jet\\Debug_ErrorHandler_Handler_Log",
	JET_LIBRARY_PATH."Jet/Debug/ErrorHandler/Handler/Log.php",
	array(
		/** options */
	)
);
Debug_ErrorHandler::registerHandler(
	"Display",
	"Jet\\Debug_ErrorHandler_Handler_Display",
	JET_LIBRARY_PATH."Jet/Debug/ErrorHandler/Handler/Display.php",
	array(
		/** options */
	)
);

Debug_ErrorHandler::initialize();
