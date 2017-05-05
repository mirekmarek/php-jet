<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Mvc_Layout_PackageCreator_CSS_Abstract
 * @package Jet
 */
abstract class Mvc_Layout_PackageCreator_CSS_Abstract extends Mvc_Layout_PackageCreator_Abstract
{

	/**
	 * @var string
	 */
	protected $media;

	/**
	 *
	 * @param string $media
	 * @param Locale $locale
	 * @param array  $URIs
	 */
	public function __construct( $media, Locale $locale, array $URIs )
	{

		$this->media = $media;
		$this->locale = $locale;
		$this->URIs = $URIs;

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
	public function getPackagePath()
	{
		return JET_PUBLIC_PATH.$this->getPackageRelativeFileName();
	}

	/**
	 * @return string
	 */
	abstract public function getPackageRelativeFileName();

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