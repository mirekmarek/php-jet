<?php
namespace Jet;


//require("initXHProf.php");

require("defines.php");
require( JET_APPLICATION_PATH . "initErrorHandler.php" );
require( JET_APPLICATION_PATH . "initAutoloader.php" );

//Debug_Profiler::enable();


//require(JET_APPLICATION_PATH."_installer/install.php");

Application::start( JET_APPLICATION_ENVIRONMENT );
Mvc::run();
Application::end();
