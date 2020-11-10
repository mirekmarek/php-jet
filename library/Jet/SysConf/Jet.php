<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * 
 */
class SysConf_Jet {
	protected static $DEVEL_MODE = true;

	protected static $DEBUG_PROFILER_ENABLED = false;

	protected static $LAYOUT_CSS_PACKAGER_ENABLED = false;
	protected static $LAYOUT_JS_PACKAGER_ENABLED = false;

	protected static $TRANSLATOR_AUTO_APPEND_UNKNOWN_PHRASE = true;

	protected static $CACHE_REFLECTION_LOAD = false;
	protected static $CACHE_REFLECTION_SAVE = false;

	protected static $CACHE_DATAMODEL_DEFINITION_LOAD = false;
	protected static $CACHE_DATAMODEL_DEFINITION_SAVE = false;

	protected static $CACHE_CONFIG_DEFINITION_LOAD = false;
	protected static $CACHE_CONFIG_DEFINITION_SAVE = false;

	protected static $CACHE_AUTOLOADER_LOAD = false;
	protected static $CACHE_AUTOLOADER_SAVE = false;

	protected static $CACHE_MVC_SITE_LOAD = false;
	protected static $CACHE_MVC_SITE_SAVE = false;

	protected static $CACHE_MVC_PAGE_LOAD = false;
	protected static $CACHE_MVC_PAGE_SAVE = false;


	protected static $IO_CHMOD_MASK_DIR = 0777;
	protected static $IO_CHMOD_MASK_FILE = 0666;

	protected static $HIDE_HTTP_REQUEST = true;

	protected static $CHARSET = 'UTF-8';

	protected static $TIMEZONE = 'Europe/Prague';

	protected static $TAB = "\t";
	protected static $EOL = PHP_EOL;

	/**
	 * @return bool
	 */
	public static function DEVEL_MODE()
	{
		return self::$DEVEL_MODE;
	}

	/**
	 * @param bool $val
	 */
	public static function setDEVEL_MODE( $val )
	{
		self::$DEVEL_MODE = $val;
	}

	/**
	 * @return bool
	 */
	public static function DEBUG_PROFILER_ENABLED()
	{
		return self::$DEBUG_PROFILER_ENABLED;
	}

	/**
	 * @param bool $val
	 */
	public static function setDEBUG_PROFILER_ENABLED( $val )
	{
		self::$DEBUG_PROFILER_ENABLED = $val;
	}

	/**
	 * @return bool
	 */
	public static function LAYOUT_CSS_PACKAGER_ENABLED()
	{
		return self::$LAYOUT_CSS_PACKAGER_ENABLED;
	}

	/**
	 * @param bool $val
	 */
	public static function setLAYOUT_CSS_PACKAGER_ENABLED( $val )
	{
		self::$LAYOUT_CSS_PACKAGER_ENABLED = $val;
	}

	/**
	 * @return bool
	 */
	public static function LAYOUT_JS_PACKAGER_ENABLED()
	{
		return self::$LAYOUT_JS_PACKAGER_ENABLED;
	}

	/**
	 * @param bool $val
	 */
	public static function setLAYOUT_JS_PACKAGER_ENABLED( $val )
	{
		self::$LAYOUT_JS_PACKAGER_ENABLED = $val;
	}

	/**
	 * @return bool
	 */
	public static function TRANSLATOR_AUTO_APPEND_UNKNOWN_PHRASE()
	{
		return self::$TRANSLATOR_AUTO_APPEND_UNKNOWN_PHRASE;
	}

	/**
	 * @param bool $val
	 */
	public static function setTRANSLATOR_AUTO_APPEND_UNKNOWN_PHRASE( $val )
	{
		self::$TRANSLATOR_AUTO_APPEND_UNKNOWN_PHRASE = $val;
	}

	/**
	 * @param bool $val
	 */
	public static function setCACHE_LOAD( $val )
	{
		static::$CACHE_REFLECTION_LOAD = $val;
		static::$CACHE_DATAMODEL_DEFINITION_LOAD = $val;
		static::$CACHE_CONFIG_DEFINITION_LOAD = $val;
		static::$CACHE_AUTOLOADER_LOAD = $val;
		static::$CACHE_MVC_SITE_LOAD = $val;
		static::$CACHE_MVC_PAGE_LOAD = $val;

	}

	/**
	 * @param bool $val
	 */
	public static function setCACHE_SAVE( $val )
	{
		static::$CACHE_REFLECTION_SAVE = $val;
		static::$CACHE_DATAMODEL_DEFINITION_SAVE = $val;
		static::$CACHE_CONFIG_DEFINITION_SAVE = $val;
		static::$CACHE_AUTOLOADER_SAVE = $val;
		static::$CACHE_MVC_SITE_SAVE = $val;
		static::$CACHE_MVC_PAGE_SAVE = $val;
	}

	/**
	 * @return bool
	 */
	public static function CACHE_REFLECTION_LOAD()
	{
		return self::$CACHE_REFLECTION_LOAD;
	}

	/**
	 * @param bool $val
	 */
	public static function setCACHE_REFLECTION_LOAD( $val )
	{
		self::$CACHE_REFLECTION_LOAD = $val;
	}

	/**
	 * @return bool
	 */
	public static function CACHE_REFLECTION_SAVE()
	{
		return self::$CACHE_REFLECTION_SAVE;
	}

	/**
	 * @param bool $val
	 */
	public static function setCACHE_REFLECTION_SAVE( $val )
	{
		self::$CACHE_REFLECTION_SAVE = $val;
	}

	/**
	 * @return bool
	 */
	public static function CACHE_DATAMODEL_DEFINITION_LOAD()
	{
		return self::$CACHE_DATAMODEL_DEFINITION_LOAD;
	}

	/**
	 * @param bool $val
	 */
	public static function setCACHE_DATAMODEL_DEFINITION_LOAD( $val )
	{
		self::$CACHE_DATAMODEL_DEFINITION_LOAD = $val;
	}

	/**
	 * @return bool
	 */
	public static function CACHE_DATAMODEL_DEFINITION_SAVE()
	{
		return self::$CACHE_DATAMODEL_DEFINITION_SAVE;
	}

	/**
	 * @param bool $val
	 */
	public static function setCACHE_DATAMODEL_DEFINITION_SAVE( $val )
	{
		self::$CACHE_DATAMODEL_DEFINITION_SAVE = $val;
	}

	/**
	 * @return bool
	 */
	public static function CACHE_CONFIG_DEFINITION_LOAD()
	{
		return self::$CACHE_CONFIG_DEFINITION_LOAD;
	}

	/**
	 * @param bool $val
	 */
	public static function setCACHE_CONFIG_DEFINITION_LOAD( $val )
	{
		self::$CACHE_CONFIG_DEFINITION_LOAD = $val;
	}

	/**
	 * @return bool
	 */
	public static function CACHE_CONFIG_DEFINITION_SAVE()
	{
		return self::$CACHE_CONFIG_DEFINITION_SAVE;
	}

	/**
	 * @param bool $val
	 */
	public static function setCACHE_CONFIG_DEFINITION_SAVE( $val )
	{
		self::$CACHE_CONFIG_DEFINITION_SAVE = $val;
	}

	/**
	 * @return bool
	 */
	public static function CACHE_AUTOLOADER_LOAD()
	{
		return self::$CACHE_AUTOLOADER_LOAD;
	}

	/**
	 * @param bool $CACHE_AUTOLOADER_LOAD
	 */
	public static function setCACHE_AUTOLOADER_LOAD( $CACHE_AUTOLOADER_LOAD )
	{
		self::$CACHE_AUTOLOADER_LOAD = $CACHE_AUTOLOADER_LOAD;
	}

	/**
	 * @return bool
	 */
	public static function CACHE_AUTOLOADER_SAVE()
	{
		return self::$CACHE_AUTOLOADER_SAVE;
	}

	/**
	 * @param bool $val
	 */
	public static function setCACHE_AUTOLOADER_SAVE( $val )
	{
		self::$CACHE_AUTOLOADER_SAVE = $val;
	}

	/**
	 * @return bool
	 */
	public static function CACHE_MVC_SITE_LOAD()
	{
		return self::$CACHE_MVC_SITE_LOAD;
	}

	/**
	 * @param bool $val
	 */
	public static function setCACHE_MVC_SITE_LOAD( $val )
	{
		self::$CACHE_MVC_SITE_LOAD = $val;
	}

	/**
	 * @return bool
	 */
	public static function CACHE_MVC_SITE_SAVE()
	{
		return self::$CACHE_MVC_SITE_SAVE;
	}

	/**
	 * @param bool $val
	 */
	public static function setCACHE_MVC_SITE_SAVE( $val )
	{
		self::$CACHE_MVC_SITE_SAVE = $val;
	}

	/**
	 * @return bool
	 */
	public static function CACHE_MVC_PAGE_LOAD()
	{
		return self::$CACHE_MVC_PAGE_LOAD;
	}

	/**
	 * @param bool $val
	 */
	public static function setCACHE_MVC_PAGE_LOAD( $val )
	{
		self::$CACHE_MVC_PAGE_LOAD = $val;
	}

	/**
	 * @return bool
	 */
	public static function CACHE_MVC_PAGE_SAVE()
	{
		return self::$CACHE_MVC_PAGE_SAVE;
	}

	/**
	 * @param bool $val
	 */
	public static function setCACHE_MVC_PAGE_SAVE( $val )
	{
		self::$CACHE_MVC_PAGE_SAVE = $val;
	}

	/**
	 * @return int
	 */
	public static function IO_CHMOD_MASK_DIR()
	{
		return self::$IO_CHMOD_MASK_DIR;
	}

	/**
	 * @param int $val
	 */
	public static function setIO_CHMOD_MASK_DIR( $val )
	{
		self::$IO_CHMOD_MASK_DIR = $val;
	}

	/**
	 * @return int
	 */
	public static function IO_CHMOD_MASK_FILE()
	{
		return self::$IO_CHMOD_MASK_FILE;
	}

	/**
	 * @param int $val
	 */
	public static function setIO_CHMOD_MASK_FILE( $val )
	{
		self::$IO_CHMOD_MASK_FILE = $val;
	}

	/**
	 * @return bool
	 */
	public static function HIDE_HTTP_REQUEST()
	{
		return self::$HIDE_HTTP_REQUEST;
	}

	/**
	 * @param bool $val
	 */
	public static function setHIDE_HTTP_REQUEST( $val )
	{
		self::$HIDE_HTTP_REQUEST = $val;
	}

	/**
	 * @return string
	 */
	public static function CHARSET()
	{
		return self::$CHARSET;
	}

	/**
	 * @param string $val
	 */
	public static function setCHARSET( $val )
	{
		self::$CHARSET = $val;
	}

	/**
	 * @return string
	 */
	public static function TIMEZONE()
	{
		return self::$TIMEZONE;
	}

	/**
	 * @param string $TIMEZONE
	 */
	public static function setTIMEZONE( $TIMEZONE )
	{
		self::$TIMEZONE = $TIMEZONE;
	}

	/**
	 * @return string
	 */
	public static function TAB()
	{
		return self::$TAB;
	}

	/**
	 * @param string $val
	 */
	public static function setTAB( string $val )
	{
		self::$TAB = $val;
	}

	/**
	 * @return string
	 */
	public static function EOL()
	{
		return self::$EOL;
	}

	/**
	 * @param string $val
	 */
	public static function setEOL( $val )
	{
		self::$EOL = $val;
	}


}