<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace Jet;

$module_name = require 'init/init_modules.php';

echo "Deactivating module '{$module_name}' ... " . PHP_EOL;

try {
	Application_Modules::deactivateModule($module_name);
} catch (Exception $e) {
	handleException($e);
}

ok();
