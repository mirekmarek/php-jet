<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\AuthController;
use Jet;
use Jet\Mvc_Controller_Standard;
use Jet\Mvc_Controller_Exception;
use Jet\Form;
use Jet\Auth_User_Abstract;
use Jet\Http_Headers;
use Jet\Auth;

class Controller_Standard extends Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	/**
	 * @param string $action
	 * @param array $action_parameters
	 *
	 * @throws Mvc_Controller_Exception
	 * 
	 * @return bool
	 */
	public function checkACL( $action, $action_parameters ) {
		return true;
	}

	/**
	 *
	 */
	public function initialize() {
	}


    /**
     *
     */
    public function login_Action() {

		/**
		 * @var Form $form
		 */
		$form = $this->module_instance->getLoginForm();

		if(
			$form->catchValues() &&
			$form->validateValues()
		) {
			$data = $form->getValues();
			if(Auth::login( $data['login'], $data['password'] )) {
				Http_Headers::reload();
			} else {
				$this->view->setVar('incorrect_login', true);
			}
		}

		$this->view->setVar('login_form', $form);

		$this->render('login');
	}


    /**
     *
     */
	public function isNotActivated_Action() {
		$this->render('is-not-activated');
	}

    /**
     *
     */
	public function isBlocked_Action() {
		$this->render('is-blocked');
	}

    /**
     *
     */
	public function mustChangePassword_Action() {
		/**
		 * @var Form $form
		 */
		$form = $this->module_instance->getChangePasswordForm();

		if(
			$form->catchValues() &&
			$form->validateValues()
		) {
			$data = $form->getValues();
			/**
			 * @var Auth_User_Abstract $user
			 */
			$user = $this->module_instance->getCurrentUser();
			$user->setPassword( $data['password'] );
			$user->setPasswordIsValid(true);
			$user->setPasswordIsValidTill(null);
			$user->save();

			Http_Headers::reload();
		}

		$this->view->setVar('form', $form);

		$this->render('must-change-password');
	}
}