<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Mvc_Layout_PackageCreator_Abstract
 * @package Jet
 */
abstract class Mvc_Layout_PackageCreator extends BaseObject
{


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
		if( $URI[0]=='%' ) {
			$URI = str_replace( '_URI%', '_PATH%', $URI );

			return Data_Text::replaceSystemConstants( $URI );
		}


		$_URI = $this->normalizePath_Constants(
			$URI, [
				    'JET_PUBLIC',
			    ]
		);
		if( $_URI ) {
			return Data_Text::replaceSystemConstants( $_URI );
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

		foreach( $constants as $constant_name ) {
			$URI_constant_name = $constant_name.'_URI';
			$path_constant_name = $constant_name.'_PATH';

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
		if( $URI[0]=='%' ) {
			$URI = str_replace( '_PATH%', '_URI%', $URI );

			return Data_Text::replaceSystemConstants( $URI );
		}


		$_URI = $this->normalizeURI_Constants(
			$URI, [
				    'JET_PUBLIC',
			    ]
		);
		if( $_URI ) {
			return Data_Text::replaceSystemConstants( $_URI );
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
	protected function normalizeURI_Constants( $URI, $constants )
	{

		foreach( $constants as $constant_name ) {
			$URI_constant_name = $constant_name.'_URI';
			$path_constant_name = $constant_name.'_PATH';

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