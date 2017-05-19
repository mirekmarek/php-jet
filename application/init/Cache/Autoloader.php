<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

Autoloader::setCacheLoadEnabled(JET_AUTOLOADER_CACHE_LOAD);
Autoloader::setCacheSaveEnabled(JET_AUTOLOADER_CACHE_SAVE);
if(JET_AUTOLOADER_CACHE_SAVE) {
	Autoloader::setCacheSaver( function( $map ) {
		$file_path = JET_PATH_CACHE.'autoloader_class_map.php';

		file_put_contents(
			$file_path,
			'<?php return '.var_export( $map, true ).';'
		);
		@chmod($file_path, JET_IO_CHMOD_MASK_FILE);
	});

}

if(JET_AUTOLOADER_CACHE_LOAD) {
	Autoloader::setCacheLoader( function() {
		$file_path = JET_PATH_CACHE.'autoloader_class_map.php';

		if( !is_file( $file_path ) || !is_readable( $file_path ) ) {
			return false;
		}

		/** @noinspection PhpIncludeInspection */
		return require $file_path;
	} );
}
