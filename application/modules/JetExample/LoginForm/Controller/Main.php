<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\LoginForm;
use Jet\Application_Log;
use Jet\Tr;
use Jet\Mvc_Controller_Standard;
use Jet\Mvc_Controller_Exception;
use Jet\Form;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Auth;

use JetExampleApp\Mvc_Page;
use JetExampleApp\Auth_Administrator_User;
use JetExampleApp\Auth_Visitor_User;

/**
 *
 */
class Controller_Main extends Mvc_Controller_Standard {
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
		$GET = Http_Request::GET();

		if($GET->exists('logout')) {
			Auth::logout();

			Http_Headers::movedTemporary( Mvc_Page::get(Mvc_Page::HOMEPAGE_ID)->getURI() );
		}
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
			$form->catchValues()
		) {
			if($form->validateValues()) {
				$data = $form->getValues();
				if(Auth::login( $data['username'], $data['password'] )) {
					Http_Headers::reload();
				} else {
					$form->setCommonMessage( Tr::_('Invalid username or password!') );
				}
			} else {
				$form->setCommonMessage( Tr::_('Please enter username and password') );
			}
		}

		$this->view->setVar('login_form', $form);

		$this->render('login');
	}


    /**
     *
     */
	public function is_not_activated_Action() {
		$this->render('is-not-activated');
	}

    /**
     *
     */
	public function is_blocked_Action() {
		$this->render('is-blocked');
	}

    /**
     *
     */
	public function must_change_password_Action() {
		/**
		 * @var Form $form
		 */
		$form = $this->module_instance->getMustChangePasswordForm();

		if(
			$form->catchValues() &&
			$form->validateValues()
		) {
			$data = $form->getValues();
			/**
			 * @var Auth_Administrator_User|Auth_Visitor_User $user
			 */
			$user = Auth::getCurrentUser();

			if(!$user->verifyPassword($data['password'])) {
				$user->setPassword( $data['password'] );
				$user->setPasswordIsValid(true);
				$user->setPasswordIsValidTill(null);
				$user->save();

				Application_Log::info('password_changed', 'User password changed', $user->getId(), $user->getUsername(), $user);

				Http_Headers::reload();
			} else {
				$form->getField('password')->setErrorMessage( Tr::_('Please enter <strong>new</strong> password') );
			}
		}

		$this->view->setVar('form', $form);

		$this->render('must-change-password');
	}
}