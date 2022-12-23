<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace Jet;

require 'init.php';

if (!isset($argv[1])) {
	die("Usage: $argv[0] ModuleName" . PHP_EOL);
}

$module_name = $argv[1];


if (!Application_Modules::moduleExists( $module_name)) {
	echo "Module '$module_name' doesn't exist " . PHP_EOL . PHP_EOL;
	exit(20);
}
/**
 * @param Exception $e
 * @param int $error_code
 */
function handleException( Exception $e, int $error_code = 100) : void
{
	echo 'ERROR' . PHP_EOL;
	echo $e->getMessage();
	echo PHP_EOL . PHP_EOL;
	exit($error_code);
}


function ok() : void
{
	echo 'OK' . PHP_EOL . PHP_EOL;
	exit(0);
}

return $module_name;