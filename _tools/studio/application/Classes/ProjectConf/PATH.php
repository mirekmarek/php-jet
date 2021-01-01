<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

/**
 *
 */
class ProjectConf_PATH {
	/**
	 * @var string
	 */
	protected static string $BASE =    '';
	/**
	 * @var string
	 */
	protected static string $SITES  =  '';
	/**
	 * @var string
	 */
	protected static string $PUBLIC =  '';
	/**
	 * @var string
	 */
	protected static string $LOGS =    '';
	/**
	 * @var string
	 */
	protected static string $TMP =     '';
	/**
	 * @var string
	 */
	protected static string $CACHE =   '';

	/**
	 * @var string
	 */
	protected static string $APPLICATION =  '';
	
	/**
	 * @var string
	 */
	protected static string $APPLICATION_CLASSES =  '';

	/**
	 * @var string
	 */
	protected static string $APPLICATION_MODULES =  '';
	
	/**
	 * @var string
	 */
	protected static string $CONFIG =       '';
	/**
	 * @var string
	 */
	protected static string $DATA =         '';
	/**
	 * @var string
	 */
	protected static string $DICTIONARIES = '';

	/**
	 * @var string 
	 */
	protected static string $TEMPLATES = '';

	/**
	 * @param string $what
	 * @throws ProjectConf_PATH_Exception
	 */
	protected static function _check( string $what )
	{
		if(!static::$$what) {
			throw new ProjectConf_PATH_Exception('PATH '.$what.' is not set');
		}
	}

	/**
	 * @return string
	 */
	public static function BASE() : string
	{
		static::_check('BASE');
		return static::$BASE;
	}

	/**
	 * @param string $BASE
	 */
	public static function setBASE( string $BASE ) : void
	{
		static::$BASE = $BASE;
	}

	/**
	 * @return string
	 */
	public static function SITES() : string
	{
		static::_check('SITES');
		return static::$SITES;
	}

	/**
	 * @param string $SITES
	 */
	public static function setSITES( string $SITES ) : void
	{
		static::$SITES = $SITES;
	}

	/**
	 * @return string
	 */
	public static function PUBLIC() : string
	{
		static::_check('PUBLIC');
		return static::$PUBLIC;
	}

	/**
	 * @param string $PUBLIC
	 */
	public static function setPUBLIC( string $PUBLIC ) : void
	{
		static::$PUBLIC = $PUBLIC;
	}

	/**
	 * @return string
	 */
	public static function LOGS() : string
	{
		static::_check('LOGS');
		return static::$LOGS;
	}

	/**
	 * @param string $LOGS
	 */
	public static function setLOGS( string $LOGS ) : void
	{
		static::$LOGS = $LOGS;
	}

	/**
	 * @return string
	 */
	public static function TMP() : string
	{
		static::_check('TMP');
		return static::$TMP;
	}

	/**
	 * @param string $TMP
	 */
	public static function setTMP( string $TMP ) : void
	{
		static::$TMP = $TMP;
	}

	/**
	 * @return string
	 */
	public static function CACHE() : string
	{
		static::_check('CACHE');
		return static::$CACHE;
	}

	/**
	 * @param string $CACHE
	 */
	public static function setCACHE( string $CACHE ) : void
	{
		static::$CACHE = $CACHE;
	}

	/**
	 * @return string
	 */
	public static function APPLICATION() : string
	{
		static::_check('APPLICATION');
		return static::$APPLICATION;
	}

	/**
	 * @param string $APPLICATION
	 */
	public static function setAPPLICATION( string $APPLICATION ) : void
	{
		static::$APPLICATION = $APPLICATION;
	}

	/**
	 * @return string
	 */
	public static function APPLICATION_CLASSES() : string
	{
		static::_check('APPLICATION_CLASSES');
		return static::$APPLICATION_CLASSES;
	}

	/**
	 * @param string $APPLICATION_CLASSES
	 */
	public static function setAPPLICATION_CLASSES( string $APPLICATION_CLASSES ) : void
	{
		static::$APPLICATION_CLASSES = $APPLICATION_CLASSES;
	}


	/**
	 * @return string
	 */
	public static function APPLICATION_MODULES() : string
	{
		static::_check('APPLICATION_MODULES');
		return static::$APPLICATION_MODULES;
	}

	/**
	 * @param string $APPLICATION_MODULES
	 */
	public static function setAPPLICATION_MODULES( string $APPLICATION_MODULES ) : void
	{
		static::$APPLICATION_MODULES = $APPLICATION_MODULES;
	}

	/**
	 * @return string
	 */
	public static function CONFIG() : string
	{
		static::_check('CONFIG');
		return static::$CONFIG;
	}

	/**
	 * @param string $CONFIG
	 */
	public static function setCONFIG( string $CONFIG ) : void
	{
		static::$CONFIG = $CONFIG;
	}

	/**
	 * @return string
	 */
	public static function DATA() : string
	{
		static::_check('DATA');
		return static::$DATA;
	}

	/**
	 * @param string $DATA
	 */
	public static function setDATA( string $DATA ) : void
	{
		static::$DATA = $DATA;
	}

	/**
	 * @return string
	 */
	public static function DICTIONARIES() : string
	{
		static::_check('DICTIONARIES');
		return static::$DICTIONARIES;
	}

	/**
	 * @param string $DICTIONARIES
	 */
	public static function setDICTIONARIES( string $DICTIONARIES ) : void
	{
		static::$DICTIONARIES = $DICTIONARIES;
	}

	/**
	 * @return string
	 */
	public static function TEMPLATES() : string
	{
		static::_check('TEMPLATES');
		return static::$TEMPLATES;
	}

	/**
	 * @param string $TEMPLATES
	 */
	public static function setTEMPLATES( string $TEMPLATES ) : void
	{
		static::$TEMPLATES = $TEMPLATES;
	}


}