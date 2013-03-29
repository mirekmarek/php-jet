<?php
namespace Jet;

$application_dir = dirname(dirname(__DIR__))."/application/";

require_once( $application_dir . "defines.php" );
require( JET_APPLICATION_PATH . "initErrorHandler.php" );
require( JET_APPLICATION_PATH . "initAutoloader.php" );

Application::start( JET_APPLICATION_ENVIRONMENT );