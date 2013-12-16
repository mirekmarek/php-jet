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

function error( $err_msg ) {
        echo "Error:".PHP_EOL;
        echo "\t{$err_msg}".PHP_EOL;
        echo PHP_EOL;
}


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

/*
$options_parser = new Console_Getopt();
$options_parser->setHelpHeader(
	"Usage:".PHP_EOL
	.PHP_EOL
	."\tphp site_create.php [OPTIONS] ".PHP_EOL
	.PHP_EOL
);

$name_option = new Console_Getopt_OptionDefinition("name", "n");
$name_option->setHelp("site name (internal)");
$options_parser->addOption( $name_option );

$locales_option = new Console_Getopt_OptionDefinition("locales", "l");
$locales_option->setHelp("site locales (comma (,) delimited), e.g. en_US,cs_CZ");
$options_parser->addOption( $locales_option );

$URL_option = new Console_Getopt_OptionDefinition("URL", "U");
$URL_option->setHelp("site URL for each locale (example: en_US=http://my-site.tld,cs_CZ=http://muj-web.tld/cs/)");
$options_parser->addOption( $URL_option );

$ssl_URL_option = new Console_Getopt_OptionDefinition("SSL_URL", "S");
$ssl_URL_option->setIsRequired(false);
$ssl_URL_option->setHelp("site SSL URL for each locale (example: en_US=https://my-site.tld,cs_CZ=https://muj-web.tld/cs/)");
$options_parser->addOption( $ssl_URL_option );

$ID_option = new Console_Getopt_OptionDefinition("ID", "i");
$ID_option->setIsRequired(false);
$ID_option->setHelp("site ID");
$options_parser->addOption($ID_option);

$template_option = new Console_Getopt_OptionDefinition("template", "t");
$template_option->setIsRequired(false);
$template_option->setHelp("site template");
$options_parser->addOption($template_option);

//$t_option = new Console_Getopt_OptionDefinition("param", "param", "p", Console_Getopt_OptionDefinition::TYPE_BOOL);
//$options_parser->addOption($t_option);

if(!$options_parser->parse()) {
	$options_parser->showErrorMessage();
	exit(1);
}

$options = $options_parser->getOptions();

var_dump($options);
die();
*/

$locales = explode(",", $options["locales"] );
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
foreach(explode(",",$options["URL"]) as $URL_data) {
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


$site_data = Mvc_Sites::getNewSite($options["name"], $options["ID"]);

foreach($locales as $locale) {
	$site_data->addLocale( $locale );

	foreach( $URLs[(string)$locale] as $URL) {
		$site_data->addURL($locale, $URL);
	}
}

$errors = array();
if(!$site_data->validateProperties($errors)) {
	$error_msg = array();

	foreach( $errors as $error ) {
		$error_msg[] = $error->message." (".$error->code.")";
	}

	error( implode("\n", $error_msg) );
}

//var_dump($site_data);die();
Mvc_Sites::createSite($site_data, $options["template"]);
echo "END\n";