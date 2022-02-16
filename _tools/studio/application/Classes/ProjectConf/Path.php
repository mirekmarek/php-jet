<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

/**
 *
 */
class ProjectConf_Path
{
	/**
	 * @var string
	 */
	protected static string $root = '';
	/**
	 * @var string
	 */
	protected static string $bases = '';
	/**
	 * @var string
	 */
	protected static string $logs = '';
	/**
	 * @var string
	 */
	protected static string $tmp = '';
	/**
	 * @var string
	 */
	protected static string $cache = '';

	/**
	 * @var string
	 */
	protected static string $application = '';

	/**
	 * @var string
	 */
	protected static string $application_classes = '';

	/**
	 * @var string
	 */
	protected static string $application_modules = '';

	/**
	 * @var string
	 */
	protected static string $config = '';
	/**
	 * @var string
	 */
	protected static string $data = '';
	/**
	 * @var string
	 */
	protected static string $dictionaries = '';

	/**
	 * @var string
	 */
	protected static string $templates = '';

	/**
	 * @param string $what
	 * @throws ProjectConf_Path_Exception
	 */
	protected static function _check( string $what ): void
	{
		if( !static::$$what ) {
			throw new ProjectConf_Path_Exception( 'Path ' . $what . ' is not set' );
		}
	}

	/**
	 * @return string
	 */
	public static function getRoot(): string
	{
		static::_check( 'root' );
		return static::$root;
	}

	/**
	 * @param string $root
	 */
	public static function setRoot( string $root ): void
	{
		static::$root = $root;
	}

	/**
	 * @return string
	 */
	public static function getBases(): string
	{
		static::_check( 'bases' );
		return static::$bases;
	}

	/**
	 * @param string $bases
	 */
	public static function setBases( string $bases ): void
	{
		static::$bases = $bases;
	}

	/**
	 * @return string
	 */
	public static function getLogs(): string
	{
		static::_check( 'logs' );
		return static::$logs;
	}

	/**
	 * @param string $logs
	 */
	public static function setLogs( string $logs ): void
	{
		static::$logs = $logs;
	}

	/**
	 * @return string
	 */
	public static function getTmp(): string
	{
		static::_check( 'tmp' );
		return static::$tmp;
	}

	/**
	 * @param string $tmp
	 */
	public static function setTmp( string $tmp ): void
	{
		static::$tmp = $tmp;
	}

	/**
	 * @return string
	 */
	public static function getCache(): string
	{
		static::_check( 'cache' );
		return static::$cache;
	}

	/**
	 * @param string $cache
	 */
	public static function setCache( string $cache ): void
	{
		static::$cache = $cache;
	}

	/**
	 * @return string
	 */
	public static function getApplication(): string
	{
		static::_check( 'application' );
		return static::$application;
	}

	/**
	 * @param string $application
	 */
	public static function setApplication( string $application ): void
	{
		static::$application = $application;
	}

	/**
	 * @return string
	 */
	public static function getApplicationClasses(): string
	{
		static::_check( 'application_classes' );
		return static::$application_classes;
	}

	/**
	 * @param string $application_classes
	 */
	public static function setApplicationClasses( string $application_classes ): void
	{
		static::$application_classes = $application_classes;
	}


	/**
	 * @return string
	 */
	public static function getApplicationModules(): string
	{
		static::_check( 'application_modules' );
		return static::$application_modules;
	}

	/**
	 * @param string $application_modules
	 */
	public static function setApplicationModules( string $application_modules ): void
	{
		static::$application_modules = $application_modules;
	}

	/**
	 * @return string
	 */
	public static function getConfig(): string
	{
		static::_check( 'config' );
		return static::$config;
	}

	/**
	 * @param string $config
	 */
	public static function setConfig( string $config ): void
	{
		static::$config = $config;
	}

	/**
	 * @return string
	 */
	public static function getData(): string
	{
		static::_check( 'data' );
		return static::$data;
	}

	/**
	 * @param string $data
	 */
	public static function setData( string $data ): void
	{
		static::$data = $data;
	}

	/**
	 * @return string
	 */
	public static function getDictionaries(): string
	{
		static::_check( 'dictionaries' );
		return static::$dictionaries;
	}

	/**
	 * @param string $dictionaries
	 */
	public static function setDictionaries( string $dictionaries ): void
	{
		static::$dictionaries = $dictionaries;
	}

	/**
	 * @return string
	 */
	public static function getTemplates(): string
	{
		static::_check( 'templates' );
		return static::$templates;
	}

	/**
	 * @param string $templates
	 */
	public static function setTemplates( string $templates ): void
	{
		static::$templates = $templates;
	}
}