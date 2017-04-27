<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

define('JET_CONFIG_ENVIRONMENT', 'development');

require('config/class_names.php');

/** @noinspection PhpIncludeInspection */
require('config/'.JET_CONFIG_ENVIRONMENT.'/defines.php');

/** @noinspection PhpIncludeInspection */
require('config/'.JET_CONFIG_ENVIRONMENT.'/defines_URI.php');

/** @noinspection PhpIncludeInspection */
require('config/'.JET_CONFIG_ENVIRONMENT.'/php_setup.php');

if( JET_DEBUG_PROFILER_ENABLED ) {
	/** @noinspection PhpIncludeInspection */
	require( JET_APPLICATION_PATH . 'init/Profiler.php' );
}
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


Application::start();
Application::runMvc();
Application::end();
