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
Autoloader::registerLibraryAutoloaders();
Autoloader::registerApplicationAutoloaders();
Autoloader::initComposerAutoloader();

//Debug_Profiler::blockEnd('INIT - Autoloader');