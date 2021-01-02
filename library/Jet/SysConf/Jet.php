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
class SysConf_Jet {
	protected static bool $DEVEL_MODE = false;

	protected static bool $DEBUG_PROFILER_ENABLED = false;

	protected static bool $LAYOUT_CSS_PACKAGER_ENABLED = true;
	protected static bool $LAYOUT_JS_PACKAGER_ENABLED = true;

	protected static bool $TRANSLATOR_AUTO_APPEND_UNKNOWN_PHRASE = true;

	protected static int $IO_CHMOD_MASK_DIR = 0777;
	protected static int $IO_CHMOD_MASK_FILE = 0666;

	protected static bool $HIDE_HTTP_REQUEST = true;

	protected static string $CHARSET = 'UTF-8';

	protected static string $TIMEZONE = 'Europe/Prague';

	/**
	 * @return bool
	 */
	public static function DEVEL_MODE() : bool
	{
		return self::$DEVEL_MODE;
	}

	/**
	 * @param bool $val
	 */
	public static function setDEVEL_MODE( bool $val ) : void
	{
		self::$DEVEL_MODE = $val;
	}

	/**
	 * @return bool
	 */
	public static function DEBUG_PROFILER_ENABLED() : bool
	{
		return self::$DEBUG_PROFILER_ENABLED;
	}

	/**
	 * @param bool $val
	 */
	public static function setDEBUG_PROFILER_ENABLED( bool $val ) : void
	{
		self::$DEBUG_PROFILER_ENABLED = $val;
	}

	/**
	 * @return bool
	 */
	public static function LAYOUT_CSS_PACKAGER_ENABLED() : bool
	{
		return self::$LAYOUT_CSS_PACKAGER_ENABLED;
	}

	/**
	 * @param bool $val
	 */
	public static function setLAYOUT_CSS_PACKAGER_ENABLED( bool $val ) : void
	{
		self::$LAYOUT_CSS_PACKAGER_ENABLED = $val;
	}

	/**
	 * @return bool
	 */
	public static function LAYOUT_JS_PACKAGER_ENABLED() : bool
	{
		return self::$LAYOUT_JS_PACKAGER_ENABLED;
	}

	/**
	 * @param bool $val
	 */
	public static function setLAYOUT_JS_PACKAGER_ENABLED( bool $val ) : void
	{
		self::$LAYOUT_JS_PACKAGER_ENABLED = $val;
	}

	/**
	 * @return bool
	 */
	public static function TRANSLATOR_AUTO_APPEND_UNKNOWN_PHRASE() : bool
	{
		return self::$TRANSLATOR_AUTO_APPEND_UNKNOWN_PHRASE;
	}

	/**
	 * @param bool $val
	 */
	public static function setTRANSLATOR_AUTO_APPEND_UNKNOWN_PHRASE( bool $val ) : void
	{
		self::$TRANSLATOR_AUTO_APPEND_UNKNOWN_PHRASE = $val;
	}

	/**
	 * @return int
	 */
	public static function IO_CHMOD_MASK_DIR() : int
	{
		return self::$IO_CHMOD_MASK_DIR;
	}

	/**
	 * @param int $val
	 */
	public static function setIO_CHMOD_MASK_DIR( int $val ) : void
	{
		self::$IO_CHMOD_MASK_DIR = $val;
	}

	/**
	 * @return int
	 */
	public static function IO_CHMOD_MASK_FILE() : int
	{
		return self::$IO_CHMOD_MASK_FILE;
	}

	/**
	 * @param int $val
	 */
	public static function setIO_CHMOD_MASK_FILE( int $val ) : void
	{
		self::$IO_CHMOD_MASK_FILE = $val;
	}

	/**
	 * @return bool
	 */
	public static function HIDE_HTTP_REQUEST() : bool
	{
		return self::$HIDE_HTTP_REQUEST;
	}

	/**
	 * @param bool $val
	 */
	public static function setHIDE_HTTP_REQUEST( bool $val ) : void
	{
		self::$HIDE_HTTP_REQUEST = $val;
	}

	/**
	 * @return string
	 */
	public static function CHARSET() : string
	{
		return self::$CHARSET;
	}

	/**
	 * @param string $val
	 */
	public static function setCHARSET( string $val ) : void
	{
		self::$CHARSET = $val;
	}

	/**
	 * @return string
	 */
	public static function TIMEZONE() : string
	{
		return self::$TIMEZONE;
	}

	/**
	 * @param string $TIMEZONE
	 */
	public static function setTIMEZONE( string $TIMEZONE ) : void
	{
		self::$TIMEZONE = $TIMEZONE;
	}

}