<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
abstract class PackageCreator_CSS extends PackageCreator
{

	/**
	 *
	 * @param string $media
	 * @param array $URIs
	 */
	abstract public function __construct( string $media, array $URIs );

	/**
	 *
	 */
	abstract public function generate(): void;

	/**
	 * @return string
	 */
	abstract public function createPackage(): string;

	/**
	 *
	 * @return string
	 */
	abstract public function getKey(): string;

	/**
	 * @return string
	 */
	abstract public function getPackagePath(): string;

	/**
	 * @return string
	 */
	abstract public function getPackageRelativeFileName(): string;

	/**
	 * @return string
	 */
	abstract public function getPackageURI(): string;

}