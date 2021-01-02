<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Autoloader_Cache;
use Jet\Autoloader_Cache_Backend;
use Jet\SysConf_Cache;
use Jet\SysConf_Jet;
use Jet\SysConf_PATH;


if(SysConf_Cache::isAUTOLOADER_ENABLED()) {
	Autoloader_Cache::init(
		new class implements Autoloader_Cache_Backend {
			/**
			 * @return string
			 */
			public static function getPath() : string
			{
				return SysConf_PATH::CACHE().'autoloader_class_map.php';
			}

			/**
			 *
			 * @return array|null
			 */
			public function load() : array|null
			{
				$file_path = static::getPath();

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
				$file_path = static::getPath();

				file_put_contents(
					$file_path,
					'<?php return '.var_export( $map, true ).';'
				);
				/** @noinspection PhpUsageOfSilenceOperatorInspection */
				@chmod( $file_path, SysConf_Jet::IO_CHMOD_MASK_FILE());
				if(function_exists('opcache_reset')) {
					opcache_reset();
				}

			}

			/**
			 *
			 */
			public function invalidate() : void
			{
				$file_path = static::getPath();

				if(file_exists($file_path)) {
					unlink($file_path);
				}
				if(function_exists('opcache_reset')) {
					opcache_reset();
				}


			}
		}
	);
}
