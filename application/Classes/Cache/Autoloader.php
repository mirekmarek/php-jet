<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

/**
 *
 */
class Cache_Autoloader {

	/**
	 * @return string
	 */
	public static function getPath()
	{
		return JET_PATH_CACHE.'autoloader_class_map.php';
	}

	/**
	 *
	 * @return bool|mixed
	 */
	public static function load()
	{
		$file_path = static::getPath();

		if(
			!is_file( $file_path ) ||
			!is_readable( $file_path )
		) {
			return false;
		}

		return require $file_path;
	}


	/**
	 * @param array $data
	 */
	public static function save( $data )
	{
		$file_path = static::getPath();

		file_put_contents(
			$file_path,
			'<?php return '.var_export( $data, true ).';'
		);
		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		@chmod( $file_path, JET_IO_CHMOD_MASK_FILE);

	}

	/**
	 *
	 */
	public static function invalidate()
	{
		$file_path = static::getPath();

		if(file_exists($file_path)) {
			unlink($file_path);
		}

	}
}