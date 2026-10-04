<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\SysServices\Manager;

use Jet\Auth_Controller_Interface;
use Jet\Auth_User_Interface;
use Jet\MVC_Page_Interface;

class AuthController implements Auth_Controller_Interface {
	
	public function handleLogin(): void {
	}
	
	public function login( string $username, string $password ): bool
	{
		return false;
	}
	
	public function loginUser( Auth_User_Interface $user ): bool
	{
		return false;
	}
	
	public function logout(): void
	{
	}
	
	public function checkCurrentUser(): bool
	{
		return false;
	}
	
	public function getCurrentUser(): Auth_User_Interface|false
	{
		return false;
	}
	
	public function getCurrentUserHasPrivilege( string $privilege, mixed $value = null ): bool
	{
		return false;
	}
	
	public function checkModuleActionAccess( string $module_name, string $action ): bool
	{
		return false;
	}
	
	public function checkPageAccess( MVC_Page_Interface $page ): bool
	{
		return false;
	}
}