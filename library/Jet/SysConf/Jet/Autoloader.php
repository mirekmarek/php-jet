<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class SysConf_Jet_Autoloader
{

	protected static bool $cache_enabled = false;
	
	protected static string $application_autoloaders_dir_name = 'Autoloaders';
	
	protected static string $library_autoloader_file_name = 'JetAutoloader.php';
	
	
	/**
	 * @return bool
	 */
	public static function getCacheEnabled(): bool
	{
		return static::$cache_enabled;
	}

	/**
	 * @param bool $cache_enabled
	 */
	public static function setCacheEnabled( bool $cache_enabled ): void
	{
		static::$cache_enabled = $cache_enabled;
	}
	
	/**
	 * @return string
	 */
	public static function getApplicationAutoloadersDirName(): string
	{
		return static::$application_autoloaders_dir_name;
	}
	
	/**
	 * @param string $application_autoloaders_dir_name
	 */
	public static function setApplicationAutoloadersDirName( string $application_autoloaders_dir_name ): void
	{
		static::$application_autoloaders_dir_name = $application_autoloaders_dir_name;
	}
	
	/**
	 * @return string
	 */
	public static function getLibraryAutoloaderFileName(): string
	{
		return static::$library_autoloader_file_name;
	}
	
	/**
	 * @param string $library_autoloader_file_name
	 */
	public static function setLibraryAutoloaderFileName( string $library_autoloader_file_name ): void
	{
		static::$library_autoloader_file_name = $library_autoloader_file_name;
	}
	

}