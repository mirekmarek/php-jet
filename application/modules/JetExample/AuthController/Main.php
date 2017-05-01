<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\AuthController;

use Jet\Application_Log;
use Jet\Application_Modules_Module_Abstract;
use Jet\Auth_Controller_Interface;
use Jet\Mvc;
use Jet\Form;


use JetExampleApp\Application_Modules_Module_Manifest;
use JetExampleApp\Auth_Administrator_User as Administrator;
use JetExampleApp\Auth_Visitor_User as Visitor;

/**
 *
 */
class Main extends Application_Modules_Module_Abstract  implements Auth_Controller_Interface {

	const EVENT_LOGIN_FAILED = 'login_failed';
	const EVENT_LOGIN_SUCCESS = 'login_success';
	const EVENT_LOGOUT = 'logout';


	/**
	 * @var Main_Public
	 */
	protected $admin;

	/**
	 * @var Main_Public
	 */
	protected $public;

	/**
	 *
	 * @param Application_Modules_Module_Manifest $manifest
	 */
	public function __construct( Application_Modules_Module_Manifest $manifest )
	{
		parent::__construct($manifest);

		$this->admin = new Main_Admin( $this );
		$this->public = new Main_Public( $this );
	}

	/**
	 *
	 * @return bool
	 */
	public function getUserIsLoggedIn() {
		if( Mvc::getCurrentPage()->getIsAdminUI() ) {
			return $this->admin->getUserIsLoggedIn();
		} else {
			return $this->public->getUserIsLoggedIn();
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
			$this->public->handleLogin();
		}
	}


	/**
	 *
	 * @param string $login
	 * @param string $password
	 *
	 * @return bool
	 */
	public function login( $login, $password ) {
		if( Mvc::getCurrentPage()->getIsAdminUI() ) {
			$res = $this->admin->login( $login, $password );
		} else {
			$res = $this->public->login( $login, $password );
		}


		if(!$res) {
			Application_Log::warning(
							static::EVENT_LOGIN_FAILED,
							'Login failed. Login: \''.$login.'\'',
							$login
						);
			return false;
		} else {
			$user = $this->getCurrentUser();
			Application_Log::success(
							static::EVENT_LOGIN_SUCCESS,
							'User '.$user->getLogin().' (id:'.$user->getId().') has logged in',
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
							'User has '.$user->getLogin().' (id:'.$user->getId().') logged off',
							$user->getId(),
							$user->getName()
						);
		}


		if( Mvc::getCurrentPage()->getIsAdminUI() ) {
			$this->admin->logout();
		} else {
			$this->public->logout();
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
			return $this->public->getCurrentUser();
		}

	}

	/**
	 *
	 * @return Form
	 */
	public function getLoginForm() {

		if( Mvc::getCurrentPage()->getIsAdminUI() ) {
			return $this->admin->getLoginForm();
		} else {
			return $this->public->getLoginForm();
		}

	}

	/**
	 * @return Form
	 */
	public function getChangePasswordForm() {

		if( Mvc::getCurrentPage()->getIsAdminUI() ) {
			return $this->admin->getChangePasswordForm();
		} else {
			return $this->public->getChangePasswordForm();
		}

	}


	/**
	 * @return Form
	 */
	function getMustChangePasswordForm() {

		if( Mvc::getCurrentPage()->getIsAdminUI() ) {
			return $this->admin->getMustChangePasswordForm();
		} else {
			return $this->public->getMustChangePasswordForm();
		}

	}



}