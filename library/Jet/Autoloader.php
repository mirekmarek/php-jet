<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

require_once 'Exception.php';
require_once 'Autoloader/Exception.php';
require_once 'Autoloader/Loader.php';

/**
 *
 */
class Autoloader
{
	/**
	 * @var bool
	 */
	protected static bool $cache_save_enabled = false;

	/**
	 * @var bool
	 */
	protected static bool $cache_load_enabled = false;

	/**
	 * @var callable
	 */
	protected static $cache_loader;

	/**
	 * @var callable
	 */
	protected static $cache_saver;

	/**
	 *
	 * @var bool
	 */
	protected static bool $is_initialized = false;

	/**
	 * @var Autoloader_Loader[]
	 */
	protected static array $loaders = [];

	/**
	 *
	 * @var array
	 */
	protected static array $classes_paths_map = [];

	/**
	 *
	 * @var bool
	 */
	protected static bool $classes_paths_map_updated = false;

	/**
	 * @return bool
	 */
	public static function getCacheSaveEnabled() : bool
	{
		return static::$cache_save_enabled && static::$cache_saver;
	}

	/**
	 * @return bool
	 */
	public static function getCacheLoadEnabled() : bool
	{
		return static::$cache_load_enabled && static::$cache_loader;
	}

	/**
	 * @param callable $cache_loader
	 */
	public static function enableCacheLoad( callable $cache_loader ) : void
	{
		static::$cache_load_enabled = true;
		static::$cache_loader = $cache_loader;
	}

	/**
	 * @param callable $cache_saver
	 */
	public static function enableCacheSave( callable $cache_saver ) : void
	{
		static::$cache_save_enabled = true;
		static::$cache_saver = $cache_saver;
	}


	/**
	 *
	 */
	public static function initialize() : void
	{

		if( static::$is_initialized ) {
			return;
		}

		if(
			static::getCacheLoadEnabled() &&
			static::$cache_loader
		) {
			$loader = static::$cache_loader;

			$data = $loader();

			if(is_array($data)) {
				static::$classes_paths_map = $data;
			}
		}

		$classes_paths_map_updated = &static::$classes_paths_map_updated;
		$classes_paths_map = &static::$classes_paths_map;
		$cache_save_enabled = &static::$cache_save_enabled;
		$cache_saver = &static::$cache_saver;


		register_shutdown_function(
			function() use (&$classes_paths_map_updated, &$classes_paths_map, &$cache_save_enabled, &$cache_saver) {

				if(
					$classes_paths_map_updated &&
					$cache_save_enabled &&
					$cache_saver
				) {
					$cache_saver($classes_paths_map);
				}
			}
		);


		static::$is_initialized = true;


		spl_autoload_register( [ __NAMESPACE__.'\Autoloader', 'load' ], true, true );

	}
	/**
	 *
	 * @return bool
	 */
	public static function getIsInitialized() : bool
	{
		return static::$is_initialized;
	}


	/**
	 *
	 * @param string $class_name
	 *
	 * @throws Autoloader_Exception
	 */
	public static function load( string $class_name ) : void
	{

		$path = false;

		$loader_name = '';

		$cache_hit = false;

		if( isset( static::$classes_paths_map[$class_name] ) ) {
			$path = static::$classes_paths_map[$class_name];
			$loader_name = 'CACHE';
			$cache_hit = true;
		} else {
			$root_namespace = strstr($class_name, '\\', true);
			$namespace = substr( $class_name, 0, strrpos($class_name, '\\') );
			$_class_name = substr( $class_name, strlen($namespace)+1 );

			foreach( static::$loaders as $loader_name => $loader ) {
				$path = $loader->getScriptPath( $root_namespace, $namespace, $_class_name );
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
				Autoloader_Exception::CODE_UNABLE_TO_DETERMINE_SCRIPT_PATH
			);
		}


		if( !file_exists( $path ) ) {
			throw new Autoloader_Exception(
				'File \''.$path.'\' does not exist. Class: \''.$class_name.'\', Loader: \''.$loader_name.'\'',
				Autoloader_Exception::CODE_SCRIPT_DOES_NOT_EXIST
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

		if( !$cache_hit ) {
			static::$classes_paths_map_updated = true;
			static::$classes_paths_map[$class_name] = $path;
		}

	}

	/**
	 * @param Autoloader_Loader $loader
	 */
	public static function register( Autoloader_Loader $loader ) : void
	{
		static::$loaders[get_class( $loader )] = $loader;
	}
}