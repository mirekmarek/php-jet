<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

require 'includes/bootstrap_cli.php';

if (!isset($argv[1])) {
	die('Usage: ' . $argv[0] . ' \'Namespace\ClassName\'' . PHP_EOL);
}


$class = $argv[1];

echo PHP_EOL . 'Update for class \'' . $class . '\'' . PHP_EOL;

try {
	class_exists($class);
} catch (\Exception $e) {
	echo PHP_EOL . 'ERROR: ' . $e->getMessage() . PHP_EOL . PHP_EOL;
	die();
}

echo implode(PHP_EOL, DataModel_Helper::getUpdateCommand($class));
DataModel_Helper::update($class);
echo PHP_EOL . PHP_EOL;