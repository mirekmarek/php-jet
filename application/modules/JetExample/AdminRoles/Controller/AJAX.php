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
namespace JetApplicationModule\JetExample\AdminRoles;
use Jet;

class Controller_AJAX extends Jet\Mvc_Controller_AJAX {
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


	function default_Action() {
		$role = Jet\Auth_Factory::getRoleInstance();
		$form = $role->getCommonForm();
		$form->enableDecorator('Dojo');

		$this->view->setVar('form', $form);
		$this->view->setVar('available_privileges_list', Jet\Auth::getAvailablePrivilegesList());

		$this->render('ria/default');
	}

}