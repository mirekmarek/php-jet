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
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Router
 */
namespace Jet;

abstract class Mvc_Router_Config_Abstract extends Config_Application {
	/**
	 * @var string
	 */
	protected static $__factory_class_name = "Jet\\Mvc_Factory";
	/**
	 * @var string
	 */
	protected static $__factory_class_method = "getRouterConfigInstance";
	/**
	 * @var string
	 */
	protected static $__factory_must_be_instance_of_class_name = "Jet\\Mvc_Router_Config_Abstract";

	/**
	 * @return array
	 */
	abstract public function getCacheBackendOptions();

	/**
	 * @return string
	 */
	abstract public function getCacheBackendType();

	/**
	 * @return boolean
	 */
	abstract public function getCacheEnabled();

	/**
	 * @return string
	 */
	abstract public function getDefaultAdminUIManagerModuleName();

	/**
	 * @return string
	 */
	abstract public function getDefaultSiteUIManagerModuleName();

	/**
	 * @return string
	 */
	abstract public function getDefaultAuthManagerModuleName();

}