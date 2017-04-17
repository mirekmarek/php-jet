<?php
/**
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package application bootstrap
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


//require( JET_APPLICATION_PATH . '_install/_installer/install.php' );

Application::start();
Application::runMvc();
Application::end();
