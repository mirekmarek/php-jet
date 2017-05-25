<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\IO_File;
use Jet\IO_Dir;

/**
 *
 */
class Cache_MVC {

	/**
	 * @return string
	 */
	public static function getDir()
	{
		return JET_PATH_CACHE.'mvc/';
	}

	/**
	 *
	 * @return string
	 */
	public static function getPathSites()
	{
		return static::getDir().'sites.dat';
	}

	/**
	 * @param string $site_id
	 * @param string $locale_str
	 *
	 * @return string
	 */
	public static function getPathPages( $site_id, $locale_str )
	{
		return static::getDir().'pages_'.$site_id.'_'.$locale_str.'.dat';
	}


	/**
	 *
	 * @return bool|mixed
	 */
	public static function loadSites()
	{
		$file_path = static::getPathSites();

		if(
			!IO_File::isReadable($file_path)
		) {
			return false;
		}

		$data = IO_File::read($file_path);
		return unserialize($data);
	}


	/**
	 * @param array  $data
	 */
	public static function saveSites( $data )
	{
		$file_path = static::getPathSites();

		IO_File::write( $file_path, serialize($data) );
	}

	/**
	 *
	 * @param string $site_id
	 * @param string $locale_str
	 *
	 * @return bool|mixed
	 */
	public static function loadPages( $site_id, $locale_str )
	{
		$file_path = static::getPathPages( $site_id, $locale_str );

		if(
			!IO_File::isReadable($file_path)
		) {
			return false;
		}

		$data = IO_File::read($file_path);
		return unserialize($data);
	}


	/**
	 *
	 * @param string $site_id
	 * @param string $locale_str
	 *
	 * @param array  $data
	 */
	public static function savePages( $site_id, $locale_str, $data )
	{
		$file_path = static::getPathPages( $site_id, $locale_str );

		IO_File::write( $file_path, serialize($data) );
	}


	/**
	 * 
	 */
	public static function invalidate()
	{
		$dir_path = static::getDir();

		if( IO_Dir::exists($dir_path) ) {
			IO_Dir::remove($dir_path);
		}

	}
}