<?php
/**
 *
 * @copyright Copyright c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplication;

require __DIR__.'/config/Path.php';
require __DIR__.'/config/Jet.php';
require __DIR__.'/config/URI.php';

require __DIR__.'/Init/Profiler.php';
require __DIR__.'/Init/PHP.php';
require __DIR__.'/Init/ErrorHandler.php';
require __DIR__.'/Init/Cache.php';
require __DIR__.'/Init/Autoloader.php';
require __DIR__.'/Init/Factory.php';
require __DIR__.'/Init/HTTPRequest.php';

//<REMOVE AFTER INSTALLATION> !!!
require __DIR__.'/Init/Installation.php';
//</REMOVE AFTER INSTALLATION> !!!

Application::runMVC();
