<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * 
 */
class SysConf_Path {
	/**
	 * @var string
	 */
	protected static string $base =    '';
	/**
	 * @var string
	 */
	protected static string $library = '';
	/**
	 * @var string
	 */
	protected static string $sites  =  '';
	/**
	 * @var string
	 */
	protected static string $menus  =  '';
	/**
	 * @var string
	 */
	protected static string $public =  '';
	/**
	 * @var string
	 */
	protected static string $logs =    '';
	/**
	 * @var string
	 */
	protected static string $tmp =     '';
	/**
	 * @var string
	 */
	protected static string $cache =   '';

	/**
	 * @var string
	 */
	protected static string $application =  '';
	/**
	 * @var string
	 */
	protected static string $config =       '';
	/**
	 * @var string
	 */
	protected static string $data =         '';
	/**
	 * @var string
	 */
	protected static string $dictionaries = '';

	/**
	 * @param string $what
	 * @throws SysConf_Path_Exception
	 */
	protected static function _check( string $what ) : void
	{
		if(!static::$$what) {
			throw new SysConf_Path_Exception('Path '.$what.' is not set');
		}
	}

	/**
	 * @return string
	 */
	public static function getBase() : string
	{
		static::_check('base');
		return static::$base;
	}

	/**
	 * @param string $base
	 */
	public static function setBase( string $base ) : void
	{
		static::$base = $base;
	}

	/**
	 * @return string
	 */
	public static function getLibrary() : string
	{
		static::_check('library');
		return static::$library;
	}

	/**
	 * @param string $library
	 */
	public static function setLibrary( string $library ) : void
	{
		static::$library = $library;
	}

	/**
	 * @return string
	 */
	public static function getSites() : string
	{
		static::_check('sites');
		return static::$sites;
	}

	/**
	 * @param string $sites
	 */
	public static function setSites( string $sites ) : void
	{
		static::$sites = $sites;
	}


	/**
	 * @return string
	 */
	public static function getMenus() : string
	{
		static::_check('menus');
		return static::$menus;
	}

	/**
	 * @param string $menus
	 */
	public static function setMenus( string $menus ) : void
	{
		static::$menus = $menus;
	}


	/**
	 * @return string
	 */
	public static function getPublic() : string
	{
		static::_check('public');
		return static::$public;
	}

	/**
	 * @param string $public
	 */
	public static function setPublic( string $public ) : void
	{
		static::$public = $public;
	}

	/**
	 * @return string
	 */
	public static function getLogs() : string
	{
		static::_check('logs');
		return static::$logs;
	}

	/**
	 * @param string $logs
	 */
	public static function setLogs( string $logs ) : void
	{
		static::$logs = $logs;
	}

	/**
	 * @return string
	 */
	public static function getTmp() : string
	{
		static::_check('tmp');
		return static::$tmp;
	}

	/**
	 * @param string $tmp
	 */
	public static function setTmp( string $tmp ) : void
	{
		static::$tmp = $tmp;
	}

	/**
	 * @return string
	 */
	public static function getCache() : string
	{
		static::_check('cache');
		return static::$cache;
	}

	/**
	 * @param string $cache
	 */
	public static function setCache( string $cache ) : void
	{
		static::$cache = $cache;
	}

	/**
	 * @return string
	 */
	public static function getApplication() : string
	{
		static::_check('application');
		return static::$application;
	}

	/**
	 * @param string $application
	 */
	public static function setApplication( string $application ) : void
	{
		static::$application = $application;
	}

	/**
	 * @return string
	 */
	public static function getConfig() : string
	{
		static::_check('config');
		return static::$config;
	}

	/**
	 * @param string $config
	 */
	public static function setConfig( string $config ) : void
	{
		static::$config = $config;
	}

	/**
	 * @return string
	 */
	public static function getData() : string
	{
		static::_check('data');
		return static::$data;
	}

	/**
	 * @param string $data
	 */
	public static function setData( string $data ) : void
	{
		static::$data = $data;
	}

	/**
	 * @return string
	 */
	public static function getDictionaries() : string
	{
		static::_check('dictionaries');
		return static::$dictionaries;
	}

	/**
	 * @param string $dictionaries
	 */
	public static function setDictionaries( string $dictionaries ) : void
	{
		static::$dictionaries = $dictionaries;
	}
}