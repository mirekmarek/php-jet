<?php
/**
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package tools
 */
namespace Jet;

define('JET_CONFIG_ENVIRONMENT', 'development');

$application_dir = dirname(dirname(__DIR__)).'/application/';

require_once( $application_dir . 'config/'.JET_CONFIG_ENVIRONMENT.'/defines.php' );
require_once( $application_dir . 'config/class_names.php' );
/** @noinspection PhpIncludeInspection */
require( JET_APPLICATION_PATH . 'init/ErrorHandler.php' );

Debug_ErrorHandler::registerHandler(
	'Display',
	__NAMESPACE__.'\Debug_ErrorHandler_Handler_Display',
	JET_LIBRARY_PATH.'Jet/Debug/ErrorHandler/Handler/Display.php',
	[]
);


/** @noinspection PhpIncludeInspection */
require( JET_APPLICATION_PATH . 'init/Autoloader.php' );

try {
	Application::start();
} catch( \Exception $e ) {
	echo 'ERROR: '.$e->getMessage().JET_EOL.JET_EOL;
	die();
}
