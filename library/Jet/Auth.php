<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	 * @var Auth_Controller_Interface
	 */
	protected static $controller;

	/**
	 *
	 * @return Auth_Controller_Interface
	 */
	public static function getController()
	{
		return static::$controller;
	}

	/**
	 * @param Auth_Controller_Interface $controller
	 */
	public static function setController( Auth_Controller_Interface $controller )
	{
		static::$controller = $controller;
	}

	/**
	 *
	 * @return bool
	 */
	public static function checkCurrentUser() {
		return static::getController()->checkCurrentUser();
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
		return static::getController()->getCurrentUserHasPrivilege( $privilege, $value );
	}

	/**
	 * @param string $module_name
	 * @param string $action
	 *
	 * @return bool
	 */
	public static function checkModuleActionAccess( $module_name, $action )
	{
		return static::getController()->checkModuleActionAccess( $module_name, $action );
	}

	/**
	 * @param Mvc_Page_Interface $page
	 *
	 * @return bool
	 */
	public static function checkPageAccess( Mvc_Page_Interface $page )
	{
		return static::getController()->checkPageAccess( $page );
	}

	/**
	 *
	 */
	public static function handleLogin()
	{
		static::getController()->handleLogin();
	}

}