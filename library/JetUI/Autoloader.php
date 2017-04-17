<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Autoloader
 * @subpackage Autoloader_Loader
 */
namespace JetUI;

use Jet\Autoloader_Loader_Abstract;

class Autoloader extends Autoloader_Loader_Abstract {

	/**
	 * Get class script path or false
	 *
	 * @param $class_name
	 *
	 * @return string|bool
	 */
	public function getClassPath($class_name) {
		if(
			substr($class_name, 0, 6)!=__NAMESPACE__.'\\'
		) {
			return false;
		}

		$class_name = str_replace( '\\', DIRECTORY_SEPARATOR, $class_name );
		$class_name = str_replace( '_', DIRECTORY_SEPARATOR, $class_name );

        $path = dirname(__DIR__).DIRECTORY_SEPARATOR.$class_name.'.php';

		return $path;

	}
}