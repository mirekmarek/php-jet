<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Autoloader_Cache;
use Jet\Autoloader_Cache_Backend_Files;
use Jet\SysConf_PATH;

require_once SysConf_PATH::LIBRARY().'Jet/Autoloader/Cache/Backend/Files.php';

Autoloader_Cache::init( new Autoloader_Cache_Backend_Files()  );
