<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace Jet;

require 'init/init.php';

try {
	$list = Application_Modules::allModulesList();
} catch (Application_Modules_Exception $e) {
	die( 'ERROR:' .PHP_EOL . $e->getMessage() . PHP_EOL.PHP_EOL);
}

$max_name_str_len = 10;
$max_vendor_str_len = 7;

foreach ($list as $module_info) {
	$name_str_len = strlen($module_info->getName());
	$vendor_str_len = strlen($module_info->getVendor());

	if ($name_str_len > $max_name_str_len) $max_name_str_len = $name_str_len;
	if ($vendor_str_len > $max_vendor_str_len) $max_vendor_str_len = $vendor_str_len;
}

echo '|-' . str_pad( '', $max_vendor_str_len, '-' ) . '-|-' . str_pad( '', $max_name_str_len, '-' ) . '-|---------|---------|' . PHP_EOL;
echo '| ' . str_pad( 'Vendor', $max_vendor_str_len) . ' | ' . str_pad( 'Module', $max_name_str_len) . ' |  Inst.  | Act.    |' . PHP_EOL;
echo '|-' . str_pad( '', $max_vendor_str_len, '-' ) . '-|-' . str_pad( '', $max_name_str_len, '-' ) . '-|---------|---------|' . PHP_EOL;

foreach ($list as $module_info) {
	echo '| ' . str_pad($module_info->getVendor(), $max_vendor_str_len) . ' ';
	echo '| ' . str_pad($module_info->getName(), $max_name_str_len) . ' | ';
	echo $module_info->isInstalled() ? '  YES  ' : '  NO   ';
	echo ' | ';
	echo $module_info->isActivated() ? '  YES  ' : '  NO   ';
	echo ' | ';
	echo PHP_EOL;
}
echo '|-' . str_pad( '', $max_vendor_str_len, '-' ) . '-|-' . str_pad( '', $max_name_str_len, '-' ) . '-|---------|---------|' . PHP_EOL;
