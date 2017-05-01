<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetExampleApp;

use Jet\BaseObject;
use Jet\Auth_Controller_Interface;

use Jet\Mvc;
use Jet\Mvc_Factory;

use Jet\Session;


use Jet\Data_DateTime;


use JetExampleApp\Auth_Visitor_User as Visitor;

/**
 *
 */
class Auth_Controller_Site extends BaseObject implements Auth_Controller_Interface {

	/**
	 *
	 * @var Visitor
	 */
	protected $current_user = false;

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

		$module = Auth_Controller::getLoginFormModule();

		$page_content = [];
		$page_content_item = Mvc_Factory::getPageContentInstance();

		$page_content_item->setModuleName( $module->getModuleManifest()->getName() );
		$page_content_item->setControllerAction( $action );


		$page_content[] = $page_content_item;

		$page->setContent( $page_content );

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
		return new Session('auth_web');

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

		$user = Visitor::getByIdentity(  $login, $password  );

		if(!$user)  {
			return false;
		}

		/**
		 * @var Visitor $user
		 */
		$session = $this->getSession();
		$session->setValue( 'user_id', $user->getId() );

		$this->current_user = $user;

		return true;
	}


	/**
	 * Return current user data or FALSE
	 *
	 * @return Visitor|null
	 */
	public function getCurrentUser() {
		if($this->current_user!==false) {
			return $this->current_user;
		}

		$session = $this->getSession();

		$user_id = $session->getValue('user_id', null);

		if(!$user_id) {
			$this->current_user = null;
		} else {
			$this->current_user = Visitor::get($user_id);
		}

		return $this->current_user;
	}

	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------



}