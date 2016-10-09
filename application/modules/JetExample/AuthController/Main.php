<?php
/**
 *
 *
 *
 * Default authentication and authorization module
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */

namespace JetApplicationModule\JetExample\AuthController;

use Jet\Application_Modules_Module_Abstract;
use Jet\Auth_Factory;
use Jet\Auth_Controller_Interface;
use Jet\Auth_User_Interface;
use Jet\Auth_User;
use Jet\Form_Field_RegistrationPassword;
use Jet\Mvc;
use Jet\Mvc_Factory;
use Jet\Mvc_Layout;
use Jet\Mvc_Page_Interface;
use Jet\Data_DateTime;
use Jet\Session;
use Jet\Form;
use Jet\Form_Field_Password;
use Jet\Form_Field_Input;

/**
 * Class Main
 *
 * @JetApplication_Signals:signal = '/user/login'
 * @JetApplication_Signals:signal = '/user/logout'
 */
class Main extends Application_Modules_Module_Abstract  implements Auth_Controller_Interface{

	/**
	 * Currently logged user
	 *
	 * @var Auth_User
	 */
	protected $current_user;

	/**
	 * Is called after controller instance is created
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
	 * Returns dispatch queue (example: show login dialog )
	 *
	 * @return Mvc_Page_Interface
	 */
	public function getAuthenticationPage() {

        $page = Mvc::getCurrentPage();


		$action = 'login';

		$user = $this->getCurrentUser();

		if($user) {
			if(!$user->getIsActivated()) {
				$action = 'isNotActivated';
			} else
				if($user->getIsBlocked()) {
					$action = 'isBlocked';
				} else
					if(!$user->getPasswordIsValid()) {
						$action = 'mustChangePassword';
					}
		}


        $page_content = [];
        $page_content_item = Mvc_Factory::getPageContentInstance();

        $page_content_item->setModuleName( $this->module_manifest->getName() );
        $page_content_item->setControllerAction( $action );
        $page_content_item->setIsDynamic(true);


        $page_content[] = $page_content_item;

        $page->setContents( $page_content );

        $layout = new Mvc_Layout( $this->getLayoutsDir(), 'default' );

        $page->setLayout( $layout );

		return $page;
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
		$user = Auth_Factory::getUserInstance()->getByIdentity(  $login, $password  );

		if(!$user)  {
			return false;
		}

		$session = $this->getSession();
		$session->setValue( 'user_ID', $user->getID() );

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
	 * @return Auth_User|bool
	 */
	public function getCurrentUser() {
		if($this->current_user!==null) {
			return $this->current_user;
		}

		$session = $this->getSession();

		$user_ID = $session->getValue( 'user_ID', null );
		if(!$user_ID) {
			$this->current_user = false;
			return null;
		}

		$user_class_name = JET_AUTH_USER_CLASS;

		/**
		 * @var Auth_User_Interface $user_class_name
		 */

		$this->current_user = $user_class_name::get($user_ID);

		return $this->current_user;
	}

	/**
	 * Log auth event
	 *
	 * @param string $event
	 * @param mixed $event_data
	 * @param string $event_txt
	 * @param string $user_ID (optional; default: null = current user ID)
	 * @param string $user_login (optional; default: null = current user login)
	 */
	public function logEvent( $event, $event_data, $event_txt, $user_ID=null, $user_login=null ) {
		if($user_ID===null) {
			$c_user = $this->getCurrentUser();

			if($c_user) {
				$user_ID = (string)$c_user->getID();
				$user_login = $c_user->getLogin();
			} else {
				$user_ID = '';
				$user_login = '';
			}

		}

		Event::logEvent($event, $event_data, $event_txt, $user_ID, $user_login);
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
        $login_field = new Form_Field_Input('login', 'User name: ');
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
		$form = new Form('login', [
			new Form_Field_RegistrationPassword( 'password', 'Password')
		]);

		$form->getField('password')->setIsRequired( true );

		return $form;
	}


}