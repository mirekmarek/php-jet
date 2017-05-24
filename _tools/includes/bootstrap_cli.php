<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

define('JET_CONFIG_ENVIRONMENT', 'development');

$application_dir = dirname(dirname(__DIR__)) . '/application/';

require_once($application_dir . 'config/' . JET_CONFIG_ENVIRONMENT . '/paths.php');
require_once($application_dir . 'config/' . JET_CONFIG_ENVIRONMENT . '/jet.php');


$init_dir = JET_PATH_APPLICATION.'init/';
require( $init_dir.'Autoloader.php');
require( $init_dir.'ClassNames.php' );

