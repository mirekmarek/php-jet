<?php
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

foreach( $site_data->getLocales() as $locale) {
	Mvc_Pages::actualizePages( $site_ID, $locale );
}
