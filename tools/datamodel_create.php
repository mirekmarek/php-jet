<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package tools
 */
namespace Jet;
require "includes/bootstrap_cli.php";

if(!isset($argv[1])) {
    die("Usage: {$argv[0]} ClassName".PHP_EOL );
}


$class = $argv[1];

echo "\nCreate for class '{$class}'\n";
echo DataModel::helper_getCreateCommand($class);
DataModel::helper_create($class);
echo "\n\n";