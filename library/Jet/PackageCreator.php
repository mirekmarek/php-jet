<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	 * @var array
	 */
	protected $omitted_URIs = [];

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
	 * @param Locale $locale
	 * @param array  $URIs
	 *
	 * @return PackageCreator_CSS
	 */
	public static function CSS( $media, Locale $locale, array $URIs )
	{
		$class_name = static::getCSSClassName();
		return new $class_name( $media, $locale, $URIs );
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
	 * @param Locale $locale
	 * @param array  $URIs
	 * @param array  $code
	 *
	 * @return PackageCreator_JavaScript
	 */
	public static function JavaScript( Locale $locale, array $URIs, array $code )
	{
		$class_name = static::getJavaScriptClassName();
		return new $class_name( $locale, $URIs, $code );
	}

	/**
	 * @return array
	 */
	public function getOmittedURIs()
	{
		return $this->omitted_URIs;
	}

	/**
	 * @param string $URI
	 *
	 * @return string|null
	 */
	protected function getFileContent( $URI )
	{

		$_URI = $this->normalizePath( $URI );

		try {
			$content = IO_File::read( $_URI );

		} catch( IO_File_Exception $e ) {
			$this->omitted_URIs[] = $URI;

			return null;
		}

		return $content;
	}

	/**
	 * @param string $URI
	 *
	 * @return string
	 */
	protected function normalizePath( $URI )
	{
		if(IO_File::exists(JET_PATH_PUBLIC.$URI)) {
			return JET_PATH_PUBLIC.$URI;
		}


		if( substr( $URI, 0, 2 )=='//' ) {
			return 'http:'.$URI;
		}

		return $URI;
	}

	/**
	 * @param string $URI
	 * @param array  $constants
	 *
	 * @return string
	 */
	protected function normalizePath_Constants( $URI, $constants )
	{

		foreach( $constants as $path_constant_name=>$URI_constant_name ) {

			$URI_constant_value = constant( $URI_constant_name );
			$path_constant_value = constant( $path_constant_name );

			$URI_constant_len = strlen( $URI_constant_value );

			if( substr( $URI, 0, $URI_constant_len )==$URI_constant_value ) {
				return $path_constant_value.substr( $URI, $URI_constant_len );
			}

		}

		return null;
	}


	/**
	 * @param string $URI
	 *
	 * @return string
	 */
	protected function normalizeURI( $URI )
	{
		if(IO_File::exists(JET_PATH_PUBLIC.$URI)) {
			return JET_URI_PUBLIC.$URI;
		}

		return $URI;
	}

	/**
	 * @param string $URI
	 * @param array  $constants
	 *
	 * @return string
	 */
	protected function normalizeURI_Constants( $URI, $constants )
	{

		foreach( $constants as $path_constant_name=>$URI_constant_name ) {

			$URI_constant_value = constant( $URI_constant_name );
			$path_constant_value = constant( $path_constant_name );

			$path_constant_len = strlen( $path_constant_value );

			if( substr( $URI, 0, $path_constant_len )==$path_constant_value ) {
				return $URI_constant_value.substr( $URI, $path_constant_len );
			}

		}

		return null;
	}

}