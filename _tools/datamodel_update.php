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

echo SysConf_Jet::EOL() . 'Update for class \'' . $class . '\'' . SysConf_Jet::EOL();

try {
	class_exists($class);
} catch (\Exception $e) {
	echo SysConf_Jet::EOL() . 'ERROR: ' . $e->getMessage() . SysConf_Jet::EOL() . SysConf_Jet::EOL();
	die();
}

echo implode(SysConf_Jet::EOL(), DataModel_Helper::getUpdateCommand($class));
DataModel_Helper::update($class);
echo SysConf_Jet::EOL() . SysConf_Jet::EOL();