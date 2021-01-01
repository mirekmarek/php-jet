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
abstract class PackageCreator_JavaScript extends PackageCreator
{

	/**
	 * @var string
	 */
	protected static string $packages_dir_name = 'js_packages';

	/**
	 * @return string
	 */
	public static function getPackagesDirName() : string
	{
		return static::$packages_dir_name;
	}

	/**
	 * @param string $packages_dir_name
	 */
	public static function setPackagesDirName( string $packages_dir_name ) : void
	{
		static::$packages_dir_name = $packages_dir_name;
	}

	/**
	 *
	 * @param array  $URIs
	 */
	abstract public function __construct( array $URIs );

	/**
	 *
	 */
	abstract public function generate() : void;

	/**
	 * @return string
	 */
	abstract public function createPackage() : string;

	/**
	 *
	 * @return string
	 */
	abstract public function getKey() : string;

	/**
	 * @return string
	 */
	abstract public function getPackagePath() : string;

	/**
	 * @return string
	 */
	abstract public function getPackageRelativeFileName() : string;

	/**
	 * @return string
	 */
	abstract public function getPackageURI() : string;

}