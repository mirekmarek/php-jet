<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package tools
 */
namespace Jet;

$application_dir = dirname(dirname(__DIR__)).'/application/';

require_once( $application_dir . 'defines.php' );
require( JET_APPLICATION_PATH . 'init/ErrorHandler.php' );
require( JET_APPLICATION_PATH . 'init/Autoloader.php' );

Application::start( JET_APPLICATION_ENVIRONMENT );