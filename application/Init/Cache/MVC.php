<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Mvc_Cache;
use Jet\Mvc_Cache_Backend_Files;
use Jet\SysConf_PATH;

require_once SysConf_PATH::LIBRARY().'Jet/Mvc/Cache/Backend/Files.php';


Mvc_Cache::init( new Mvc_Cache_Backend_Files() );

