<?php
namespace Jet;
$module_name = require "includes/modules_main.php";

echo "Reload module manidest '{$module_name}' ... ".PHP_EOL;

try {
    Application_Modules::reloadModuleManifest( $module_name );
} catch(Exception $e) {
	handleException($e);
}
ok();
