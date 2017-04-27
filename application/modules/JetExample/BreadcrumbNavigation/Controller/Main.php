<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\BreadcrumbNavigation;
use Jet\Mvc_Controller_Standard;
use Jet\Mvc;

/**
 *
 */
class Controller_Main extends Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	protected static $ACL_actions_check_map = [
		'default' => false
	];

	/**
	 *
	 */
	public function initialize() {
	}


	public function default_Action() {

        $view = $this->getActionParameterValue('view', 'default');

		$this->view->setVar('data', Mvc::getCurrentPage()->getBreadcrumbNavigation());

		$this->render( $view );
	}
}