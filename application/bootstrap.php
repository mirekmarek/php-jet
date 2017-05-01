<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

use JetExampleApp\Application_Log_Logger;
use JetExampleApp\Auth_Controller;

define('JET_CONFIG_ENVIRONMENT', 'development');



/** @noinspection PhpIncludeInspection */
require('config/'.JET_CONFIG_ENVIRONMENT.'/class_names.php');
/** @noinspection PhpIncludeInspection */
require('config/'.JET_CONFIG_ENVIRONMENT.'/paths.php');
/** @noinspection PhpIncludeInspection */
require('config/'.JET_CONFIG_ENVIRONMENT.'/jet.php');
/** @noinspection PhpIncludeInspection */
require('config/'.JET_CONFIG_ENVIRONMENT.'/URI.php');
/** @noinspection PhpIncludeInspection */
require('config/'.JET_CONFIG_ENVIRONMENT.'/php_setup.php');




/** @noinspection PhpIncludeInspection */
require( JET_APPLICATION_PATH . 'init/Profiler.php' );
/** @noinspection PhpIncludeInspection */
require( JET_APPLICATION_PATH . 'init/ErrorHandler.php' );
/** @noinspection PhpIncludeInspection */
require( JET_APPLICATION_PATH . 'init/Autoloader.php' );





//- REMOVE AFTER INSTALLATION -------------
$installer_path = JET_BASE_PATH . '_installer/install.php';
$install_symptom_file = JET_DATA_PATH.'installed.txt';
if(
	IO_File::exists($installer_path) &&
	!IO_File::exists($install_symptom_file)
) {
	/** @noinspection PhpIncludeInspection */
	require( $installer_path );
}
//- REMOVE AFTER INSTALLATION -------------





Application_Log::setLogger( new Application_Log_Logger() );
Auth::setAuthController( new Auth_Controller() );

Application::start();
Application::runMvc();
Application::end();
