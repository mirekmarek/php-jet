<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	 * @var string
	 */
	protected static $auth_controller_module_name;

	/**
	 * @var string
	 */
	protected static $auth_controller_class_name;

	/**
	 * Auth module instance
	 *
	 * @var Auth_Controller_Interface
	 */
	protected static $auth_controller;

	/**
	 * @param string $auth_controller_module_name
	 * @throws Exception
	 */
	public static function setAuthControllerModuleName($auth_controller_module_name)
	{
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
			static::$auth_controller_module_name = Mvc::getCurrentPage()->getAuthControllerModuleName();
		} else {
			if(defined('JET_DEFAULT_AUTH_CONTROLLER_MODULE_NAME')) {
				static::$auth_controller_module_name = JET_DEFAULT_AUTH_CONTROLLER_MODULE_NAME;
			}
		}

		return static::$auth_controller_module_name;
	}

	/**
	 * @return string
	 */
	public static function getAuthControllerClassName()
	{
		if(
			!self::$auth_controller_class_name &&
			defined('JET_DEFAULT_AUTH_CONTROLLER_CLASS_NAME')
		) {
			self::$auth_controller_class_name = JET_DEFAULT_AUTH_CONTROLLER_CLASS_NAME;
		}

		return self::$auth_controller_class_name;
	}

	/**
	 * @param string $auth_controller_class_name
	 */
	public static function setAuthControllerClassName($auth_controller_class_name)
	{
		self::$auth_controller_class_name = $auth_controller_class_name;
	}



	/**
	 * @param Auth_Controller_Interface $auth_controller
	 */
	public static function setAuthController(Auth_Controller_Interface $auth_controller)
	{
		self::$auth_controller = $auth_controller;
	}

	/**
	 * Get instance of current Auth module
	 *
	 * @return Auth_Controller_Interface
	 */
	public static function getAuthController()
	{
		if(!static::$auth_controller) {
			if( ($module_name=static::getAuthControllerModuleName()) ) {
				static::$auth_controller = Application_Modules::getModuleInstance( $module_name );
			} else {
				$class_name = static::getAuthControllerClassName();
				static::$auth_controller = new $class_name();
			}

		}

		return static::$auth_controller;
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
		if(!static::getAuthController()->login( $login, $password )) {
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

		return static::getAuthController()->logout();
	}

	/**
	 * Return current user data or FALSE
	 *
	 * @return Auth_User_Interface|bool
	 */
	public static function getCurrentUser() {
		return static::getAuthController()->getCurrentUser();
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
		static::getAuthController()->logEvent( $event, $event_data, $event_txt, $user_id, $user_login );
	}

}