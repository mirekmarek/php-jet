<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Content\Images;

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



}