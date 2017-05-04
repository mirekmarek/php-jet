<?php
namespace JetExampleApp;

use Jet\Application_Modules;
use Jet\Application_Modules_Module_Abstract;
use Jet\Application_Log;
use Jet\Auth_Controller_Interface;
use Jet\Mvc;

use JetExampleApp\Auth_Administrator_User as Administrator;
use JetExampleApp\Auth_Visitor_User as Visitor;

/**
 *
 */
class Auth_Controller implements Auth_Controller_Interface
{
	const LOGIN_FORM_MODULE_NAME = 'JetExample.LoginForm';

	const EVENT_LOGIN_FAILED = 'login_failed';
	const EVENT_LOGIN_SUCCESS = 'login_success';
	const EVENT_LOGOUT = 'logout';


	/**
	 * @var Auth_Controller_Admin
	 */
	protected $admin;

	/**
	 * @var Auth_Controller_Site
	 */
	protected $site;

	/**
	 *
	 */
	public function __construct()
	{
		$this->admin = new Auth_Controller_Admin();
		$this->site = new Auth_Controller_Site();
	}

	/**
	 * @return Application_Modules_Module_Abstract
	 */
	public static function getLoginFormModule() {
		$module = Application_Modules::getModuleInstance(static::LOGIN_FORM_MODULE_NAME);

		return $module;
	}

	/**
	 *
	 * @return bool
	 */
	public function isUserLoggedIn() {
		if( Mvc::getCurrentPage()->getIsAdminUI() ) {
			return $this->admin->isUserLoggedIn();
		} else {
			return $this->site->isUserLoggedIn();
		}
	}

	/**
	 *
	 */
	public function handleLogin()
	{
		if( Mvc::getCurrentPage()->getIsAdminUI() ) {
			$this->admin->handleLogin();
		} else {
			$this->site->handleLogin();
		}
	}


	/**
	 *
	 * @param string $username
	 * @param string $password
	 *
	 * @return bool
	 */
	public function login($username, $password ) {
		if( Mvc::getCurrentPage()->getIsAdminUI() ) {
			$res = $this->admin->login( $username, $password );
		} else {
			$res = $this->site->login( $username, $password );
		}


		if(!$res) {
			Application_Log::warning(
				static::EVENT_LOGIN_FAILED,
				'Login failed. Username: \''.$username.'\'',
				$username
			);
			return false;
		} else {
			$user = $this->getCurrentUser();
			Application_Log::success(
				static::EVENT_LOGIN_SUCCESS,
				'User '.$user->getUsername().' (id:'.$user->getId().') has logged in',
				$user->getId(),
				$user->getName()
			);

			return true;
		}

	}

	/**
	 *
	 */
	public function logout() {
		$user = $this->getCurrentUser();
		if($user) {
			Application_Log::info(
				static::EVENT_LOGOUT,
				'User has '.$user->getUsername().' (id:'.$user->getId().') logged off',
				$user->getId(),
				$user->getName()
			);
		}


		if( Mvc::getCurrentPage()->getIsAdminUI() ) {
			$this->admin->logout();
		} else {
			$this->site->logout();
		}
	}

	/**
	 *
	 * @return Administrator|Visitor|bool
	 */
	public function getCurrentUser() {
		if( Mvc::getCurrentPage()->getIsAdminUI() ) {
			return $this->admin->getCurrentUser();
		} else {
			return $this->site->getCurrentUser();
		}

	}


}
