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
class PackageCreator_CSS_Default extends PackageCreator_CSS
{
	/**
	 * @var string
	 */
	protected string $media = '';

	/**
	 *
	 * @param string $media
	 * @param array $URIs
	 */
	public function __construct( string $media, array $URIs )
	{
		$this->media = $media;
		$this->URIs = $URIs;
	}


	/**
	 *
	 * @return string
	 */
	protected function createPackage(): string
	{
		$CSS = '@charset "utf-8";' . PHP_EOL . PHP_EOL;

		foreach( $this->URIs as $URI ) {

			$CSS_file_data = $this->getFileContent( $URI );

			$CSS_file_data = $this->changeUrls( $CSS_file_data, $URI );

			$CSS .= '/* URI: ' . $URI . ' */' . PHP_EOL;
			$CSS .= $CSS_file_data . PHP_EOL;
			$CSS .= '/* ------------------------ */ ' . PHP_EOL;
		}

		return $CSS;
	}

	/**
	 * @param string $CSS_file_data
	 * @param string $URI
	 *
	 * @return string
	 */
	protected function changeUrls( string $CSS_file_data, string $URI ): string
	{
		$base_URI = dirname( $this->normalizeURI( $URI ) ) . '/';


		$res = [];
		if( preg_match_all( '/url\(([^)]*)\)/', $CSS_file_data, $res, PREG_SET_ORDER ) ) {
			foreach( $res as $r ) {
				$orig_str = $r[0];
				$path = trim( $r[1] );

				if(
					$path[0] == '"' ||
					$path[0] == "'"
				) {
					$path = substr( $path, 1, -1 );
				}


				if( $path[0] == '.' ) {

					$_base_URI = $base_URI;

					$path = explode( '/', $path );

					while( $path[0] == '..' ) {
						array_shift( $path );
						$_base_URI = dirname( $_base_URI );
					}

					if( $_base_URI == '/' ) {
						$_base_URI = '';
					}

					$URL = $_base_URI . '/' . implode( '/', $path );

				} else {
					if( $path[0] == '/' ) {
						$URL = $path;
					} else {
						$URL = $base_URI . $path;
					}
				}

				$CSS_file_data = str_replace( $orig_str, 'url("' . $URL . '")', $CSS_file_data );

			}

		}

		return $CSS_file_data;
	}

	/**
	 * @return string
	 */
	protected function getPackageRelativeFileName(): string
	{
		if($this->media) {
			return SysConf_Jet_PackageCreator_CSS::getPackagesDirName() . '/' . $this->media . '_' .$this->getKey() . '.css';
		}
		return SysConf_Jet_PackageCreator_CSS::getPackagesDirName() . '/' . $this->getKey() . '.css';
	}


	/**
	 * @return string
	 */
	public function getPackagePath(): string
	{
		return SysConf_Path::getCss() . $this->getPackageRelativeFileName();
	}

	/**
	 * @return string
	 */
	public function getPackageURI(): string
	{
		return SysConf_URI::getCss() . $this->getPackageRelativeFileName();
	}

}