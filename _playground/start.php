<?php

if(PHP_VERSION_ID<80000) {
	echo PHP_EOL.PHP_EOL.'Sorry, but PHP Jet requires PHP 8.0 and newer. Your PHP version is: '.phpversion().PHP_EOL.PHP_EOL;
	
	die();
}

$host = 'localhost';
$port = 8000;
$workers = 10;

$dir = dirname(__DIR__);
$router = __DIR__.DIRECTORY_SEPARATOR.'router.php';


echo PHP_EOL.PHP_EOL;
echo 'Please enter test server host address od press ENTER.'.PHP_EOL.PHP_EOL;
$_host = trim(readline('Host ('.$host.' is default): '));

if($_host) {
	$host = $_host;
}


echo PHP_EOL.PHP_EOL;
echo 'Please enter test server TCP port od press ENTER.'.PHP_EOL.PHP_EOL;
$_port = (int)readline('TCP port ('.$port.' is default): ');

if($_port>0) {
	$port = $_port;
}

echo PHP_EOL.PHP_EOL;
echo 'Starting test server http://'.$host.':'.$port.PHP_EOL.PHP_EOL;



$command = "php -S $host:$port $router -t $dir";

putenv('PHP_CLI_SERVER_WORKERS='.$workers);

passthru( $command );
