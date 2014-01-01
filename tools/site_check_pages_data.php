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

if(!isset($argv[1])) {
    die("Usage: {$argv[0]} siteID".PHP_EOL );
}

$site_ID = $argv[1];

$site_data = Mvc_Sites::getSite( Mvc_Factory::getSiteIDInstance()->createID( $site_ID) );

if(!$site_data) {
	die("Unknown site");
}

try {
	foreach( $site_data->getLocales() as $locale) {
		Mvc_Pages::checkPagesData( $site_ID, $locale );
	}

} catch( Exception $e ) {
	echo PHP_EOL."Error:".PHP_EOL.PHP_EOL;
	echo $e->getMessage();
	echo PHP_EOL;
	exit($e->getCode());
}

exit(0);