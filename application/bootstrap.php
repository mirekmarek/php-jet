<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\IO_File;
use Jet\Application_Log;
use Jet\Auth;
use Jet\Mvc;
use Jet\Mvc_Router;
use Jet\ErrorPages;
use Jet\Form_Field_WYSIWYG;
use Jet\Http_Request;


define( 'JET_CONFIG_ENVIRONMENT', 'development' );
//define( 'JET_CONFIG_ENVIRONMENT', 'production' );


$config_dir = __DIR__.'/config/'.JET_CONFIG_ENVIRONMENT.'/';
$init_dir = __DIR__.'/Init/';

require( $config_dir.'jet.php' );
require( $config_dir.'paths.php' );
require( $config_dir.'URI.php' );
require( $config_dir.'js_css.php' );


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

Http_Request::initialize( JET_HIDE_HTTP_REQUEST );


Mvc::getRouter()->afterSiteAndLocaleResolved( function( Mvc_Router $router ) {
	$current_site = $router->getSite();
	$current_locale = $router->getLocale();

	ErrorPages::setErrorPagesDir(
		$current_site->getPagesDataPath(
			$current_locale
		)
	);

	switch($current_site->getId()) {
		case Application::getAdminSiteId():
			Application_Log::setLogger( new Application_Log_Logger_Admin() );
			Auth::setController( new Auth_Controller_Admin() );
			break;
		case Application::getRESTSiteId():
			Application_Log::setLogger( new Application_Log_Logger_REST() );
			Auth::setController( new Auth_Controller_REST() );
			break;
		default:
			Application_Log::setLogger( new Application_Log_Logger_Web() );
			Auth::setController( new Auth_Controller_Web() );
			break;
	}

	if( $current_locale->getLanguage()!='en' ) {
		Form_Field_WYSIWYG::setDefaultEditorConfigValue(
			'language_url',
			JET_URI_PUBLIC.'scripts/tinymce/language/'.$current_locale->toString().'.js'
		);
	}
} );


Application::runMvc();

Application::end();
