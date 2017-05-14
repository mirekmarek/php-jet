<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/** @noinspection PhpIncludeInspection */
require JET_PATH_LIBRARY.'Jet/Autoloader.php';
Autoloader::initialize();


require JET_PATH_LIBRARY.'Jet/Autoloader/Loader/Jet.php';
Autoloader_Loader_Jet::register();


require_once JET_PATH_LIBRARY.'Jet/Autoloader/Loader/ApplicationModules.php';
Autoloader_Loader_ApplicationModules::register();


require JET_PATH_LIBRARY.'Jet/Autoloader/Loader/Zend.php';
Autoloader_Loader_Zend::register();


require JET_PATH_APPLICATION.'classes/Autoloader.php';
/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
\JetExampleApp\Autoloader::register();

