<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
	public static function initialize(): void
	{

		if( static::$is_initialized ) {
			return;
		}

		static::$is_initialized = true;

		spl_autoload_register( [
			static::class,
			'load'
		], true, true );

	}

	/**
	 * @param string $class_name
	 * @param ?string $loader_name
	 *
	 * @return string|bool
	 */
	public static function getScriptPath( string $class_name, ?string &$loader_name='' ) : string|bool
	{
		$path = false;
		$root_namespace = strstr( $class_name, '\\', true );
		$namespace = substr( $class_name, 0, strrpos( $class_name, '\\' ) );
		$_class_name = substr( $class_name, strlen( $namespace ) + 1 );
		$loader_name = '';

		foreach( static::$loaders as $loader_name => $loader ) {
			$path = $loader->getScriptPath( $root_namespace, $namespace, $_class_name );
			if( $path ) {
				break;
			}
		}

		return $path;
	}

	/**
	 *
	 * @param string $class_name
	 *
	 * @throws Autoloader_Exception
	 */
	public static function load( string $class_name ): void
	{

		if( static::$class_path_map === null ) {

			$data = Autoloader_Cache::load();

			if( is_array( $data ) ) {
				static::$class_path_map = $data;
			} else {
				static::$class_path_map = [];
			}

		}


		if( isset( static::$class_path_map[$class_name] ) ) {
			$path = static::$class_path_map[$class_name];

			require_once $path;

			return;
		}


		$path = static::getScriptPath( $class_name, $loader_name );


		if( !$path ) {
			throw new Autoloader_Exception(
				'Unable to load class \'' . $class_name . '\'. Registered auto loaders: \''
				. implode( '\', \'', array_keys( static::$loaders ) )
				. '\'',
				Autoloader_Exception::CODE_UNABLE_TO_DETERMINE_SCRIPT_PATH
			);
		}

		if( !file_exists( $path ) ) {
			throw new Autoloader_Exception(
				'File \'' . $path . '\' does not exist. Class: \'' . $class_name . '\', Loader: \'' . $loader_name . '\'',
				Autoloader_Exception::CODE_SCRIPT_DOES_NOT_EXIST
			);

		}

		require_once $path;

		if(
			!class_exists( $class_name, false ) &&
			!interface_exists( $class_name, false ) &&
			!trait_exists( $class_name, false )
		) {
			throw new Autoloader_Exception(
				'Class \'' . $class_name . '\' does not exist in script: \'' . $path . '\', Loader: \'' . $loader_name . '\' ',
				Autoloader_Exception::CODE_INVALID_CLASS_DOES_NOT_EXIST
			);
		}

		static::$class_path_map[$class_name] = $path;

		if( !static::$save_class_map ) {
			register_shutdown_function(
				function() {
					Autoloader_Cache::save( static::$class_path_map );
				}
			);

			static::$save_class_map = true;
		}


	}

	/**
	 * @param Autoloader_Loader $loader
	 */
	public static function register( Autoloader_Loader $loader ): void
	{
		static::$loaders[get_class( $loader )] = $loader;
	}
}