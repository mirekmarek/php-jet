<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	 * @return self
	 */
	public static function register()
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
	abstract public function getScriptPath( $root_namespace, $namespace, $class_name );
}