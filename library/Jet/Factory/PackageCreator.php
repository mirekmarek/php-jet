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
class Factory_PackageCreator
{
	protected static string $CSS_class_name = PackageCreator_CSS_Default::class;
	protected static string $JavaScript_class_name = PackageCreator_JavaScript_Default::class;


	/**
	 * @return string
	 */
	public static function getCSSClassName() : string
	{
		return static::$CSS_class_name;
	}

	/**
	 * @param string $CSS_class_name
	 */
	public static function setCSSClassName( string $CSS_class_name ): void
	{
		static::$CSS_class_name = $CSS_class_name;
	}


	/**
	 * @return string
	 */
	public static function getJavaScriptClassName(): string
	{
		return static::$JavaScript_class_name;
	}

	/**
	 * @param string $JavaScript_class_name
	 */
	public static function setJavaScriptClassName( string $JavaScript_class_name ): void
	{
		static::$JavaScript_class_name = $JavaScript_class_name;
	}

	/**
	 * @param string $media
	 * @param array $URIs
	 *
	 * @return PackageCreator_CSS
	 */
	public static function CSS( string $media, array $URIs ): PackageCreator_CSS
	{
		$class_name = static::getCSSClassName();
		return new $class_name( $media, $URIs );
	}

	/**
	 * @param array $URIs
	 *
	 * @return PackageCreator_JavaScript
	 */
	public static function JavaScript( array $URIs ): PackageCreator_JavaScript
	{
		$class_name = static::getJavaScriptClassName();
		return new $class_name( $URIs );
	}

}