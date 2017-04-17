<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	echo "\t\tphp {$argv[0]}Namespace locale".PHP_EOL;
	echo PHP_EOL;
	echo "example: php {$argv[0]} ModuleName en_US".PHP_EOL;
	echo "\t\texport module 'ModuleName' en_US dictionary ".PHP_EOL.PHP_EOL;

	echo "example: php {$argv[0]} ".Translator::COMMON_NAMESPACE." en_US".PHP_EOL.PHP_EOL;
	echo "\t\texport common en_US dictionary ".PHP_EOL.PHP_EOL;

	die( );
}

if(!isset($argv[2])){
	show_usage();
}

if(isset($argv[3])) {
	show_usage();
}
$namespace = $argv[1];
$locale = new Locale($argv[2]);


echo Translator::exportDictionary( $namespace, $locale );
