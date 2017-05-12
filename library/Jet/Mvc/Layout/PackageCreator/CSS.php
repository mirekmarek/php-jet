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
abstract class Mvc_Layout_PackageCreator_CSS extends Mvc_Layout_PackageCreator
{

	/**
	 *
	 * @param string $media
	 * @param Locale $locale
	 * @param array  $URIs
	 */
	abstract public function __construct( $media, Locale $locale, array $URIs );

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
	abstract public function getPackagePath();

	/**
	 * @return string
	 */
	abstract public function getPackageRelativeFileName();

	/**
	 * @return string
	 */
	abstract public function getPackageDataPath();

	/**
	 * @return string
	 */
	abstract public function getPackageURI();

}