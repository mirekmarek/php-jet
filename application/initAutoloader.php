<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package application bootstrap
 */
namespace Jet;

require JET_LIBRARY_PATH."Jet/Autoloader.php";
Autoloader::initialize();

Autoloader::registerLoader(
	"Jet\\Autoloader_Loader_Jet",
	JET_LIBRARY_PATH."Jet/Autoloader/Loader/Jet.php"
);
Autoloader::registerLoader(
	"Jet\\Autoloader_Loader_ApplicationModules",
	JET_LIBRARY_PATH."Jet/Autoloader/Loader/ApplicationModules.php"
);
Autoloader::registerLoader(
	"Jet\\Autoloader_Loader_Zend",
	JET_LIBRARY_PATH."Jet/Autoloader/Loader/Zend.php"
);
