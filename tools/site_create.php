<?php
namespace Jet;
require "includes/bootstrap_cli.php";

function show_usage() {
	echo "Usage:".PHP_EOL;
	echo PHP_EOL;
	echo "\tphp site_create.php [OPTIONS] ".PHP_EOL;
	echo PHP_EOL;
	echo "Options:".PHP_EOL;

	echo "\t--name|-n [required] - site name (internal)".PHP_EOL;
	echo "\t--locales|-l [required] - site locales (comma (,) delimited), e.g. en_US,cs_CZ".PHP_EOL;
	echo "\t--URL|-U [required] - site URL for each locale (example: en_US=http://my-site.tld,cs_CZ=http://muj-web.tld)".PHP_EOL;
	echo "\t--SSL_URL|-S [optimal] - site SSL URL for each locale (example: en_US=https://my-site.tld,cs_CZ=https://muj-web.tld)".PHP_EOL;
	echo "\t--ID|-i [optional] - site ID".PHP_EOL;
	echo "\t--template|-t [optional] - template".PHP_EOL;
	echo PHP_EOL;
	echo "Example:".PHP_EOL;
	echo "\tphp site_create.php --name=\"Site Name\" --locales=en_US,cs_CZ --URL=\"en_US=http://domain.tld,cs_CZ=http://domain.tld/mujweb\"  --ID=mysiteidentifier --template=template ".PHP_EOL;
	echo "\tphp site_create.php -n \"Site Name\" -l \"en_US,cs_CZ\" -U \"en_US=http://domain.tld,cs_CZ=http://domain.tld/mujweb\" -i \"mysiteidentifier\" -t \"template\"".PHP_EOL;
	echo PHP_EOL;
	echo "Parameters ID, SSL_URL and template are optimal".PHP_EOL;
	echo PHP_EOL;

	die();
}

function error( $err_msg ) {
        echo "Error:".PHP_EOL;
        echo "\t{$err_msg}".PHP_EOL;
        echo PHP_EOL;

        show_usage();
}

$params = array(
	"name|n=s"    => "site name (internal)",
	"locales|l=s" => "site locales (comma (,) delimited), e.g. en_US,cs_CZ",
	"URL|U=s" => "site URL for each locale (example: en_US=http://my-site.tld,cs_CZ=http://muj-web.tld)",
	"SSL_URL|S=s" => "site SSL URL for each locale (example: en_US=https://my-site.tld,cs_CZ=https://muj-web.tld)",
	"ID|i-w"   => "site ID",
	"template|t-w"   => "site template"
 );


$options = array();
try {
	$opts = new \Zend_Console_Getopt($params);
	$opts->parse();
	$options = $opts->getOptions();
} catch (\Zend_Console_Getopt_Exception $e) {
	// echo $e->getUsageMessage();
	error($e->getMessage());
	show_usage();
}

if(array_diff(array("name", "URL", "locales"), $options)) {
	show_usage();
}


$locales = explode(",", $opts->getOption("locales"));
$known_locales = array();
foreach($locales as $i=>$locale) {
	$zlc = new Locale( $locale );

	if($zlc->toString()!=$locale) {
		error( "'{$locale}' is not valid locale" );
	}

	$locales[$i] = $zlc;
	$known_locales[] = $zlc->toString();
}

$URLs = array();
$known_URLs = array();
foreach(explode(",",$opts->URL) as $URL_data) {
	if( !strpos($URL_data, "=") ) {
		error( "Invalid URL parameter format " );
	}

	list($locale, $URL) = explode("=", $URL_data);

	if(!in_array($locale, $known_locales)) {
		error( "Unknown locale {$locale}" );
	}



	if( !parse_url($URL) ) {
		error( "URL {$URL} is not valid" );
	}

	if(in_array($URL, $URLs)) {
		error( "URL {$URL} is not unique" );
	}

	if(!isset($URLs[$locale])) {
		$URLs[$locale] = array();
	}

	$URLs[$locale][] = $URL;
	$known_URLs[] = $URL;
}


$site_data = Mvc_Sites::getNewSite($opts->name, $opts->ID);

foreach($locales as $locale) {
	$site_data->addLocale( $locale );

	foreach( $URLs[(string)$locale] as $URL) {
		$site_data->addURL($locale, $URL);
	}
}

$errors = array();
if(!$site_data->validateData($errors)) {
	$error_msg = array();

	foreach( $errors as $error ) {
		$error_msg[] = $error->message." (".$error->code.")";
	}

	error( implode("\n", $error_msg) );
}

//var_dump($site_data);die();
Mvc_Sites::createSite($site_data, $opts->getOption("template"));
echo "END\n";