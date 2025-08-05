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
interface Auth_Controller_Interface
{

	/**
	 *
	 */
	public function handleLogin(): void;

	/**
	 *
	 * @param string $username
	 * @param string $password
	 *
	 * @return bool
	 */
	public function login( string $username, string $password ): bool;
	
	/**
	 * @param Auth_User_Interface $user
	 * @return bool
	 */
	public function loginUser( Auth_User_Interface $user ) : bool;

	/**
	 *
	 */
	public function logout(): void;

	/**
	 * @return bool
	 */
	public function checkCurrentUser(): bool;

	/**
	 *
	 * @return Auth_User_Interface|false
	 */
	public function getCurrentUser(): Auth_User_Interface|false;

	/**
	 *
	 * @param string $privilege
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function getCurrentUserHasPrivilege( string $privilege, mixed $value=null ): bool;

	/**
	 * @param string $module_name
	 * @param string $action
	 *
	 * @return bool
	 */
	public function checkModuleActionAccess( string $module_name, string $action ): bool;


	/**
	 * @param MVC_Page_Interface $page
	 *
	 * @return bool
	 */
	public function checkPageAccess( MVC_Page_Interface $page ): bool;

}

