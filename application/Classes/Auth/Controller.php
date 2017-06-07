<?php
namespace JetApplication;

use Jet\Application_Modules;
use Jet\Application_Module;
use Jet\Application_Log;
use Jet\Auth_ControllerInterface;
use Jet\Mvc;

use JetApplication\Auth_Administrator_User as Administrator;
use JetApplication\Auth_Visitor_User as Visitor;

/**
 *
 */
class Auth_Controller implements Auth_ControllerInterface
{
	const LOGIN_FORM_MODULE_NAME = 'JetExample.Login';

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
	 * @var Auth_Controller_Site
	 */
	protected $rest;

	/**
	 *
	 */
	public function __construct()
	{
		$this->admin = new Auth_Controller_Admin();
		$this->site = new Auth_Controller_Site();
		$this->rest = new Auth_Controller_REST();
	}

	/**
	 * @return Application_Module
	 */
	public static function getLoginModule()
	{
		$module = Application_Modules::moduleInstance( static::LOGIN_FORM_MODULE_NAME );

		return $module;
	}

	/**
	 * @return Auth_Controller_Admin|Auth_Controller_REST|Auth_Controller_Site
	 */
	protected function getController()
	{
		/**
		 * @var Mvc_Page $page;
		 */
		$page = Mvc::getCurrentPage();

		if( $page->getIsRestApiHook() ) {
			return $this->rest;
		}

		if( $page->getIsAdminUI() ) {
			return $this->admin;
		}


		return $this->site;

	}

	/**
	 *
	 * @return bool
	 */
	public function isUserLoggedIn()
	{
		return $this->getController()->isUserLoggedIn();
	}

	/**
	 *
	 */
	public function handleLogin()
	{
		return $this->getController()->handleLogin();
	}


	/**
	 *
	 * @param string $username
	 * @param string $password
	 *
	 * @return bool
	 */
	public function login( $username, $password )
	{
		$res = $this->getController()->login( $username, $password );


		if( !$res ) {
			Application_Log::warning(
				static::EVENT_LOGIN_FAILED, 'Login failed. Username: \''.$username.'\'', $username
			);

			return false;
		} else {
			$user = $this->getCurrentUser();
			Application_Log::success(
				static::EVENT_LOGIN_SUCCESS, 'User '.$user->getUsername().' (id:'.$user->getId().') has logged in',
				$user->getId(), $user->getName()
			);

			return true;
		}

	}

	/**
	 *
	 * @return Administrator|Visitor|bool
	 */
	public function getCurrentUser()
	{
		return $this->getController()->getCurrentUser();
	}

	/**
	 *
	 */
	public function logout()
	{
		$user = $this->getCurrentUser();
		if( $user ) {
			Application_Log::info(
				static::EVENT_LOGOUT, 'User has '.$user->getUsername().' (id:'.$user->getId().') logged off',
				$user->getId(), $user->getName()
			);
		}


		$this->getController()->logout();
	}


}
