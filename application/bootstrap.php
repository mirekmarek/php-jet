<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

define( 'JET_CONFIG_ENVIRONMENT', 'development' );
//define( 'JET_CONFIG_ENVIRONMENT', 'production' );


$config_dir = __DIR__.'/config/'.JET_CONFIG_ENVIRONMENT.'/';
require( $config_dir.'jet.php' );
require( $config_dir.'paths.php' );
require( $config_dir.'URI.php' );
require( $config_dir.'js_css.php' );


$init_dir = __DIR__.'/Init/';
require( $init_dir.'Profiler.php' );
require( $init_dir.'PHP.php' );
require( $init_dir.'ErrorHandler.php' );
require( $init_dir.'Autoloader.php' );
require( $init_dir.'ClassNames.php' );
//<REMOVE AFTER INSTALLATION> !!!
require( $init_dir.'Installation.php' );
//</REMOVE AFTER INSTALLATION> !!!
require( $init_dir.'Cache.php' );
require( $init_dir.'HTTPRequest.php' );

Application::runMvc();
