<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Autoloader_Loader_Abstract
 * @package Jet
 */
abstract class Autoloader_Loader_Abstract {

	/**
	 * Get class script path or false
	 *
	 * @param $class_name
	 *
	 * @return string|bool
	 */
	abstract public function getClassPath( $class_name );

	/**
	 * @return static
	 */
	public static function register() {
		$loader = new static();

		Autoloader::register( $loader );

		return $loader;
	}
}