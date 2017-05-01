<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\AuthController;

use Jet\BaseObject;
use Jet\Auth_Controller_Interface;

use Jet\Mvc;
use Jet\Mvc_Factory;
use Jet\Mvc_Layout;

use Jet\Session;

use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Form_Field_Password;
use Jet\Form_Field_RegistrationPassword;

use Jet\Data_DateTime;

use JetExampleApp\Mvc_Page as Page;
use JetExampleApp\Auth_Administrator_User as Administrator;

/**
 *
 */
class Main_Admin extends BaseObject implements Auth_Controller_Interface{

	/**
	 * Currently logged user
	 *
	 * @var Administrator
	 */
	protected $current_user;

	/**
	 * @var Main
	 */
	protected $module;

	/**
	 *
	 * @param Main $module
	 */
	public function __construct( Main $module )
	{
		$this->module = $module;
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

		$page_content_item->setModuleName( $this->module->getModuleManifest()->getName() );
		$page_content_item->setControllerAction( $action );


		$page_content[] = $page_content_item;

		$page->setContent( $page_content );


		$layout = new Mvc_Layout( $this->module->getLayoutsDir(), 'default' );

		Mvc_Layout::setCurrentLayout($layout);


		echo $page->render();
	}

	/**
	 * Logout current user
	 */
	public function logout() {
		Session::destroy();
		$this->current_user = null;
	}

	/**
	 * @return Session
	 */
	protected function getSession() {
		return new Session('auth_admin');
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

		$user = Administrator::getByIdentity(  $login, $password  );

		if(!$user)  {
			return false;
		}

		/**
		 * @var Administrator $user
		 */
		$session = $this->getSession();
		$session->setValue( 'user_id', $user->getId() );

		$this->current_user = $user;

		return true;
	}

	/**
	 * Return current user data or FALSE
	 *
	 * @return Administrator|bool
	 */
	public function getCurrentUser() {
		if($this->current_user!==null) {
			return $this->current_user;
		}

		$session = $this->getSession();

		$user_id = $session->getValue('user_id', null);

		if(!$user_id) {
			$this->current_user = false;

			/**
			 * @var Page $page
			 */
			$page = Mvc::getCurrentPage();
			if($page->getIsRestApiHook()) {
				$this->handleRestApiHttpAuth();
			}


		} else {
			$this->current_user = Administrator::get($user_id);
		}

		return $this->current_user;
	}

	/**
	 *
	 */
	protected function handleRestApiHttpAuth() {

		if (!isset($_SERVER['PHP_AUTH_USER'])) {
			header('WWW-Authenticate: Basic realm="Login"');
			header('HTTP/1.0 401 Unauthorized');
		} else {
			$user = Administrator::getByIdentity($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);

			if($user) {
				$this->current_user = $user;
			}
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
	public function getLoginForm() {
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
	public function getChangePasswordForm() {
		$user = new Administrator();

		$current_password = new Form_Field_Password('current_password', 'Current password');
		$current_password->setIsRequired( true );
		$current_password->setErrorMessages([
			Form_Field_RegistrationPassword::ERROR_CODE_EMPTY => 'Please type new password',
		]);

		$new_password = new Form_Field_RegistrationPassword('password', 'New password');
		$new_password->setPasswordConfirmationLabel('Confirm new password');

		$new_password->setPasswordStrengthCheckCallback([$user, 'verifyPasswordStrength']);

		$new_password->setIsRequired( true );
		$new_password->setErrorMessages([
			Form_Field_RegistrationPassword::ERROR_CODE_EMPTY => 'Please type new password',
			Form_Field_RegistrationPassword::ERROR_CODE_CHECK_EMPTY => 'Please confirm new password',
			Form_Field_RegistrationPassword::ERROR_CODE_CHECK_NOT_MATCH => 'Password confirmation do not match',
			Form_Field_RegistrationPassword::ERROR_CODE_WEAK_PASSWORD => 'Password is not strong enough',
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

		$password->setPasswordStrengthCheckCallback([$this->getCurrentUser(), 'verifyPasswordStrength']);

		$password->setErrorMessages([
			Form_Field_RegistrationPassword::ERROR_CODE_EMPTY => 'Please type new password',
			Form_Field_RegistrationPassword::ERROR_CODE_CHECK_EMPTY => 'Please confirm new password',
			Form_Field_RegistrationPassword::ERROR_CODE_CHECK_NOT_MATCH => 'Password confirmation do not match',
			Form_Field_RegistrationPassword::ERROR_CODE_WEAK_PASSWORD => 'Password is not strong enough',
		]);
		$password->setIsRequired( true );
		$password->setPasswordConfirmationLabel('Confirm new password');

		return $form;
	}



}