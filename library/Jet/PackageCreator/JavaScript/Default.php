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
class PackageCreator_JavaScript_Default extends PackageCreator_JavaScript
{

	/**
	 * @var string|null
	 */
	protected string|null $key = null;

	/**
	 *
	 * @param array  $URIs
	 */
	public function __construct( array $URIs )
	{
		$this->URIs = $URIs;
	}

	/**
	 *
	 */
	public function generate() : void
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
	public function createPackage() : string
	{
		$JS = '';

		foreach( $this->URIs as $URI ) {
			$JS .= '/* URI: '.$URI.' */'.PHP_EOL;
			$JS .= $this->getFileContent( $URI ).PHP_EOL;
			$JS .= '/* ------------------------ */ '.PHP_EOL;
		}

		return $JS;
	}


	/**
	 *
	 * @return string
	 */
	public function getKey() : string
	{
		if( !$this->key ) {
			$this->key = md5( implode( '', $this->URIs ) );
		}

		return $this->key;
	}


	/**
	 * @return string
	 */
	public function getPackageRelativeFileName() : string
	{

		return static::getPackagesDirName().'/'.$this->getKey().'.js';
	}

	/**
	 * @return string
	 */
	public function getPackagePath() : string
	{
		return SysConf_Path::PUBLIC().$this->getPackageRelativeFileName();
	}

	/**
	 * @return string
	 */
	public function getPackageURI() : string
	{
		return SysConf_URI::getPublic().$this->getPackageRelativeFileName();
	}

}