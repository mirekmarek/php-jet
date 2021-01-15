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
	protected static string $css = '';

	/**
	 * @var string
	 */
	protected static string $js = '';

	/**
	 * @var string
	 */
	protected static string $images = '';


	/**
	 * @param string $what
	 *
	 * @throws SysConf_URI_Exception
	 */
	protected static function _check( string $what ): void
	{
		if( !static::$$what ) {
			throw new SysConf_URI_Exception( 'URI ' . $what . ' is not set' );
		}
	}

	/**
	 * @return string
	 */
	public static function getBase(): string
	{
		static::_check( 'base' );
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
		static::_check( 'css' );

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
		static::_check( 'images' );

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
		static::_check( 'js' );

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