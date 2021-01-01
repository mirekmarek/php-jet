<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;


$application_dir = dirname(dirname(__DIR__)) . '/application/';

require_once($application_dir . 'config/PATH.php');
require_once($application_dir . 'config/jet.php');


$init_dir = SysConf_PATH::APPLICATION().'Init/';
require( $init_dir.'Autoloader.php');
require( $init_dir.'ClassNames.php' );

