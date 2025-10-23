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
	 * @var callable|null
	 */
	protected static $controller_provider = null;

	/**
	 *
	 * @var Auth_Controller_Interface|null
	 */
	protected static ?Auth_Controller_Interface $controller = null;
	
	/**
	 * @param callable $provider
	 */
	public static function setControllerProvider( callable $provider ): void
	{
		static::$controller_provider = $provider;
	}
	
	/**
	 *
	 * @return ?Auth_Controller_Interface
	 */
	public static function getController(): ?Auth_Controller_Interface
	{
		if(
			!static::$controller &&
			($provider = static::$controller_provider)
		) {
			static::$controller = $provider();
		}
		
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
		return static::getController()?->checkCurrentUser()??false;
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
		return static::getController()?->login( $username, $password )??false;
	}

	/**
	 * @param Auth_User_Interface $user
	 * @return bool
	 */
	public static function loginUser( Auth_User_Interface $user ): bool
	{
		return static::getController()?->loginUser( $user )??false;
	}

	/**
	 */
	public static function logout(): void
	{
		static::getController()?->logout();
	}

	/**
	 *
	 * @return Auth_User_Interface|false
	 */
	public static function getCurrentUser(): Auth_User_Interface|false
	{
		return static::getController()?->getCurrentUser()??false;
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
		return static::getController()?->getCurrentUserHasPrivilege( $privilege, $value )??false;
	}

	/**
	 * @param string $module_name
	 * @param string $action
	 *
	 * @return bool
	 */
	public static function checkModuleActionAccess( string $module_name, string $action ): bool
	{
		return static::getController()?->checkModuleActionAccess( $module_name, $action )??false;
	}

	/**
	 * @param MVC_Page_Interface $page
	 *
	 * @return bool
	 */
	public static function checkPageAccess( MVC_Page_Interface $page ): bool
	{
		return static::getController()?->checkPageAccess( $page )??false;
	}

	/**
	 *
	 */
	public static function handleLogin(): void
	{
		static::getController()?->handleLogin();
	}

}