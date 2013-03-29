<?php
namespace Jet;
require "includes/bootstrap_cli.php";

if(!isset($argv[1])) {
    die("Usage: {$argv[0]} ClassName".PHP_EOL );
}


$class = $argv[1];

echo "\nUpdate for class '{$class}'\n";
echo implode("\n", DataModel::helper_getUpdateCommand($class));
DataModel::helper_update($class);
echo "\n\n";