<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

require_once 'Exception.php';
require_once 'Autoloader/Exception.php';
require_once 'Autoloader/Loader.php';
require_once 'Autoloader/Cache.php';
require_once 'Autoloader/Cache/Backend.php';

/**
 *
 */
class Autoloader
{

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
	 * @var ?array
	 */
	protected static ?array $class_path_map = null;

	/**
	 *
	 * @var bool
	 */
	protected static bool $save_class_map = false;


	/**
	 *
	 */
	public static function initialize() : void
	{

		if( static::$is_initialized ) {
			return;
		}

		static::$is_initialized = true;

		spl_autoload_register( [ __NAMESPACE__.'\Autoloader', 'load' ], true, true );

	}

	/**
	 *
	 * @param string $class_name
	 *
	 * @throws Autoloader_Exception
	 */
	public static function load( string $class_name ) : void
	{

		if(static::$class_path_map===null) {

			$data = Autoloader_Cache::load();

			if(is_array($data)) {
				static::$class_path_map = $data;
			} else {
				static::$class_path_map = [];
			}

		}


		$path = false;

		$loader_name = '';

		$cache_hit = false;

		if( isset( static::$class_path_map[$class_name] ) ) {
			$path = static::$class_path_map[$class_name];
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
			if(!static::$save_class_map) {
				register_shutdown_function(
					function() {
						Autoloader_Cache::save( static::$class_path_map );
					}
				);

				static::$save_class_map = true;
			}
			static::$class_path_map[$class_name] = $path;
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