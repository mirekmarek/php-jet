<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

DataModel_Definition::setCacheLoadEnabled(JET_DATAMODEL_DEFINITION_CACHE_LOAD);
DataModel_Definition::setCacheSaveEnabled(JET_DATAMODEL_DEFINITION_CACHE_SAVE);

if(JET_DATAMODEL_DEFINITION_CACHE_LOAD) {
	DataModel_Definition::setCacheLoader( function( $class ) {

		$file_path = JET_PATH_CACHE.'datamodel_definitions/'.str_replace( '\\', '__', $class.'.php' );

		if( IO_File::exists( $file_path ) ) {
			/** @noinspection PhpIncludeInspection */
			return require $file_path;
		}

		return false;
	} );
}

if(JET_DATAMODEL_DEFINITION_CACHE_SAVE) {
	DataModel_Definition::setCacheSaver( function( $class, $data ) {
		$file_path = JET_PATH_CACHE.'datamodel_definitions/'.str_replace( '\\', '__', $class.'.php' );

		IO_File::write( $file_path, '<?php return '.var_export( $data, true ).';' );

	} );
}
