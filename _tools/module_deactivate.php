<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;
$module_name = require "includes/modules_main.php";

echo "Deactivating module '{$module_name}' ... ".PHP_EOL;

try {
    Application_Modules::deactivateModule($module_name);
} catch(Exception $e) {
	handleException($e);
}

ok();
