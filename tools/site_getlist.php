<?php
namespace Jet;
require "includes/bootstrap_cli.php";


$list = Mvc_Sites::getAllSitesList();

$max_ID_strlen = 3;
$max_name_strlen = 5;

foreach( $list as $site ) {
	$ID = (string)$site->getID();
	$name = $site->getName();

	if(strlen($ID)>$max_ID_strlen) {
		$max_ID_strlen = strlen($ID);
	}

	if(strlen($name)>$max_name_strlen) {
		$max_name_strlen = strlen($name);
	}
}

$line = "|-".str_pad("", $max_ID_strlen, "-")."-|-".str_pad("", $max_name_strlen, "-")."-|--------|".PHP_EOL;
echo $line;
echo "| ".str_pad("ID ", $max_ID_strlen, " ")." | ".str_pad("Name ", $max_name_strlen, " ")." | Active |".PHP_EOL;
echo $line;


foreach( $list as $site ) {
	/**
	 * @var Mvc_Sites_Site_Default $site
	 */
	$ID = (string)$site->getID();
	$name = $site->getName();
	
	echo "| ".str_pad($ID, $max_ID_strlen, " ")." | ".str_pad($name, $max_name_strlen, " ")." | ".($site->getIsActive()?"YES":"NO ")."    |".PHP_EOL;
}
echo $line;

