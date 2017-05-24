<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Auth extends BaseObject
{


	/**
	 *
	 * @var Auth_ControllerInterface
	 */
	protected static $controller;

	/**
	 *
	 * @return Auth_ControllerInterface
	 */
	public static function getController()
	{
		return static::$controller;
	}

	/**
	 * @param Auth_ControllerInterface $controller
	 */
	public static function setController( Auth_ControllerInterface $controller )
	{
		static::$controller = $controller;
	}

	/**
	 *
	 * @return bool
	 */
	public static function isUserLoggedIn() {
		return static::getController()->isUserLoggedIn();
	}

	/**
	 *
	 * @param string $username
	 * @param string $password
	 *
	 * @return bool
	 */
	public static function login( $username, $password )
	{
		return static::getController()->login( $username, $password );
	}

	/**
	 * Logout current user
	 *
	 * @return mixed
	 */
	public static function logout()
	{

		return static::getController()->logout();
	}

	/**
	 *
	 * @return Auth_User_Interface|null
	 */
	public static function getCurrentUser()
	{
		return static::getController()->getCurrentUser();
	}

	/**
	 *
	 * @param string $privilege
	 * @param mixed  $value
	 *
	 * @return bool
	 */
	public static function getCurrentUserHasPrivilege( $privilege, $value )
	{
		if( ( $current_user = static::getCurrentUser() ) ) {
			return $current_user->hasPrivilege( $privilege, $value );
		}

		return false;
	}

	/**
	 *
	 */
	public static function handleLogin()
	{
		static::getController()->handleLogin();
	}

}