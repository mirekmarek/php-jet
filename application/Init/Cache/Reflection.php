<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Reflection;

if(JET_CACHE_REFLECTION_LOAD) {
	Reflection::enableCacheLoad( function( $class ) {
		return Cache_Reflection::load( $class );
	} );
}

if(JET_CACHE_REFLECTION_SAVE) {
	Reflection::enableCacheSave( function( $class, $data ) {
		Cache_Reflection::save( $class, $data );
	} );
}
