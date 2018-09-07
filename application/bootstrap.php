<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;


require( __DIR__.'/config/paths.php' );
require( JET_PATH_CONFIG.'jet.php' );
require( JET_PATH_CONFIG.'URI.php' );
require( JET_PATH_CONFIG.'js_css.php' );


require( JET_PATH_INIT.'Profiler.php' );
require( JET_PATH_INIT.'PHP.php' );
require( JET_PATH_INIT.'ErrorHandler.php' );
require( JET_PATH_INIT.'Autoloader.php' );
require( JET_PATH_INIT.'ClassNames.php' );
//<REMOVE AFTER INSTALLATION> !!!
require( JET_PATH_INIT.'Installation.php' );
//</REMOVE AFTER INSTALLATION> !!!
require( JET_PATH_INIT.'Cache.php' );
require( JET_PATH_INIT.'HTTPRequest.php' );

Application::runMvc();
