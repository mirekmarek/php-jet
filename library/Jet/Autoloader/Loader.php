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
	 * @return string
	 */
	abstract public function getAutoloaderName() : string;

	/**
	 *
	 * @param string $class_name
	 *
	 * @return bool|string
	 */
	abstract public function getScriptPath( string $class_name ): bool|string;
	
	/**
	 * @param string $class_name
	 * @return string
	 */
	public function classNameToPath( string $class_name ) : string
	{
		$class_name = ltrim($class_name, '/');
		$class_name = str_replace( '_', DIRECTORY_SEPARATOR, $class_name );
		$class_name = str_replace( '\\', DIRECTORY_SEPARATOR, $class_name );
		
		return  $class_name . '.php';
	}
}