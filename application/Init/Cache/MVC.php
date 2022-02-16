<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\MVC_Cache;
use Jet\SysConf_Path;

use Jet\MVC_Cache_Backend_Files;

require_once SysConf_Path::getLibrary() . 'Jet/MVC/Cache/Backend/Files.php';
$backend = new MVC_Cache_Backend_Files();


/*
use Jet\MVC_Cache_Backend_Redis;
require_once SysConf_Path::getLibrary().'Jet/MVC/Cache/Backend/Redis.php';
$backend = new MVC_Cache_Backend_Redis();
*/

MVC_Cache::init( $backend );

