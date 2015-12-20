<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\AdminUsers;
use Jet;
use Jet\Mvc_Controller_AJAX;
use Jet\Auth_Factory;

class Controller_AJAX extends Mvc_Controller_AJAX {
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


	function default_Action() {
		$role = Auth_Factory::getUserInstance();
		$form = $role->getCommonForm();
		$form->enableDecorator('Dojo');

		$this->view->setVar('form', $form);

		$this->render('ria/default');
	}

}