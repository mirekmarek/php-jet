<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Layout
 */
namespace Jet;

abstract class Mvc_Layout_PackageCreator_JavaScript_Abstract extends Mvc_Layout_PackageCreator_Abstract {

	/**
	 * @var array|string[]
	 */
	protected $code = [];

	/**
	 * @var array|string[]
	 */
	protected $omitted_code = [];

	/**
	 *
	 * @param Locale $locale
	 * @param array $URIs
	 * @param array $code
	 */
	public function __construct( Locale $locale, array $URIs, array $code ) {

		$this->locale = $locale;
		$this->URIs = $URIs;
		$this->code = $code;

	}


	/**
	 * @return array
	 */
	public function getOmittedCode() {
		return $this->omitted_code;
	}

	/**
	 *
	 * @return string
	 */
	abstract public function getKey();

	/**
	 * @return string
	 */
	abstract public function createPackage();

	/**
	 *
	 */
	abstract public function generatePackageFile();

	/**
	 * @return string
	 */
	abstract public function getPackageRelativeFileName();

	/**
	 * @return string
	 */
	public function getPackagePath() {
		return JET_PUBLIC_PATH.$this->getPackageRelativeFileName();
	}

	/**
	 * @return string
	 */
	public function getPackageDataPath() {
		return JET_DATA_PATH.$this->getPackageRelativeFileName().'.dat';
	}

	/**
	 * @return string
	 */
	public function getPackageURI() {
		return JET_PUBLIC_URI.$this->getPackageRelativeFileName();
	}

}