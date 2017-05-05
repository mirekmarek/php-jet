<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Images;

/**
 *
 */
class Config
{

	/**
	 *
	 * @var int
	 */
	protected static $default_max_w = 800;

	/**
	 *
	 * @var int
	 */
	protected static $default_max_h = 600;

	/**
	 *
	 * @var int
	 */
	protected static $default_thb_max_w = 50;

	/**
	 * @var int
	 */
	protected static $default_thb_max_h = 50;

	/**
	 * @return int
	 */
	public static function getDefaultMaxH()
	{
		return static::$default_max_h;
	}

	/**
	 * @return int
	 */
	public static function getDefaultMaxW()
	{
		return static::$default_max_w;
	}


	/**
	 * @return int
	 */
	public static function getDefaultThbMaxH()
	{
		return static::$default_thb_max_h;
	}

	/**
	 * @return int
	 */
	public static function getDefaultThbMaxW()
	{
		return static::$default_thb_max_w;
	}


}