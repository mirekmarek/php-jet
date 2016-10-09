<?php
/**
 *
 *
 *
 * Default router config class
 *
 * @see Mvc/readme.txt
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Router
 */
namespace Jet;

abstract class Mvc_Router_Config_Abstract extends Application_Config {

	/**
	 * @return boolean
	 */
	abstract public function getCacheEnabled();

	/**
	 * @return array
	 */
	abstract public function getCacheBackendOptions();

	/**
	 * @return string
	 */
	abstract public function getCacheBackendType();


}