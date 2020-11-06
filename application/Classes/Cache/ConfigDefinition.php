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

/**
 *
 */
class Cache_ConfigDefinition {

	/**
	 * @return string
	 */
	public static function getDir()
	{
		return JET_PATH_CACHE.'config_definitions/';
	}

	/**
	 * @param string $class
	 *
	 * @return string
	 */
	public static function getPath( $class )
	{
		return static::getDir().str_replace( '\\', '__', $class.'.php' );
	}

	/**
	 * @param string $class
	 *
	 * @return bool|mixed
	 */
	public static function load( $class )
	{
		$file_path = static::getPath( $class );

		if(
			!IO_File::isReadable($file_path)
		) {
			return false;
		}

		return require $file_path;
	}


	/**
	 * @param string $class
	 * @param array  $data
	 */
	public static function save( $class, $data )
	{
		$file_path = static::getPath( $class );

		IO_File::write(
			$file_path,
			'<?php return '.var_export( $data, true ).';'
		);
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