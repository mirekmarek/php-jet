<?php
/**
 *
 *
 *
 * ModuleTemplate default admin controller
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule_BreadcrumbNavigation
 * @subpackage JetApplicationModule_BreadcrumbNavigation_Controller
 */
namespace JetApplicationModule\JetExample\BreadcrumbNavigation;
use Jet;

class Controller_Standard extends Jet\Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = NULL;

	protected static $ACL_actions_check_map = array(
		'default' => false
	);

	/**
	 *
	 */
	public function initialize() {
	}


	public function default_Action( $view='default' ) {
		//named params emulation
		if(is_array($view)) {
			extract($view, EXTR_IF_EXISTS);
		}

		$this->view->setVar('data', Jet\Mvc::getCurrentFrontController()->getBreadcrumbNavigation());

		$this->render( $view );
	}
}