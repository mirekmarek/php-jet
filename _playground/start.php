<?php
function errorMessage( string $message ) : void
{
	
	$border_char = '*';
	$ident = "\t";
	
	$line = $ident.str_pad('', strlen($message)+8, $border_char).PHP_EOL;
	
	$pad = str_pad('', 3, $border_char);
	
	echo PHP_EOL.PHP_EOL;
	echo $line;
	echo "$ident$pad $message $pad".PHP_EOL;
	echo $line;
	echo PHP_EOL;
	
}

if(PHP_VERSION_ID<80000) {
	errorMessage( 'Sorry, but PHP Jet requires PHP 8.0 and newer. Your PHP version is: '.phpversion() );
	
	die();
}

if( PHP_SAPI!='cli' ) {
	errorMessage( 'For command line usage only' );
}


$host = 'localhost';
$port = 8000;
$workers = 10;

$dir = dirname(__DIR__);
$router = __DIR__.DIRECTORY_SEPARATOR.'router.php';


while(true) {
	echo PHP_EOL.PHP_EOL;
	echo 'Please enter test server host address or press ENTER.'.PHP_EOL.PHP_EOL;
	$_host = trim(readline('Host ('.$host.' is default): '));
	
	
	if($_host) {
		if(!filter_var( $_host, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME )) {
			
			errorMessage( 'Please enter valid host name or IP address' );
			
			continue;
		}
		
		$host = $_host;
	}
	
	
	break;
}


while(true) {
	echo PHP_EOL.PHP_EOL;
	echo 'Please enter test server TCP port od press ENTER.'.PHP_EOL.PHP_EOL;
	$_port = readline('TCP port ('.$port.' is default): ');
	
	if($_port) {
		if(!filter_var($_port, FILTER_VALIDATE_INT)) {
			errorMessage( 'Please enter number' );
			
			continue;
		}
		
		$_port = (int)$_port;
		
		$min = 2000;
		$max = 65535;
		
		if($_port>$max) {
			errorMessage( 'Maximal value is '.$max );
			
			continue;
		}
		
		if($_port<$min) {
			errorMessage( 'Minimal value is '.$min );
			
			continue;
		}
		
		$port = $_port;
	}
	
	break;
	
}

echo PHP_EOL.PHP_EOL;
echo 'Starting test server http://'.$host.':'.$port.PHP_EOL.PHP_EOL;



$command = PHP_BINARY." -S $host:$port $router -t $dir";

putenv('PHP_CLI_SERVER_WORKERS='.$workers);

passthru( $command );
