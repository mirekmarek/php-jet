<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\Autoloader;
use Jet\SysConf_Path;

//use Jet\Debug_Profiler;

//Debug_Profiler::blockStart('INIT - Autoloader');

require SysConf_Path::LIBRARY().'Jet/Autoloader.php';


Autoloader::initialize();


require SysConf_Path::APPLICATION().'Autoloaders/Jet.php';
Autoloader_Jet::register();

require SysConf_Path::APPLICATION().'Autoloaders/StudioClasses.php';
Autoloader_StudioClasses::register();

require SysConf_Path::APPLICATION().'Autoloaders/ModuleWizards.php';
Autoloader_ModuleWizards::register();

require SysConf_Path::APPLICATION().'Autoloaders/ProjectClasses.php';
Autoloader_ProjectClasses::register();

require SysConf_Path::APPLICATION().'Autoloaders/ProjectModules.php';
Autoloader_ProjectModules::register();


//Debug_Profiler::blockEnd('INIT - Autoloader');