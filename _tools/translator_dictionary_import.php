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

function show_usage() {
	global $argv;

	echo "Usage: ".PHP_EOL;
	echo "\t\tphp {$argv[0]} [file_path] [installer]".PHP_EOL;
	echo PHP_EOL;
	echo "example: cat ./my_dictionary.csv | php {$argv[0]}".PHP_EOL;
	echo "\t\timports dictionary from ./my_dictionary.csv".PHP_EOL.PHP_EOL;

	echo "example: php {$argv[0]} ./my_dictionary.csv".PHP_EOL.PHP_EOL;
	echo "\t\timports dictionary from ./my_dictionary.csv ".PHP_EOL.PHP_EOL;

	echo "example: cat ./my_dictionary.csv | php {$argv[0]} installer".PHP_EOL;
	echo "\t\timports installer dictionary from ./my_dictionary.csv".PHP_EOL.PHP_EOL;

	echo "example: php {$argv[0]} installer ./my_dictionary.csv".PHP_EOL.PHP_EOL;
	echo "\t\timports installer dictionary from ./my_dictionary.csv ".PHP_EOL.PHP_EOL;
	die( );
}

$stdin = file_get_contents("php://stdin");
if(!isset($argv[1]) && !$stdin){
	show_usage();
}

$installer = false;

$data = null;

if(isset($argv[1])) {
	$file_path = null;
	if($argv[1]=="installer") {
		$installer = true;

		if(isset($argv[2])) {
			$file_path = $argv[2];
		}
	} else {
		$file_path = $argv[1];
	}

	if($file_path) {
		$data = file_get_contents($file_path);
		if(!$data) {
			echo "Unable to read file $file_path";
			exit(100);
		}
	}

}

if($data===null) {
	$data = $stdin;
}

if($installer) {
	require JET_BASE_PATH."_installer/Installer.php";
	Installer::initTranslator();
}



try {
	Translator::importDictionary( $data );
} catch( Exception $e ) {
	echo "FAIL: ".$e->getMessage().PHP_EOL.PHP_EOL;
	exit(100);
}

echo "DONE".PHP_EOL;

