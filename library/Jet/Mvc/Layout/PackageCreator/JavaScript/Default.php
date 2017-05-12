<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Mvc_Layout_PackageCreator_JavaScript_Default extends Mvc_Layout_PackageCreator_JavaScript
{

	/**
	 * @var array|string[]
	 */
	protected $code = [];

	/**
	 * @var array|string[]
	 */
	protected $omitted_code = [];

	/**
	 * @var string
	 */
	protected $key = null;

	/**
	 *
	 * @param Locale $locale
	 * @param array  $URIs
	 * @param array  $code
	 */
	public function __construct( Locale $locale, array $URIs, array $code )
	{

		$this->locale = $locale;
		$this->URIs = $URIs;
		$this->code = $code;

	}

	/**
	 * @return array
	 */
	public function getOmittedCode()
	{
		return $this->omitted_code;
	}

	/**
	 * @return string
	 */
	public function getPackageRelativeFileName()
	{

		return Mvc_Layout::JS_PACKAGES_DIR_NAME.$this->getKey().'.js';
	}

	/**
	 *
	 * @return string
	 */
	public function getKey()
	{
		if( !$this->key ) {
			$this->key = $this->locale.'_'.md5( implode( '', $this->URIs ) );
		}

		return $this->key;
	}

	/**
	 *
	 */
	public function generatePackageFile()
	{

		$package_path = $this->getPackagePath();
		$package_data_path = $this->getPackageDataPath();

		if( !IO_File::exists( $package_path )||!IO_File::exists( $package_data_path ) ) {

			IO_File::write(
				$package_path, $this->createPackage()
			);

			IO_File::write(
				$package_data_path, serialize(
					                  [
						                  'omitted_code' => $this->omitted_code, 'omitted_URIs' => $this->omitted_URIs,
					                  ]
				                  )
			);

		} else {
			$data = IO_File::read( $package_data_path );
			$data = unserialize( $data );

			$this->omitted_code = $data['omitted_code'];
			$this->omitted_URIs = $data['omitted_URIs'];
		}

	}

	/**
	 *
	 * @return string
	 */
	public function createPackage()
	{
		$JS = '';

		foreach( $this->URIs as $URI ) {
			$JS .= '/* URI: '.$URI.' */'.JET_EOL;
			$JS .= $this->getFileContent( $URI ).JET_EOL;
			$JS .= '/* ------------------------ */ '.JET_EOL;
		}


		if( !$this->omitted_URIs ) {
			foreach( $this->code as $code ) {
				$JS .= $code.JET_EOL;
			}

			$this->omitted_code = [];
		} else {
			$this->omitted_code = $this->code;
		}

		return $JS;
	}

	/**
	 * @return string
	 */
	public function getPackagePath()
	{
		return JET_PUBLIC_PATH.$this->getPackageRelativeFileName();
	}

	/**
	 * @return string
	 */
	public function getPackageDataPath()
	{
		return JET_DATA_PATH.$this->getPackageRelativeFileName().'.dat';
	}

	/**
	 * @return string
	 */
	public function getPackageURI()
	{
		return JET_PUBLIC_URI.$this->getPackageRelativeFileName();
	}

}