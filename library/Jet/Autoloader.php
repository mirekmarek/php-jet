<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Autoloader
 */
namespace Jet;

require_once 'Exception.php';
require_once 'Autoloader/Exception.php';
require_once 'Autoloader/Loader/Abstract.php';

class Autoloader {
	/**
	 *
	 * @var bool
	 */
	protected static $is_initialized = false;

	/**
	 * @var Autoloader_Loader_Abstract[]
	 */
	protected static $loaders = array();

	/**
	 *
	 * @var array
	 */
	protected static $classes_paths_map = array();

	
	/**
	 * Initialize autoloader
	 */
	public static function initialize(){

		if(self::$is_initialized){
			return;
		}

		self::$is_initialized = true;

		spl_autoload_register( array('\Jet\\Autoloader', 'load'), true, true );

	}

	/**
	 *
	 * @return bool
	 */
	public static function getIsInitialized(){
		return self::$is_initialized;
	}


	/**
	 *
	 * @param string $class_name
	 *
	 * @throws Autoloader_Exception
	 */
	public static function load($class_name){

		$path = false;

		$loader_name = '';
		if(isset(static::$classes_paths_map[$class_name])) {
			$path = static::$classes_paths_map[$class_name];
		} else {
			foreach(static::$loaders as $loader_name => $loader) {
				$path = $loader->getClassPath($class_name);
				if($path) {
					break;
				}
			}

			if($path) {
				static::$classes_paths_map[$class_name] = $path;
			}
		}

		if(!$path) {
			throw new Autoloader_Exception(
				'Unable to load class \''.$class_name.'\'. Registered auto loaders: \''.implode('', '', array_keys(static::$loaders)).'\'',
				Autoloader_Exception::CODE_INVALID_CLASS_DOES_NOT_EXIST
			);
		}

		if(!file_exists($path)) {
			throw new Autoloader_Exception(
				'File \''.$path.'\' does not exist. Class: \''.$class_name.'\', Loader: \''.$loader_name.'\'',
				Autoloader_Exception::CODE_INVALID_CLASS_DOES_NOT_EXIST
			);

		}

		/** @noinspection PhpIncludeInspection */
		require_once $path;

		if(
			!class_exists($class_name, false) &&
			!interface_exists($class_name, false) &&
			!trait_exists($class_name, false)
		) {
			throw new Autoloader_Exception(
				'Class \''.$class_name.'\' does not exist in script: \''.$path.'\', Loader: \''.$loader_name.'\' ',
				Autoloader_Exception::CODE_INVALID_CLASS_DOES_NOT_EXIST
			);
		}
	}


	/**
	 * @static
	 *
	 * @param string $loader_class_name
	 * @param string $loader_script_path
	 *
	 * @throws Autoloader_Exception
	 *
	 * @return Autoloader_Loader_Abstract
	 */
	public static function registerLoader($loader_class_name, $loader_script_path){
		/** @noinspection PhpIncludeInspection */
		require_once $loader_script_path;


		if(
			!class_exists($loader_class_name, false)
		) {
			throw new Autoloader_Exception(
				'Autoloader class \''.$loader_class_name.'\' does not exist. Should be in script: \''.$loader_script_path.'\' ',
				Autoloader_Exception::CODE_INVALID_AUTOLOADER_CLASS_DOES_NOT_EXIST
			);

		}

		$loader = new $loader_class_name();

		if( ! ($loader instanceof Autoloader_Loader_Abstract) ){
			throw new Autoloader_Exception(
				'Autoloader class \''.$loader_class_name.'\' must extend Jet\\Autoloader_Loader_Abstract class.',
				Autoloader_Exception::CODE_INVALID_AUTOLOADER_CLASS
			);
		}

		static::$loaders[$loader_class_name] = $loader;

		return $loader;
	}
}