<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\Autoloader;
use Jet\SysConf_PATH;

//use Jet\Debug_Profiler;

//Debug_Profiler::blockStart('INIT - Autoloader');

require SysConf_PATH::LIBRARY().'Jet/Autoloader.php';


Autoloader::initialize();


require SysConf_PATH::APPLICATION().'Autoloaders/Jet.php';
Autoloader_Jet::register();

require SysConf_PATH::APPLICATION().'Autoloaders/StudioClasses.php';
Autoloader_StudioClasses::register();

require SysConf_PATH::APPLICATION().'Autoloaders/ModuleWizards.php';
Autoloader_ModuleWizards::register();

require SysConf_PATH::APPLICATION().'Autoloaders/ProjectClasses.php';
Autoloader_ProjectClasses::register();

require SysConf_PATH::APPLICATION().'Autoloaders/ProjectModules.php';
Autoloader_ProjectModules::register();


//Debug_Profiler::blockEnd('INIT - Autoloader');