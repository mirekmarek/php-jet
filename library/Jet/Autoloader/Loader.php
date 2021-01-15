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
abstract class Autoloader_Loader
{

	/**
	 * @return static
	 */
	public static function register(): static
	{
		$loader = new static();

		Autoloader::register( $loader );

		return $loader;
	}

	/**
	 *
	 * @param string $root_namespace
	 * @param string $namespace
	 * @param string $class_name
	 *
	 * @return bool|string
	 */
	abstract public function getScriptPath( string $root_namespace, string $namespace, string $class_name ): bool|string;
}