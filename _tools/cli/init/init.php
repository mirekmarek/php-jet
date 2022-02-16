<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace Jet;

$application_dir = dirname( __DIR__, 3 ) . '/application/';

require_once $application_dir . 'config/Path.php';
require_once $application_dir . 'config/Jet.php';
require_once $application_dir .'/config/URI.php';

$init_dir = SysConf_Path::getApplication().'Init/';
require $init_dir.'ErrorHandler.php';
require $init_dir.'Cache.php';
require $init_dir.'Autoloader.php';

