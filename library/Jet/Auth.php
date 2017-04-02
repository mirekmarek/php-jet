<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Auth
 */

namespace Jet;

class Auth extends BaseObject {

	/**
	 * @var Auth_Config_Abstract
	 */
	protected static $config;

	/**
	 * @var string
	 */
	protected static $auth_controller_module_name;

	/**
	 * Auth module instance
	 *
	 * @var Auth_Controller_Interface
	 */
	protected static $current_auth_controller;

	/**
	 * @param Auth_Config_Abstract $config
	 */
	public static function setConfig( Auth_Config_Abstract $config)
	{
		self::$config = $config;
	}

	/**
	 * @return Auth_Config_Abstract
	 */
	public static function getConfig()
	{
		if(!self::$config) {
			self::$config = Auth_Factory::getConfigInstance();
		}

		return self::$config;
	}


	/**
	 * @param string $auth_controller_module_name
	 * @throws Exception
	 */
	public static function setAuthControllerModuleName($auth_controller_module_name)
	{
		if( self::$current_auth_controller ) {
			throw new Exception('Auth Controller has been already set! It is not possible to setup it\'s name.');
		}
		static::$auth_controller_module_name = $auth_controller_module_name;
	}

	/**
	 *
	 * @return string
	 */
	public static function getAuthControllerModuleName()
	{
		if(static::$auth_controller_module_name) {
			return static::$auth_controller_module_name;
		}

		if(Mvc::getCurrentPage() && Mvc::getCurrentPage()->getAuthControllerModuleName()) {
			return Mvc::getCurrentPage()->getAuthControllerModuleName();
		}


		return Auth::getConfig()->getDefaultAuthControllerModuleName();
	}

	/**
	 * @param Auth_Controller_Interface $current_auth_controller
	 */
	public static function setCurrentAuthController(Auth_Controller_Interface $current_auth_controller)
	{
		self::$current_auth_controller = $current_auth_controller;
	}

	/**
	 * Get instance of current Auth module
	 *
	 * @return Auth_Controller_Interface
	 */
	public static function getCurrentAuthController()
	{
		if(!static::$current_auth_controller) {
			static::$current_auth_controller = Application_Modules::getModuleInstance( static::getAuthControllerModuleName() );
		}

		return static::$current_auth_controller;
	}

	/**
	 * Authenticates given user and returns true if given username and password is OK
	 *
	 * @param string $login
	 * @param string $password
	 *
	 * @return bool
	 */
	public static function login( $login, $password ) {
		if(!static::getCurrentAuthController()->login( $login, $password )) {
			static::logEvent('login_failed', ['login'=>$login], 'Login failed. Login: \''.$login.'\'');
			return false;
		} else {
			$user_id = static::getCurrentUser()->getId();
			static::logEvent('login', ['login'=>$login, 'user_id'=>$user_id], 'Login successful. Login: \''.$login.'\', User ID: \''.$user_id.'\'');
			return true;
		}
	}

	/**
	 * Logout current user
	 *
	 * @return mixed
	 */
	public static function logout() {
		$user = static::getCurrentUser();
		if($user) {
			$login = $user->getLogin();
			$user_id = $user->getId();
			static::logEvent('logout', ['login'=>$login, 'user_id'=>$user_id], 'Logout successful. Login: \''.$login.'\', User ID: \''.$user_id.'\'');
		}

		return static::getCurrentAuthController()->logout();
	}

	/**
	 * Return current user data or FALSE
	 *
	 * @return Auth_User_Interface|bool
	 */
	public static function getCurrentUser() {
		return static::getCurrentAuthController()->getCurrentUser();
	}

	/**
	 * Does current user have given privilege?
	 *
	 * @param string $privilege
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public static function getCurrentUserHasPrivilege( $privilege, $value) {
		if( ($current_user = static::getCurrentUser()) ) {
			return $current_user->getHasPrivilege( $privilege, $value );
		}

		return false;
	}

	/**
	 * Log auth event
	 *
	 * @param string $event
	 * @param mixed $event_data
	 * @param string $event_txt
	 * @param string $user_id (optional; default: null = current user id)
	 * @param string $user_login (optional; default: null = current user login)
	 */
	public static function logEvent($event, $event_data, $event_txt, $user_id=null, $user_login=null ) {
		static::getCurrentAuthController()->logEvent( $event, $event_data, $event_txt, $user_id, $user_login );
	}

}