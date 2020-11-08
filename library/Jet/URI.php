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
class URI
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
	 * @throws URI_Exception
	 */
	protected static function _check( $what )
	{
		if(!static::$$what) {
			throw new URI_Exception('PATH '.$what.' is not set');
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