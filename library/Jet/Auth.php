<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
	protected static Auth_Controller_Interface $controller;

	/**
	 *
	 * @return Auth_Controller_Interface
	 */
	public static function getController(): Auth_Controller_Interface
	{
		return static::$controller;
	}

	/**
	 * @param Auth_Controller_Interface $controller
	 */
	public static function setController( Auth_Controller_Interface $controller ): void
	{
		static::$controller = $controller;
	}

	/**
	 *
	 * @return bool
	 */
	public static function checkCurrentUser(): bool
	{
		return static::getController()->checkCurrentUser();
	}

	/**
	 *
	 * @param string $username
	 * @param string $password
	 *
	 * @return bool
	 */
	public static function login( string $username, string $password ): bool
	{
		return static::getController()->login( $username, $password );
	}

	/**
	 */
	public static function logout(): void
	{
		static::getController()->logout();
	}

	/**
	 *
	 * @return Auth_User_Interface|bool
	 */
	public static function getCurrentUser(): Auth_User_Interface|bool
	{
		return static::getController()->getCurrentUser();
	}

	/**
	 *
	 * @param string $privilege
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public static function getCurrentUserHasPrivilege( string $privilege, mixed $value=null ): bool
	{
		return static::getController()->getCurrentUserHasPrivilege( $privilege, $value );
	}

	/**
	 * @param string $module_name
	 * @param string $action
	 *
	 * @return bool
	 */
	public static function checkModuleActionAccess( string $module_name, string $action ): bool
	{
		return static::getController()->checkModuleActionAccess( $module_name, $action );
	}

	/**
	 * @param MVC_Page_Interface $page
	 *
	 * @return bool
	 */
	public static function checkPageAccess( MVC_Page_Interface $page ): bool
	{
		return static::getController()->checkPageAccess( $page );
	}

	/**
	 *
	 */
	public static function handleLogin(): void
	{
		static::getController()->handleLogin();
	}

}