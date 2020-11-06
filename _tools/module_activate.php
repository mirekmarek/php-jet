<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;
$module_name = require "includes/modules_main.php";

echo "Activating module '{$module_name}' ... " . PHP_EOL;

try {
	Application_Modules::activateModule($module_name);
} catch (Exception $e) {
	handleException($e);
}

ok();
