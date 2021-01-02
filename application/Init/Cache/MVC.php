<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Mvc_Site;
use Jet\Mvc_Page;
use Jet\SysConf_Cache;

if(SysConf_Cache::isMVC_ENABLED()) {
	Mvc_Site::enableCacheLoad( function() {
		return Cache_MVC::loadSites();
	} );
	Mvc_Site::enableCacheSave( function( $data ) {
		Cache_MVC::saveSites( $data );
	} );
	Mvc_Page::enableCacheLoad( function( $site_id, $locale_str ) {
		return Cache_MVC::loadPages( $site_id, $locale_str );
	} );
	Mvc_Page::enableCacheSave( function( $site_id, $locale_str, $data ) {
		Cache_MVC::savePages( $site_id, $locale_str, $data );
	} );
}

