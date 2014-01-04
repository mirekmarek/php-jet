<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package application bootstrap
 */
namespace Jet;


//require("initXHProf.php");

require("defines.php");
require( JET_APPLICATION_PATH . "initErrorHandler.php" );
require( JET_APPLICATION_PATH . "initAutoloader.php" );

//Debug_Profiler::enable();


require(JET_APPLICATION_PATH."_installer/install.php");

Application::start( JET_APPLICATION_ENVIRONMENT );
Mvc::run();
Application::end();
