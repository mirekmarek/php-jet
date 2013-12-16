<?php
/**
 *
 *
 *
 * Default admin UI module
 *
 * @see Jet\Mvc/readme.txt
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule_AdminUsers
 * @subpackage JetApplicationModule_AdminUsers_Controller
 */
namespace JetApplicationModule\Jet\AdminUsers;
use Jet;

class Controller_Standard extends Jet\Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = NULL;

	protected static $ACL_actions_check_map = array(
		"default" => false
	);


	public function default_Action() {

		Jet\Mvc::requireJavascriptLib("Jet");

		$GET = Jet\Http_Request::GET();

		$user = false;
		$user_instance = Jet\Auth_Factory::getUserInstance();
		if($GET->exists("new")) {
			$user = $user_instance;
			$user->initNewObject();

			$this->view->setVar("bnt_label", "ADD");

			$this->getUIManagerModuleInstance()->addBreadcrumbNavigationData("New user");
		} else if(
			$GET->exists("ID") &&
			($user = Jet\Auth::getUser( $GET->getString("ID") ) )
		) {
			/**
			 * @var Jet\Auth_User_Default $user
			 */
			$this->view->setVar("bnt_label", "SAVE" );

			$this->getUIManagerModuleInstance()->addBreadcrumbNavigationData("Edit user: ".$user->getLogin());
		}

		if($user) {
			$form = $user->getCommonForm();
			$this->view->setVar("form", $form);

			if($user->catchForm( $form )) {
				$user->validateProperties();
				$user->save();
				Jet\Http_Headers::formSent( $form );
			}

			$this->render("edit");
		} else {

			$this->view->setVar("users", Jet\Auth::getUsersList());

			$this->render("default");
		}

		//$form->helper_showBasicHTML();

	}
}