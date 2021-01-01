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
class SysConf_URI
{
	/**
	 * @var string
	 */
	protected static string $BASE = '';

	/**
	 * @var string
	 */
	protected static string $PUBLIC = '';



	/**
	 * @param string $what
	 *
	 * @throws SysConf_URI_Exception
	 */
	protected static function _check( string $what ) : void
	{
		if(!static::$$what) {
			throw new SysConf_URI_Exception('URI '.$what.' is not set');
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
	
}