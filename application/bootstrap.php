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


$config_dir = __DIR__.'/config/'.JET_CONFIG_ENVIRONMENT.'/';

require( $config_dir.'class_names.php' );
require( $config_dir.'paths.php' );
require( $config_dir.'jet.php' );
require( $config_dir.'URI.php' );
require( $config_dir.'php_setup.php' );


$init_dir = JET_APPLICATION_PATH . 'init/';

/** @noinspection PhpIncludeInspection */
require( $init_dir.'Profiler.php' );
/** @noinspection PhpIncludeInspection */
require( $init_dir.'ErrorHandler.php' );
/** @noinspection PhpIncludeInspection */
require( $init_dir.'Autoloader.php' );





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
