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
class SysConf_URI
{
	/**
	 * @var 
	 */
	protected static $BASE;

	/**
	 * @var 
	 */
	protected static $PUBLIC;



	/**
	 * @param string $what
	 * @throws SysConf_URI_Exception
	 */
	protected static function _check( $what )
	{
		if(!static::$$what) {
			throw new SysConf_URI_Exception('URI '.$what.' is not set');
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
	
}