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
abstract class PackageCreator_CSS extends PackageCreator
{
	/**
	 * @var string
	 */
	protected static $packages_dir_name = 'css_packages';

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