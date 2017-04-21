<?php
/**
 *
 *
 *
 * Default authentication and authorization module
 *
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */

namespace JetApplicationModule\JetExample\AuthController;

use Jet\Application_Modules_Module_Abstract;
use Jet\Auth_Controller_Interface;
use Jet\Form_Field_RegistrationPassword;
use Jet\Mvc;
use Jet\Mvc_Factory;
use Jet\Mvc_Layout;
use Jet\Data_DateTime;
use Jet\Session;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Form_Field_Password;


use JetExampleApp\Auth_Administrator_User;
use JetExampleApp\Auth_Visitor_User;

/**
 * Class Main
 *
 * @JetApplication_Signals:signal = '/user/login'
 * @JetApplication_Signals:signal = '/user/logout'
 */
class Main extends Application_Modules_Module_Abstract  implements Auth_Controller_Interface {

	/**
	 * Currently logged user
	 *
	 * @var Auth_Administrator_User|Auth_Visitor_User
	 */
	protected $current_user;

	/**
	 */
	public function initialize()
	{
	}

	/**
	 *
	 * @return bool
	 */
	public function getUserIsLoggedIn() {

		$user = $this->getCurrentUser();
		if(!$user) {
			return false;
		}

		if(
		!$user->getIsActivated()
		) {
			return false;
		}

		if($user->getIsBlocked()) {
			$till = $user->getIsBlockedTill();
			if(
				$till!==null &&
				$till<=Data_DateTime::now()
			) {
				$user->unBlock();
				$user->save();
			} else {
				return false;
			}
		}

		if( !$user->getPasswordIsValid() ) {
			return false;
		}

		if(
			($pwd_valid_till = $user->getPasswordIsValidTill())!==null &&
			$pwd_valid_till<=Data_DateTime::now()
		) {
			$user->setPasswordIsValid(false);
			$user->save();

			return false;
		}

		return true;
	}

	/**
	 *
	 */
	public function handleLogin()
	{

		$page = Mvc::getCurrentPage();


		$action = 'login';

		$user = $this->getCurrentUser();

		if($user) {
			if(!$user->getIsActivated()) {
				$action = 'is_not_activated';
			} else
				if($user->getIsBlocked()) {
					$action = 'is_blocked';
				} else
					if(!$user->getPasswordIsValid()) {
						$action = 'must_change_password';
					}
		}


		$page_content = [];
		$page_content_item = Mvc_Factory::getPageContentInstance();

		$page_content_item->setModuleName( $this->module_manifest->getName() );
		$page_content_item->setControllerAction( $action );


		$page_content[] = $page_content_item;

		$page->setContent( $page_content );

		if(Mvc::getIsAdminUIRequest()) {
			$layout = new Mvc_Layout( $this->getLayoutsDir(), 'default' );

			Mvc_Layout::setCurrentLayout($layout);
		}

		echo $page->render();
	}


	/**
	 * @return Session
	 */
	protected function getSession() {
		if( Mvc::getIsAdminUIRequest() ) {
			return new Session('auth_admin');
		} else {
			return new Session('auth_web');
		}

	}

	/**
	 * Authenticates given user and returns TRUE if given credentials are valid, otherwise returns FALSE
	 *
	 * @param string $login
	 * @param string $password
	 *
	 * @return bool
	 */
	public function login( $login, $password ) {
		if(Mvc::getIsAdminUIRequest()) {
			$user = new Auth_Administrator_User();
		} else {
			$user = new Auth_Visitor_User();
		}

		$user = $user->getByIdentity(  $login, $password  );

		if(!$user)  {
			return false;
		}

		$session = $this->getSession();
		$session->setValue( 'user_id', $user->getId() );

		$this->current_user = $user;

		$this->sendSignal('/user/login');

		return true;
	}

	/**
	 * Logout current user
	 */
	public function logout() {
		$this->sendSignal('/user/logout');

		Session::destroy();
		$this->current_user = null;
	}

	/**
	 * Return current user data or FALSE
	 *
	 * @return Auth_Administrator_User|Auth_Visitor_User|bool
	 */
	public function getCurrentUser() {
		if($this->current_user!==null) {
			return $this->current_user;
		}

		$session = $this->getSession();

		$user_id = $session->getValue('user_id', null);

		if(!$user_id) {
			$this->current_user = false;
		} else {
			if(Mvc::getIsAdminUIRequest()) {
				$this->current_user = Auth_Administrator_User::get($user_id);

			} else {
				$this->current_user = Auth_Visitor_User::get($user_id);
			}
		}

		return $this->current_user;
	}


	/**
	 * Log auth event
	 *
	 * @param string $event
	 * @param mixed $event_data
	 * @param string $event_txt
	 * @param string $user_id (optional; default: null = current user ID)
	 * @param string $user_login (optional; default: null = current user login)
	 */
	public function logEvent( $event, $event_data, $event_txt, $user_id=null, $user_login=null ) {
		if($user_id===null) {
			$c_user = $this->getCurrentUser();

			if($c_user) {
				$user_id = $c_user->getId();
				$user_login = $c_user->getLogin();
			} else {
				$user_id = 0;
				$user_login = '';
			}

		}

		if(Mvc::getIsAdminUIRequest()) {
			Event_Administration::logEvent($event, $event_data, $event_txt, $user_id, $user_login);
		} else {
			Event_Site::logEvent($event, $event_data, $event_txt, $user_id, $user_login);
		}
	}

	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


	/**
	 * Get login form instance
	 *
	 * @return Form
	 */
	function getLoginForm() {
        $login_field =  new Form_Field_Input('login', 'User name: ');
        $login_field->setErrorMessages([
            Form_Field_Input::ERROR_CODE_EMPTY => 'Please type user name'
        ]);
        $password_field = new Form_Field_Password('password', 'Password:');
        $password_field->setErrorMessages([
            Form_Field_Input::ERROR_CODE_EMPTY => 'Please type password'
        ]);

		$form = new Form('login', [
            $login_field,
            $password_field
		]);

		$form->getField('login')->setIsRequired( true );
		/**
		 * @var Form_Field_Password $password
		 */
		$password = $form->getField('password');
		$password->setIsRequired( true );

		return $form;
	}

	/**
	 * @return Form
	 */
	function getChangePasswordForm() {

		$current_password = new Form_Field_Password('current_password', 'Current password');
		$current_password->setIsRequired( true );
		$current_password->setErrorMessages([
			Form_Field_RegistrationPassword::ERROR_CODE_EMPTY => 'Please type new password',
		]);

		$new_password = new Form_Field_RegistrationPassword('password', 'New password');
		$new_password->setPasswordConfirmationLabel('Confirm new password');

		$new_password->setIsRequired( true );
		$new_password->setErrorMessages([
			Form_Field_RegistrationPassword::ERROR_CODE_EMPTY => 'Please type new password',
			Form_Field_RegistrationPassword::ERROR_CODE_CHECK_EMPTY => 'Please confirm new password',
			Form_Field_RegistrationPassword::ERROR_CODE_CHECK_NOT_MATCH => 'Password confirmation do not match'
		]);


		$form = new Form('change_password', [
			$current_password,
			$new_password
		]);





		return $form;
	}


	/**
	 * @return Form
	 */
	function getMustChangePasswordForm() {
		$password = new Form_Field_RegistrationPassword('password', 'New password: ');
		$form = new Form('change_password', [
			$password
		]);


		$password->setErrorMessages([
			Form_Field_RegistrationPassword::ERROR_CODE_EMPTY => 'Please type new password',
			Form_Field_RegistrationPassword::ERROR_CODE_CHECK_EMPTY => 'Please confirm new password',
			Form_Field_RegistrationPassword::ERROR_CODE_CHECK_NOT_MATCH => 'Password confirmation do not match'
		]);
		$password->setIsRequired( true );
		$password->setPasswordConfirmationLabel('Confirm new password');

		return $form;
	}



}