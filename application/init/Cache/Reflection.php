<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

Reflection::setCacheLoadEnabled(JET_REFLECTION_CACHE_LOAD);
Reflection::setCacheSaveEnabled(JET_REFLECTION_CACHE_SAVE);

if(JET_REFLECTION_CACHE_LOAD) {
	Reflection::setCacheLoader( function( $class ) {

		$file_path = JET_PATH_CACHE.'reflections/'.str_replace( '\\', '__', $class.'.php' );

		if( IO_File::exists( $file_path ) ) {
			/** @noinspection PhpIncludeInspection */
			return require $file_path;
		}

		return false;
	} );
}

if(JET_REFLECTION_CACHE_SAVE) {
	Reflection::setCacheSaver( function( $class, $data ) {
		$file_path = JET_PATH_CACHE.'reflections/'.str_replace( '\\', '__', $class.'.php' );

		IO_File::write( $file_path, '<?php return '.var_export( $data, true ).';' );

	} );
}
