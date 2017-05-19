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

define( 'JET_CONFIG_ENVIRONMENT', 'development' );
//define( 'JET_CONFIG_ENVIRONMENT', 'production' );


$config_dir = __DIR__.'/config/'.JET_CONFIG_ENVIRONMENT.'/';
$init_dir = __DIR__.'/init/';


require( $config_dir.'jet.php' );
require( $config_dir.'paths.php' );
require( $config_dir.'URI.php' );


require( $init_dir.'Profiler.php' );
require( $init_dir.'PHP.php' );
require( $init_dir.'ErrorHandler.php' );
require( $init_dir.'Autoloader.php' );
require( $init_dir.'ClassNames.php' );


//- REMOVE AFTER INSTALLATION -------------
$installer_path = JET_PATH_BASE.'_installer/install.php';
$install_symptom_file = JET_PATH_DATA.'installed.txt';
if(
	IO_File::exists( $installer_path ) &&
	!IO_File::exists( $install_symptom_file )
) {
	/** @noinspection PhpIncludeInspection */
	require( $installer_path );
}
//- REMOVE AFTER INSTALLATION -------------

require( $init_dir.'Cache.php' );




Application_Log::setLogger( new Application_Log_Logger() );

Auth::setController( new Auth_Controller() );

Mvc::getRouter()->afterSiteResolved( function( Mvc_Router_Interface $router ) {

	ErrorPages::setErrorPagesDir(
		$router->getSite()->getPagesDataPath(
			$router->getLocale()
		)
	);

} );



Http_Request::initialize( JET_HIDE_HTTP_REQUEST );

Application::runMvc();

Application::end();
