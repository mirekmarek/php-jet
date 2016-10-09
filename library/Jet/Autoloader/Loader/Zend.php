<?php
/**
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Autoloader
 * @subpackage Autoloader_Loader
 */

namespace Jet;

class Autoloader_Loader_Zend extends Autoloader_Loader_Abstract {

	/**
	 * Get class script path or false
	 *
	 * @param $class_name
	 *
	 * @return string|bool
	 */
	public function getClassPath($class_name) {
		if(substr($class_name, 0, 4)!='Zend') {
			return false;
		}

		$class_name = str_replace( '\\', DIRECTORY_SEPARATOR, $class_name );
		$class_name = str_replace( '_', DIRECTORY_SEPARATOR, $class_name );

		return JET_LIBRARY_PATH.$class_name.'.php';

	}
}