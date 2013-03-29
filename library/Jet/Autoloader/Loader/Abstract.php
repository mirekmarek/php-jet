<?php
/**
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Autoloader
 * @subpackage Autoloader_Loader
 */
namespace Jet;

abstract class Autoloader_Loader_Abstract {

	/**
	 * Get class script path or false
	 *
	 * @param $class_name
	 *
	 * @return string|bool
	 */
	abstract public function getClassPath( $class_name );
}