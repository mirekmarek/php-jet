<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

require 'includes/bootstrap_cli.php';

if (!isset($argv[1])) {
	die('Usage: ' . $argv[0] . ' \'Namespace\ClassName\'' . SysConf_Jet::EOL());
}


$class = $argv[1];

echo SysConf_Jet::EOL() . 'Create for class \'' . $class . '\'' . SysConf_Jet::EOL();

try {
	class_exists($class);
} catch (\Exception $e) {
	echo SysConf_Jet::EOL() . 'ERROR: ' . $e->getMessage() . SysConf_Jet::EOL() . SysConf_Jet::EOL();
	die();
}

echo DataModel_Helper::getCreateCommand($class);
DataModel_Helper::create($class);
echo SysConf_Jet::EOL() . SysConf_Jet::EOL();;