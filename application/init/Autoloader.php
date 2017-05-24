<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Autoloader;
//use Jet\Debug_Profiler;

//Debug_Profiler::blockStart('INIT - Autoloader');

require JET_PATH_LIBRARY.'Jet/Autoloader.php';


Autoloader::initialize();


require JET_PATH_APPLICATION.'autoloaders/Jet.php';
Autoloader_Jet::register();

require JET_PATH_APPLICATION.'autoloaders/ApplicationClasses.php';
Autoloader_ApplicationClasses::register();


require JET_PATH_APPLICATION.'autoloaders/ApplicationModules.php';
Autoloader_ApplicationModules::register();


//Debug_Profiler::blockEnd('INIT - Autoloader');