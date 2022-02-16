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
class PackageCreator_JavaScript_Default extends PackageCreator_JavaScript
{
	/**
	 *
	 * @param array $URIs
	 */
	public function __construct( array $URIs )
	{
		$this->URIs = $URIs;
	}

	/**
	 *
	 * @return string
	 */
	protected function createPackage(): string
	{
		$JS = '';

		foreach( $this->URIs as $URI ) {
			$JS .= '/* URI: ' . $URI . ' */' . PHP_EOL;
			$JS .= $this->getFileContent( $URI ) . PHP_EOL;
			$JS .= '/* ------------------------ */ ' . PHP_EOL;
		}

		return $JS;
	}


	/**
	 * @return string
	 */
	protected function getPackageRelativeFileName(): string
	{
		return SysConf_Jet_PackageCreator_JavaScript::getPackagesDirName() . '/' . $this->getKey() . '.js';
	}

	/**
	 * @return string
	 */
	public function getPackagePath(): string
	{
		return SysConf_Path::getJs() . $this->getPackageRelativeFileName();
	}

	/**
	 * @return string
	 */
	public function getPackageURI(): string
	{
		return SysConf_URI::getJs() . $this->getPackageRelativeFileName();
	}

}