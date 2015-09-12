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

/**
 * Class Auth_Config_Abstract
 *
 * @JetFactory:class = 'Jet\Auth_Factory'
 * @JetFactory:method = 'getAuthConfigInstance'
 * @JetFactory:mandatory_parent_class = 'Jet\Auth_Config_Abstract'
 *
 */
abstract class Auth_Config_Abstract extends Config_Application {

	/**
	 * @return string
	 */
	abstract public function getDefaultAuthControllerModuleName();

}