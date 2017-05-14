<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Mvc_Layout_PackageCreator_JavaScript_Abstract
 * @package Jet
 */
abstract class PackageCreator_JavaScript extends PackageCreator
{

	/**
	 * @var string
	 */
	protected static $packages_dir_name = 'js_packages';

	/**
	 * @return string
	 */
	public static function getPackagesDirName()
	{
		return static::$packages_dir_name;
	}

	/**
	 * @param string $packages_dir_name
	 */
	public static function setPackagesDirName( $packages_dir_name )
	{
		static::$packages_dir_name = $packages_dir_name;
	}

	/**
	 *
	 * @param Locale $locale
	 * @param array  $URIs
	 * @param array  $code
	 */
	abstract public function __construct( Locale $locale, array $URIs, array $code );


	/**
	 * @return array
	 */
	abstract public function getOmittedCode();

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