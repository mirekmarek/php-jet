<?php
/**
 *
 * @copyright Copyright (c) 2011-2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package application bootstrap
 */
namespace Jet;

require('defines.php');

if(JET_DEBUG_MODE) {
	require( JET_APPLICATION_PATH . 'init/Profiler.php' );
}

require( JET_APPLICATION_PATH . 'init/ErrorHandler.php' );
require( JET_APPLICATION_PATH . 'init/Autoloader.php' );


//require(JET_APPLICATION_PATH.'_installer/install.php');

Application::start( JET_APPLICATION_ENVIRONMENT );
Mvc::run();
Application::end();
