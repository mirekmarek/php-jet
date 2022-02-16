<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class SysConf_URI
{
	protected static string $base;
	protected static string $css;
	protected static string $js;
	protected static string $images;



	/**
	 * @return string
	 */
	public static function getBase(): string
	{
		return static::$base;
	}

	/**
	 * @param string $base
	 */
	public static function setBase( string $base ): void
	{
		static::$base = $base;
	}

	/**
	 * @return string
	 */
	public static function getCss(): string
	{
		return static::$css;
	}

	/**
	 * @param string $css
	 */
	public static function setCss( string $css ): void
	{
		static::$css = $css;
	}


	/**
	 * @return string
	 */
	public static function getImages(): string
	{
		return static::$images;
	}

	/**
	 * @param string $images
	 */
	public static function setImages( string $images ): void
	{
		static::$images = $images;
	}


	/**
	 * @return string
	 */
	public static function getJs(): string
	{
		return static::$js;
	}

	/**
	 * @param string $js
	 */
	public static function setJs( string $js ): void
	{
		static::$js = $js;
	}


}