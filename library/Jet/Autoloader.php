<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/** @noinspection PhpIncludeInspection */
require_once JET_PATH_LIBRARY.'Jet/Exception.php';
/** @noinspection PhpIncludeInspection */
require_once JET_PATH_LIBRARY.'Jet/Autoloader/Exception.php';
/** @noinspection PhpIncludeInspection */
require_once JET_PATH_LIBRARY.'Jet/Autoloader/Loader.php';

/**
 *
 */
class Autoloader
{
	/**
	 * @var string
	 */
	protected static $cache_dir_path = JET_PATH_DATA;

	/**
	 * @var string
	 */
	protected static $cache_file_name = 'autoloader_class_map.php';

	/**
	 * @var bool
	 */
	protected static $cache_save_enabled;

	/**
	 * @var bool
	 */
	protected static $cache_load_enabled;

	/**
	 *
	 * @var bool
	 */
	protected static $is_initialized = false;

	/**
	 * @var Autoloader_Loader[]
	 */
	protected static $loaders = [];

	/**
	 *
	 * @var array
	 */
	protected static $classes_paths_map = [];

	/**
	 *
	 * @var array
	 */
	protected static $classes_paths_map_updated = false;

	/**
	 * @return string
	 */
	public static function getCacheDirPath()
	{
		return static::$cache_dir_path;
	}

	/**
	 * @param string $cache_dir_path
	 */
	public static function setCacheDirPath( $cache_dir_path )
	{
		static::$cache_dir_path = $cache_dir_path;
	}

	/**
	 * @return string
	 */
	public static function getCacheFileName()
	{
		return static::$cache_file_name;
	}

	/**
	 * @param string $cache_file_name
	 */
	public static function setCacheFileName( $cache_file_name )
	{
		static::$cache_file_name = $cache_file_name;
	}

	/**
	 * @return bool
	 */
	public static function getCacheSaveEnabled()
	{
		if(static::$cache_save_enabled===null) {
			if(defined('JET_AUTOLOADER_CACHE_SAVE')) {
				static::$cache_save_enabled = JET_AUTOLOADER_CACHE_SAVE;
			} else {
				static::$cache_save_enabled = false;
			}
		}

		return static::$cache_save_enabled;
	}

	/**
	 * @param bool $cache_save_enabled
	 */
	public static function setCacheSaveEnabled( $cache_save_enabled )
	{
		static::$cache_save_enabled = $cache_save_enabled;
	}

	/**
	 * @return bool
	 */
	public static function getCacheLoadEnabled()
	{
		if(static::$cache_load_enabled===null) {
			if(defined('JET_AUTOLOADER_CACHE_LOAD')) {
				static::$cache_load_enabled = JET_AUTOLOADER_CACHE_LOAD;
			} else {
				static::$cache_load_enabled = false;
			}
		}

		return static::$cache_load_enabled;
	}

	/**
	 * @param bool $cache_load_enabled
	 */
	public static function setCacheLoadEnabled( $cache_load_enabled )
	{
		static::$cache_load_enabled = $cache_load_enabled;
	}


	/**
	 * Initialize autoloader
	 */
	public static function initialize()
	{

		if( static::$is_initialized ) {
			return;
		}

		if( static::getCacheLoadEnabled() ) {
			$file_path = static::$cache_dir_path.static::$cache_file_name;

			/** @noinspection PhpIncludeInspection */
			require_once JET_PATH_LIBRARY.'Jet/IO/File.php';

			if( IO_File::isReadable( $file_path ) ) {
				/** @noinspection PhpIncludeInspection */
				static::$classes_paths_map = require $file_path;
			}
		}

		if( static::getCacheSaveEnabled() ) {

			register_shutdown_function(
				function() {

					if( Autoloader::getClassesPathsMapUpdated() ) {
						$file_path = static::$cache_dir_path.static::$cache_file_name;

						try {
							IO_File::write(
								$file_path, '<?php return '.var_export( Autoloader::getClassesPathsMap(), true ).';'
							);
						} catch( Exception $e ) {
						}
					}

				}
			);
		}

		static::$is_initialized = true;


		spl_autoload_register( [ __NAMESPACE__.'\Autoloader', 'load' ], true, true );

	}
	/**
	 *
	 * @return bool
	 */
	public static function getIsInitialized()
	{
		return static::$is_initialized;
	}


	/**
	 * @return array
	 */
	public static function getClassesPathsMapUpdated()
	{
		return static::$classes_paths_map_updated;
	}

	/**
	 * @return array
	 */
	public static function getClassesPathsMap()
	{
		return static::$classes_paths_map;
	}

	/**
	 *
	 * @param string $class_name
	 *
	 * @throws Autoloader_Exception
	 */
	public static function load( $class_name )
	{

		$path = false;

		$loader_name = '';

		$map_hit = false;

		if( isset( static::$classes_paths_map[$class_name] ) ) {
			$path = static::$classes_paths_map[$class_name];
			$loader_name = '__classes_paths_map__';
			$map_hit = true;
		} else {
			foreach( static::$loaders as $loader_name => $loader ) {
				$path = $loader->getClassPath( $class_name );
				if( $path ) {
					break;
				}
			}
		}

		if( !$path ) {
			throw new Autoloader_Exception(
				'Unable to load class \''.$class_name.'\'. Registered auto loaders: \''
				. implode( '\', \'', array_keys( static::$loaders ) )
				.'\'',
				Autoloader_Exception::CODE_INVALID_CLASS_DOES_NOT_EXIST
			);
		}

		if( !file_exists( $path ) ) {
			throw new Autoloader_Exception(
				'File \''.$path.'\' does not exist. Class: \''.$class_name.'\', Loader: \''.$loader_name.'\'',
				Autoloader_Exception::CODE_INVALID_CLASS_DOES_NOT_EXIST
			);

		}

		/** @noinspection PhpIncludeInspection */
		require_once $path;

		if(
			!class_exists( $class_name, false ) &&
			!interface_exists( $class_name, false ) &&
			!trait_exists( $class_name, false )
		) {
			throw new Autoloader_Exception(
				'Class \''.$class_name.'\' does not exist in script: \''.$path.'\', Loader: \''.$loader_name.'\' ',
				Autoloader_Exception::CODE_INVALID_CLASS_DOES_NOT_EXIST
			);
		}

		if( !$map_hit ) {
			static::$classes_paths_map_updated = true;
			static::$classes_paths_map[$class_name] = $path;
		}

	}

	/**
	 * @param Autoloader_Loader $loader
	 */
	public static function register( Autoloader_Loader $loader )
	{
		static::$loaders[get_class( $loader )] = $loader;
	}
}