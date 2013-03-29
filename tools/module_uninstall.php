<?php
namespace Jet;
$module_name = require "includes/modules_main.php";

echo "Uninstalling module '{$module_name}' ... ".PHP_EOL;

try {
    Application_Modules::uninstallModule( $module_name );
} catch(Exception $e) {
	handleException($e);
}

ok();
