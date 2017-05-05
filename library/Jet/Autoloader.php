<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/** @noinspection PhpIncludeInspection */
require_once JET_LIBRARY_PATH.'Jet/Exception.php';
/** @noinspection PhpIncludeInspection */
require_once JET_LIBRARY_PATH.'Jet/Autoloader/Exception.php';
/** @noinspection PhpIncludeInspection */
require_once JET_LIBRARY_PATH.'Jet/Autoloader/Loader/Abstract.php';

/**
 * Class Autoloader
 * @package Jet
 */
class Autoloader
{
	/**
	 *
	 * @var bool
	 */
	protected static $is_initialized = false;

	/**
	 * @var Autoloader_Loader_Abstract[]
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
	 * Initialize autoloader
	 */
	public static function initialize()
	{

		if( self::$is_initialized ) {
			return;
		}

		if( JET_AUTOLOADER_CACHE_LOAD ) {
			$file_path = JET_AUTOLOADER_CACHE_PATH.'autoloader_class_map.php';

			/** @noinspection PhpIncludeInspection */
			require_once JET_LIBRARY_PATH.'Jet/IO/File.php';

			if( IO_File::isReadable( $file_path ) ) {
				/** @noinspection PhpIncludeInspection */
				static::$classes_paths_map = require $file_path;
			}
		}

		if( JET_AUTOLOADER_CACHE_SAVE ) {

			register_shutdown_function(
				function() {

					if( Autoloader::getClassesPathsMapUpdated() ) {
						$file_path = JET_AUTOLOADER_CACHE_PATH.'autoloader_class_map.php';

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

		self::$is_initialized = true;


		spl_autoload_register( [ __NAMESPACE__.'\Autoloader', 'load' ], true, true );

	}

	/**
	 * @return array
	 */
	public static function getClassesPathsMapUpdated()
	{
		return self::$classes_paths_map_updated;
	}

	/**
	 * @return array
	 */
	public static function getClassesPathsMap()
	{
		return self::$classes_paths_map;
	}

	/**
	 *
	 * @return bool
	 */
	public static function getIsInitialized()
	{
		return self::$is_initialized;
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
				'Unable to load class \''.$class_name.'\'. Registered auto loaders: \''.implode(
					'\', \'', array_keys(
						        static::$loaders
					        )
				).'\'', Autoloader_Exception::CODE_INVALID_CLASS_DOES_NOT_EXIST
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

		if( !class_exists( $class_name, false )&&!interface_exists( $class_name, false )&&!trait_exists(
				$class_name, false
			)
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
	 * @param Autoloader_Loader_Abstract $loader
	 */
	public static function register( Autoloader_Loader_Abstract $loader )
	{
		static::$loaders[get_class( $loader )] = $loader;
	}
}