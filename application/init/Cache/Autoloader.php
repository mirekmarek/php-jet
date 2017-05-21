<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Autoloader;

if(JET_AUTOLOADER_CACHE_SAVE) {
	Autoloader::enableCacheSave( function( $data ) {
		Cache_Autoloader::save( $data );
	});
}

if(JET_AUTOLOADER_CACHE_LOAD) {
	Autoloader::enableCacheLoad( function() {
		return Cache_Autoloader::load();
	} );
}
