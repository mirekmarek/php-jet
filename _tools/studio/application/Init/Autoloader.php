<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Autoloader;
use Jet\SysConf_Path;

//use Jet\Debug_Profiler;

//Debug_Profiler::blockStart('INIT - Autoloader');

require SysConf_Path::getLibrary() . 'Jet/Autoloader.php';


Autoloader::initialize();


require SysConf_Path::getApplication() . 'Autoloaders/Jet.php';
Autoloader_Jet::register();

require SysConf_Path::getApplication() . 'Autoloaders/StudioClasses.php';
Autoloader_StudioClasses::register();

require SysConf_Path::getApplication() . 'Autoloaders/ModuleWizards.php';
Autoloader_ModuleWizards::register();

require SysConf_Path::getApplication() . 'Autoloaders/ProjectClasses.php';
Autoloader_ProjectClasses::register();

require SysConf_Path::getApplication() . 'Autoloaders/ProjectModules.php';
Autoloader_ProjectModules::register();


//Debug_Profiler::blockEnd('INIT - Autoloader');