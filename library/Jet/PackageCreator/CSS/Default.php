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
class PackageCreator_CSS_Default extends PackageCreator_CSS
{
	/**
	 * @var string
	 */
	protected $media;

	/**
	 * @var string
	 */
	protected $key = null;

	/**
	 *
	 * @param string $media
	 * @param array  $URIs
	 */
	public function __construct( $media, array $URIs )
	{

		$this->media = $media;
		$this->URIs = $URIs;
	}

	/**
	 *
	 */
	public function generate()
	{

		$package_path = $this->getPackagePath();

		if(
			!IO_File::exists( $package_path )
		) {

			IO_File::write(
				$package_path, $this->createPackage()
			);

		}

	}


	/**
	 *
	 * @return string
	 */
	public function createPackage()
	{
		$CSS = '@charset "utf-8";'.SysConf_Jet::EOL().SysConf_Jet::EOL();

		foreach( $this->URIs as $URI ) {

			$CSS_file_data = $this->getFileContent( $URI );

			$CSS_file_data = $this->changeUrls( $CSS_file_data, $URI );

			$CSS .= '/* URI: '.$URI.' */'.SysConf_Jet::EOL();
			$CSS .= $CSS_file_data.SysConf_Jet::EOL();
			$CSS .= '/* ------------------------ */ '.SysConf_Jet::EOL();
		}

		return $CSS;
	}

	/**
	 * @param string $CSS_file_data
	 * @param string $URI
	 *
	 * @return string
	 */
	public function changeUrls( $CSS_file_data, $URI )
	{
		$base_URI = dirname( $this->normalizeURI( $URI ) ).'/';


		$res = [];
		if( preg_match_all( '/url\(([^)]*)\)/', $CSS_file_data, $res, PREG_SET_ORDER ) ) {
			foreach( $res as $r ) {
				$orig_str = $r[0];
				$path = trim( $r[1] );

				if(
					$path[0]=='"' ||
					$path[0]=="'"
				) {
					$path = substr( $path, 1, -1 );
				}


				if( $path[0]=='.' ) {

					$_base_URI = $base_URI;

					$path = explode( '/', $path );

					while( $path[0]=='..' ) {
						array_shift( $path );
						$_base_URI = dirname( $_base_URI );
					}

					if( $_base_URI=='/' ) {
						$_base_URI = '';
					}

					$URL = $_base_URI.'/'.implode( '/', $path );

				} else {
					if( $path[0]=='/' ) {
						$URL = $path;
					} else {
						$URL = $base_URI.$path;
					}
				}

				$CSS_file_data = str_replace( $orig_str, 'url("'.$URL.'")', $CSS_file_data );

			}

		}

		return $CSS_file_data;
	}


	/**
	 *
	 * @return string
	 */
	public function getKey()
	{
		if( !$this->key ) {
			$this->key = $this->media.'_'.md5( implode( '', $this->URIs ) );
		}

		return $this->key;
	}

	/**
	 * @return string
	 */
	public function getPackagePath()
	{
		return SysConf_PATH::PUBLIC().$this->getPackageRelativeFileName();
	}


	/**
	 * @return string
	 */
	public function getPackageRelativeFileName()
	{

		return static::getPackagesDirName().'/'.$this->getKey().'.css';
	}

	/**
	 * @return string
	 */
	public function getPackageURI()
	{
		return SysConf_URI::PUBLIC().$this->getPackageRelativeFileName();
	}


}