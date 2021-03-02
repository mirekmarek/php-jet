<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Content\Images;

/**
 *
 */
class Config
{

	/**
	 *
	 * @var int
	 */
	protected static int $default_max_w = 800;

	/**
	 *
	 * @var int
	 */
	protected static int $default_max_h = 600;

	/**
	 * @return int
	 */
	public static function getDefaultMaxH(): int
	{
		return static::$default_max_h;
	}

	/**
	 * @return int
	 */
	public static function getDefaultMaxW(): int
	{
		return static::$default_max_w;
	}


}