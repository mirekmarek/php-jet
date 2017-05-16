<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

define('JET_CONFIG_ENVIRONMENT', 'development');

$application_dir = dirname(dirname(__DIR__)) . '/application/';

require_once($application_dir . 'config/' . JET_CONFIG_ENVIRONMENT . '/paths.php');
require_once($application_dir . 'config/' . JET_CONFIG_ENVIRONMENT . '/jet.php');


/** @noinspection PhpIncludeInspection */
require(JET_PATH_APPLICATION . 'init/ErrorHandler.php');

use JetExampleApp\ErrorHandler_Display;
ErrorHandler_Display::register();

$init_dir = JET_PATH_APPLICATION.'init/';
/** @noinspection PhpIncludeInspection */
require( $init_dir.'Autoloader.php');
/** @noinspection PhpIncludeInspection */
require( $init_dir.'ClassNames.php' );

try {
	Application::start();
} catch (\Exception $e) {
	echo 'ERROR: ' . $e->getMessage() . JET_EOL . JET_EOL;
	die();
}
