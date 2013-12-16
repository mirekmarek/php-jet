<?php
/**
 *
 *
 *
 * Default controller
 * Applied for the standard mode.
 *
 * @see Mvc/readme.txt
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Controller
 */
namespace Jet;

abstract class Mvc_Controller_Standard extends Mvc_Controller_Abstract {
	/**
	 * @param string $module_action
	 * @param string $controller_action
	 * @param array $action_parameters
	 *
	 */
	public function responseAclAccessDenied( $module_action, $controller_action, $action_parameters ) {
		$this->router->getUIManagerModuleInstance()->handleAccessDenied();
	}

}