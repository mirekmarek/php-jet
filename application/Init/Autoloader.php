<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Autoloader;
use Jet\PATH;

//use Jet\Debug_Profiler;

//Debug_Profiler::blockStart('INIT - Autoloader');

require PATH::LIBRARY().'Jet/Autoloader.php';


Autoloader::initialize();


require PATH::APPLICATION().'Autoloaders/Jet.php';
Autoloader_Jet::register();

require PATH::APPLICATION().'Autoloaders/ApplicationClasses.php';
Autoloader_ApplicationClasses::register();


require PATH::APPLICATION().'Autoloaders/ApplicationModules.php';
Autoloader_ApplicationModules::register();


//Debug_Profiler::blockEnd('INIT - Autoloader');