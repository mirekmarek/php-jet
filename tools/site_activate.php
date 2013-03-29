<?php
namespace Jet;
require "includes/bootstrap_cli.php";

function show_usage() {
	echo "Usage:".PHP_EOL;
	echo PHP_EOL;
	echo "\tphp site_activate.php siteID ".PHP_EOL;
	echo PHP_EOL;
	echo "Example:".PHP_EOL;
	echo "\tphp site_activate.php mysite ".PHP_EOL;
	echo PHP_EOL;

	die();
}

function error( $err_msg ) {
        echo "Error:".PHP_EOL;
        echo "\t{$err_msg}".PHP_EOL;
        echo PHP_EOL;

        show_usage();
}

if(!isset($argv[1]) || count($argv)>2) {
	show_usage();
}

Mvc_Sites::activateSite( Mvc_Factory::getSiteIDInstance()->createID( $argv[1]) );

echo "END\n";