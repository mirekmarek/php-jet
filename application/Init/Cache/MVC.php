<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Mvc_Site;
use Jet\Mvc_Page;

if(JET_CACHE_MVC_SITE_LOAD) {
	Mvc_Site::enableCacheLoad( function() {
		return Cache_MVC::loadSites();
	} );
}

if(JET_CACHE_MVC_SITE_SAVE) {
	Mvc_Site::enableCacheSave( function( $data ) {
		Cache_MVC::saveSites( $data );
	} );
}

if(JET_CACHE_MVC_PAGE_LOAD) {
	Mvc_Page::enableCacheLoad( function( $site_id, $locale_str ) {
		return Cache_MVC::loadPages( $site_id, $locale_str );
	} );
}

if(JET_CACHE_MVC_PAGE_SAVE) {
	Mvc_Page::enableCacheSave( function( $site_id, $locale_str, $data ) {
		Cache_MVC::savePages( $site_id, $locale_str, $data );
	} );
}
