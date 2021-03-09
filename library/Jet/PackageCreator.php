<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
abstract class PackageCreator extends BaseObject
{

	/**
	 * @var string
	 */
	protected static string $CSS_class_name = PackageCreator_CSS_Default::class;

	/**
	 * @var string
	 */
	protected static string $JavaScript_class_name = PackageCreator_JavaScript_Default::class;

	/**
	 * @var Locale|null
	 */
	protected Locale|null $locale = null;

	/**
	 * @var array
	 */
	protected array $URIs = [];

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
	 * @param array $URIs
	 *
	 * @return PackageCreator_JavaScript
	 */
	public static function JavaScript( array $URIs ): PackageCreator_JavaScript
	{
		$class_name = static::getJavaScriptClassName();
		return new $class_name( $URIs );
	}


	/**
	 * @param string $URI
	 *
	 * @return string|null
	 */
	protected function getFileContent( string $URI ): string|null
	{

		$_URI = $this->normalizePath( $URI );

		return IO_File::read( $_URI );
	}

	/**
	 * @param string $URI
	 *
	 * @return string
	 */
	protected function normalizePath( string $URI ): string
	{

		$o_URI = $URI;

		if( str_contains( $URI, '?' ) ) {
			$URI = strstr( $URI, '?', true );
		}

		$public_uri_str_len = strlen( SysConf_URI::getCss() );
		if( substr( $URI, 0, $public_uri_str_len ) == SysConf_URI::getCss() ) {
			return SysConf_Path::getCss() . substr( $URI, $public_uri_str_len );
		}

		$public_uri_str_len = strlen( SysConf_URI::getJs() );
		if( substr( $URI, 0, $public_uri_str_len ) == SysConf_URI::getJs() ) {
			return SysConf_Path::getJs() . substr( $URI, $public_uri_str_len );
		}


		if( substr( $o_URI, 0, 2 ) == '//' ) {
			return 'http:' . $o_URI;
		}

		return $o_URI;
	}


	/**
	 * @param string $URI
	 *
	 * @return string
	 */
	protected function normalizeURI( string $URI ): string
	{
		return $URI;
	}


}