<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Auth
 * @package Jet
 */
class Auth extends BaseObject {


	/**
	 * Auth module instance
	 *
	 * @var Auth_Controller_Interface
	 */
	protected static $auth_controller;

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
		return static::$auth_controller;
	}

	/**
	 * Authenticates given user and returns true if given username and password is OK
	 *
	 * @param string $username
	 * @param string $password
	 *
	 * @return bool
	 */
	public static function login($username, $password ) {
		return static::getAuthController()->login( $username, $password );
	}

	/**
	 * Logout current user
	 *
	 * @return mixed
	 */
	public static function logout() {

		return static::getAuthController()->logout();
	}

	/**
	 *
	 * @return Auth_User_Interface|null
	 */
	public static function getCurrentUser() {
		return static::getAuthController()->getCurrentUser();
	}

	/**
	 *
	 * @param string $privilege
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public static function getCurrentUserHasPrivilege( $privilege, $value) {
		if( ($current_user = static::getCurrentUser()) ) {
			return $current_user->hasPrivilege( $privilege, $value );
		}

		return false;
	}


}