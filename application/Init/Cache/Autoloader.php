<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Autoloader;
use Jet\SysConf_Jet;

if(SysConf_Jet::CACHE_AUTOLOADER_SAVE()) {
	Autoloader::enableCacheSave( function( $data ) {
		Cache_Autoloader::save( $data );
	});
}

if(SysConf_Jet::CACHE_AUTOLOADER_LOAD()) {
	Autoloader::enableCacheLoad( function() {
		return Cache_Autoloader::load();
	} );
}
