<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;


Mvc_Site::setCacheLoadEnabled(JET_MVC_SITE_CACHE_LOAD);
Mvc_Site::setCacheSaveEnabled(JET_MVC_SITE_CACHE_SAVE);

if(JET_MVC_SITE_CACHE_LOAD) {
	Mvc_Site::setCacheLoader( function() {

		$file_path = JET_PATH_CACHE.'mvc/sites.dat';

		if( IO_File::isReadable( $file_path ) ) {
			$data = IO_File::read($file_path);
			return unserialize($data);
		}

		return false;
	} );
}

if(JET_MVC_SITE_CACHE_SAVE) {
	Mvc_Site::setCacheSaver( function( $data ) {

		$file_path = JET_PATH_CACHE.'mvc/sites.dat';

		IO_File::write( $file_path, serialize($data) );

	} );
}


Mvc_Page::setCacheLoadEnabled(JET_MVC_PAGE_CACHE_LOAD);
Mvc_Page::setCacheSaveEnabled(JET_MVC_PAGE_CACHE_SAVE);


if(JET_MVC_PAGE_CACHE_LOAD) {
	Mvc_Page::setCacheLoader( function( $site_id, $locale_str ) {

		$file_path = JET_PATH_CACHE.'mvc/pages_'.$site_id.'_'.$locale_str.'.dat';

		if( IO_File::isReadable( $file_path ) ) {
			$data = IO_File::read($file_path);
			return unserialize($data);
		}

		return false;
	} );
}

if(JET_MVC_PAGE_CACHE_SAVE) {
	Mvc_Page::setCacheSaver( function( $site_id, $locale_str, $data ) {

		$file_path = JET_PATH_CACHE.'mvc/pages_'.$site_id.'_'.$locale_str.'.dat';

		IO_File::write( $file_path, serialize($data) );

	} );
}
