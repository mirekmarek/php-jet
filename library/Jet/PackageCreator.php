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
abstract class PackageCreator extends BaseObject
{

	/**
	 * @var string
	 */
	protected static $CSS_class_name = __NAMESPACE__.'\\PackageCreator_CSS_Default';

	/**
	 * @var string
	 */
	protected static $JavaScript_class_name = __NAMESPACE__.'\\PackageCreator_JavaScript_Default';

	/**
	 * @var Locale
	 */
	protected $locale;

	/**
	 * @var array
	 */
	protected $URIs = [];

	/**
	 * @return string
	 */
	public static function getCSSClassName()
	{
		return static::$CSS_class_name;
	}

	/**
	 * @param string $CSS_class_name
	 */
	public static function setCSSClassName( $CSS_class_name )
	{
		static::$CSS_class_name = $CSS_class_name;
	}


	/**
	 * @param string $media
	 * @param array  $URIs
	 *
	 * @return PackageCreator_CSS
	 */
	public static function CSS( $media, array $URIs )
	{
		$class_name = static::getCSSClassName();
		return new $class_name( $media, $URIs );
	}

	/**
	 * @return string
	 */
	public static function getJavaScriptClassName()
	{
		return static::$JavaScript_class_name;
	}

	/**
	 * @param string $JavaScript_class_name
	 */
	public static function setJavaScriptClassName( $JavaScript_class_name )
	{
		static::$JavaScript_class_name = $JavaScript_class_name;
	}


	/**
	 * @param array  $URIs
	 *
	 * @return PackageCreator_JavaScript
	 */
	public static function JavaScript( array $URIs )
	{
		$class_name = static::getJavaScriptClassName();
		return new $class_name( $URIs );
	}


	/**
	 * @param string $URI
	 *
	 * @return string|null
	 */
	protected function getFileContent( $URI )
	{

		$_URI = $this->normalizePath( $URI );

		$content = IO_File::read( $_URI );


		return $content;
	}

	/**
	 * @param string $URI
	 *
	 * @return string
	 */
	protected function normalizePath( $URI )
	{

		$o_URI = $URI;

		if(strpos($URI, '?')!==false) {
			$URI = strstr($URI, '?', true);
		}

		$public_uri_str_len = strlen(JET_URI_PUBLIC);
		if(substr($URI, 0, $public_uri_str_len)==JET_URI_PUBLIC) {
			return JET_PATH_PUBLIC.substr($URI, $public_uri_str_len);
		}

		if( substr( $o_URI, 0, 2 )=='//' ) {
			return 'http:'.$o_URI;
		}

		return $o_URI;
	}


	/**
	 * @param string $URI
	 *
	 * @return string
	 */
	protected function normalizeURI( $URI )
	{
		return $URI;
	}


}