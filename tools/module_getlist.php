<?php
namespace Jet;
require "includes/bootstrap_cli.php";

try {
	$list = Application_Modules::getAllModulesList( false );
} catch (Application_Modules_Exception $e) {
	die("ERROR:\n". $e->getMessage()."\n\n");
}

$max_name_strlen = 10;
$max_label_strlen = 10;
$max_vendor_strlen = 7;

foreach($list as $module_info) {
	$name_strlen = strlen( $module_info->getName() );
	$label_strlen = strlen( $module_info->getLabel() );
	$vendor_strlen = strlen( $module_info->getVendor() );

	if($name_strlen>$max_name_strlen) $max_name_strlen = $name_strlen;
	if($label_strlen>$max_name_strlen) $max_label_strlen = $label_strlen;
	if($vendor_strlen>$max_vendor_strlen) $max_vendor_strlen = $vendor_strlen;
}

echo "|-".str_pad("", $max_vendor_strlen, "-")."-|-".str_pad("", $max_name_strlen, "-")."-|---------|---------|".PHP_EOL;
echo "| ".str_pad("Vendor", $max_vendor_strlen)." | ".str_pad("Module", $max_name_strlen)." |  Inst.  | Act.    |".PHP_EOL;
echo "|-".str_pad("", $max_vendor_strlen, "-")."-|-".str_pad("", $max_name_strlen, "-")."-|---------|---------|".PHP_EOL;

foreach($list as $module_info) {
	echo "| ".str_pad($module_info->getVendor(), $max_vendor_strlen)." ";
	echo "| ".str_pad($module_info->getName(), $max_name_strlen)." | ";
	echo $module_info->getIsInstalled() ? "  YES  ":"  NO   ";
	echo " | ";
	echo $module_info->getIsActivated() ? "  YES  ":"  NO   ";
	echo " | ";
	echo PHP_EOL;
}
echo "|-".str_pad("", $max_vendor_strlen, "-")."-|-".str_pad("", $max_name_strlen, "-")."-|---------|---------|".PHP_EOL;
