<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	protected static $BASE =    '';
	/**
	 * @var string
	 */
	protected static $SITES  =  '';
	/**
	 * @var string
	 */
	protected static $PUBLIC =  '';
	/**
	 * @var string
	 */
	protected static $LOGS =    '';
	/**
	 * @var string
	 */
	protected static $TMP =     '';
	/**
	 * @var string
	 */
	protected static $CACHE =   '';

	/**
	 * @var string
	 */
	protected static $APPLICATION =  '';
	
	/**
	 * @var string
	 */
	protected static $APPLICATION_CLASSES =  '';

	/**
	 * @var string
	 */
	protected static $APPLICATION_MODULES =  '';
	
	/**
	 * @var string
	 */
	protected static $CONFIG =       '';
	/**
	 * @var string
	 */
	protected static $DATA =         '';
	/**
	 * @var string
	 */
	protected static $DICTIONARIES = '';

	/**
	 * @var string 
	 */
	protected static $TEMPLATES = '';

	/**
	 * @param string $what
	 * @throws ProjectConf_PATH_Exception
	 */
	protected static function _check( $what )
	{
		if(!static::$$what) {
			throw new ProjectConf_PATH_Exception('PATH '.$what.' is not set');
		}
	}

	/**
	 * @return string
	 */
	public static function BASE()
	{
		static::_check('BASE');
		return static::$BASE;
	}

	/**
	 * @param string $BASE
	 */
	public static function setBASE( $BASE )
	{
		static::$BASE = $BASE;
	}

	/**
	 * @return string
	 */
	public static function SITES()
	{
		static::_check('SITES');
		return static::$SITES;
	}

	/**
	 * @param string $SITES
	 */
	public static function setSITES( $SITES )
	{
		static::$SITES = $SITES;
	}

	/**
	 * @return string
	 */
	public static function PUBLIC()
	{
		static::_check('PUBLIC');
		return static::$PUBLIC;
	}

	/**
	 * @param string $PUBLIC
	 */
	public static function setPUBLIC( $PUBLIC )
	{
		static::$PUBLIC = $PUBLIC;
	}

	/**
	 * @return string
	 */
	public static function LOGS()
	{
		static::_check('LOGS');
		return static::$LOGS;
	}

	/**
	 * @param string $LOGS
	 */
	public static function setLOGS( $LOGS )
	{
		static::$LOGS = $LOGS;
	}

	/**
	 * @return string
	 */
	public static function TMP()
	{
		static::_check('TMP');
		return static::$TMP;
	}

	/**
	 * @param string $TMP
	 */
	public static function setTMP( $TMP )
	{
		static::$TMP = $TMP;
	}

	/**
	 * @return string
	 */
	public static function CACHE()
	{
		static::_check('CACHE');
		return static::$CACHE;
	}

	/**
	 * @param string $CACHE
	 */
	public static function setCACHE( $CACHE )
	{
		static::$CACHE = $CACHE;
	}

	/**
	 * @return string
	 */
	public static function APPLICATION()
	{
		static::_check('APPLICATION');
		return static::$APPLICATION;
	}

	/**
	 * @param string $APPLICATION
	 */
	public static function setAPPLICATION( $APPLICATION )
	{
		static::$APPLICATION = $APPLICATION;
	}

	/**
	 * @return string
	 */
	public static function APPLICATION_CLASSES()
	{
		static::_check('APPLICATION_CLASSES');
		return static::$APPLICATION_CLASSES;
	}

	/**
	 * @param string $APPLICATION_CLASSES
	 */
	public static function setAPPLICATION_CLASSES( $APPLICATION_CLASSES )
	{
		static::$APPLICATION_CLASSES = $APPLICATION_CLASSES;
	}


	/**
	 * @return string
	 */
	public static function APPLICATION_MODULES()
	{
		static::_check('APPLICATION_MODULES');
		return static::$APPLICATION_MODULES;
	}

	/**
	 * @param string $APPLICATION_MODULES
	 */
	public static function setAPPLICATION_MODULES( $APPLICATION_MODULES )
	{
		static::$APPLICATION_MODULES = $APPLICATION_MODULES;
	}

	/**
	 * @return string
	 */
	public static function CONFIG()
	{
		static::_check('CONFIG');
		return static::$CONFIG;
	}

	/**
	 * @param string $CONFIG
	 */
	public static function setCONFIG( $CONFIG )
	{
		static::$CONFIG = $CONFIG;
	}

	/**
	 * @return string
	 */
	public static function DATA()
	{
		static::_check('DATA');
		return static::$DATA;
	}

	/**
	 * @param string $DATA
	 */
	public static function setDATA( $DATA )
	{
		static::$DATA = $DATA;
	}

	/**
	 * @return string
	 */
	public static function DICTIONARIES()
	{
		static::_check('DICTIONARIES');
		return static::$DICTIONARIES;
	}

	/**
	 * @param string $DICTIONARIES
	 */
	public static function setDICTIONARIES( $DICTIONARIES )
	{
		static::$DICTIONARIES = $DICTIONARIES;
	}

	/**
	 * @return string
	 */
	public static function TEMPLATES()
	{
		static::_check('TEMPLATES');
		return static::$TEMPLATES;
	}

	/**
	 * @param string $TEMPLATES
	 */
	public static function setTEMPLATES( $TEMPLATES )
	{
		static::$TEMPLATES = $TEMPLATES;
	}


}