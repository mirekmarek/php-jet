<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

require_once SysConf_PATH::LIBRARY().'Jet/Cache.php';
require_once SysConf_PATH::LIBRARY().'Jet/Autoloader/Cache.php';
require_once SysConf_PATH::LIBRARY().'Jet/Autoloader/Cache/Backend.php';


/**
 *
 */
class Autoloader_Cache_Backend_Files implements Autoloader_Cache_Backend {
	/**
	 * @return string
	 */
	public function getPath() : string
	{
		return SysConf_PATH::CACHE().'autoloader_class_map.php';
	}

	/**
	 * @return bool
	 */
	public function isActive() : bool
	{
		return SysConf_Cache::isAutoloaderEnabled();
	}
	

	/**
	 *
	 * @return array|null
	 */
	public function load() : array|null
	{
		if(!SysConf_Cache::isAutoloaderEnabled()) {
			return null;
		}

		$file_path = $this->getPath();

		if(
			!is_file( $file_path ) ||
			!is_readable( $file_path )
		) {
			return null;
		}

		return require $file_path;
	}


	/**
	 * @param array $map
	 */
	public function save( array $map ) : void
	{
		if(!SysConf_Cache::isAutoloaderEnabled()) {
			return;
		}

		$file_path = $this->getPath();

		file_put_contents(
			$file_path,
			'<?php return '.var_export( $map, true ).';'
		);
		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		@chmod( $file_path, SysConf_Jet::IO_CHMOD_MASK_FILE());

		Cache::resetOPCache();
	}

	/**
	 *
	 */
	public function reset() : void
	{
		$file_path = $this->getPath();

		if(file_exists($file_path)) {
			unlink($file_path);
		}

		Cache::resetOPCache();

	}
}
