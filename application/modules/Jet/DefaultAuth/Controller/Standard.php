<?php
/**
 *
 *
 *
 * Default auth manager module
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule_DefaultAuth
 * @subpackage JetApplicationModule_DefaultAuth_Controller
 */
namespace JetApplicationModule\Jet\DefaultAuth;
use Jet;

class Controller_Standard extends Jet\Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = NULL;

	/**
	 * @param string $action
	 * @param array $action_parameters
	 * @throws Jet\Mvc_Controller_Exception
	 */
	function checkACL( $action, $action_parameters ) {
	}


	public function login_Action() {

		/**
		 * @var Jet\Form $form
		 */
		$form = $this->module_instance->getLoginForm();

		if(
			$form->catchValues() &&
			$form->validateValues()
		) {
			$data = $form->getValues();
			if(Jet\Auth::login( $data["login"], $data["password"] )) {
				Jet\Http_Headers::reload();
			} else {
				$this->view->setVar("incorrect_login", true);
			}
		}

		$this->view->setVar("login_form", $form);

		$this->render("login");
	}


	public function isNotActivated_Action() {
		$this->render("is-not-activated");
	}

	public function isBlocked_Action() {
		$this->render("is-blocked");
	}

	public function mustChangePassword_Action() {
		/**
		 * @var Jet\Form $form
		 */
		$form = $this->module_instance->getChangePasswordForm();

		if(
			$form->catchValues() &&
			$form->validateValues()
		) {
			$data = $form->getValues();
			/**
			 * @var Jet\Auth_User_Abstract $user
			 */
			$user = $this->module_instance->getCurrentUser();
			$user->setPassword( $data["password"] );
			$user->setPasswordIsValid(true);
			$user->setPasswordIsValidTill(null);
			$user->validateData();
			$user->save();

			Jet\Http_Headers::reload();
		}

		$this->view->setVar("form", $form);

		$this->render("must-change-password");
	}
}