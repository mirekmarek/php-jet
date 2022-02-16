<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Autoloader_Cache;
use Jet\Autoloader_Cache_Backend_Files;
use Jet\SysConf_Path;

require_once SysConf_Path::getLibrary() . 'Jet/Autoloader/Cache/Backend/Files.php';

Autoloader_Cache::init( new Autoloader_Cache_Backend_Files() );
