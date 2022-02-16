<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Autoloader;
use Jet\SysConf_Path;

//use Jet\Debug_Profiler;

//Debug_Profiler::blockStart('INIT - Autoloader');

require_once SysConf_Path::getLibrary() . 'Jet/Autoloader.php';


Autoloader::initialize();


require SysConf_Path::getApplication() . 'Autoloaders/Jet.php';
Autoloader_Jet::register();

require SysConf_Path::getApplication() . 'Autoloaders/ApplicationClasses.php';
Autoloader_ApplicationClasses::register();


require SysConf_Path::getApplication() . 'Autoloaders/ApplicationModules.php';
Autoloader_ApplicationModules::register();


//Debug_Profiler::blockEnd('INIT - Autoloader');