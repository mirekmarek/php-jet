<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\IO_File;
use Jet\IO_Dir;
use Jet\SysConf_PATH;

/**
 *
 */
class Cache_MVC {

	/**
	 * @return string
	 */
	public static function getDir() : string
	{
		return SysConf_PATH::CACHE().'mvc/';
	}

	/**
	 *
	 * @return string
	 */
	public static function getPathSites() : string
	{
		return static::getDir().'sites.dat';
	}

	/**
	 * @param string $site_id
	 * @param string $locale_str
	 *
	 * @return string
	 */
	public static function getPathPages( string $site_id, string $locale_str ) : string
	{
		return static::getDir().'pages_'.$site_id.'_'.$locale_str.'.dat';
	}


	/**
	 *
	 * @return mixed
	 */
	public static function loadSites() : mixed
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
	public static function saveSites( array $data ) : void
	{
		$file_path = static::getPathSites();

		IO_File::write( $file_path, serialize($data) );
	}

	/**
	 *
	 * @param string $site_id
	 * @param string $locale_str
	 *
	 * @return mixed
	 */
	public static function loadPages( string $site_id, string $locale_str ) : mixed
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
	public static function savePages( string $site_id, string $locale_str, array $data ) : void
	{
		$file_path = static::getPathPages( $site_id, $locale_str );

		IO_File::write( $file_path, serialize($data) );
	}


	/**
	 * 
	 */
	public static function invalidate() : void
	{
		$dir_path = static::getDir();

		if( IO_Dir::exists($dir_path) ) {
			IO_Dir::remove($dir_path);
		}

	}
}