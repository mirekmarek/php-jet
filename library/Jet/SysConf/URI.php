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
	protected static string $base = '';

	/**
	 * @var string
	 */
	protected static string $public = '';



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
	
}