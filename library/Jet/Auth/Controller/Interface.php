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
interface Auth_Controller_Interface
{

	/**
	 *
	 * @return bool
	 */
	public function isUserLoggedIn();

	/**
	 *
	 * @param string $username
	 * @param string $password
	 *
	 * @return bool
	 */
	public function login( $username, $password );

	/**
	 * Logout current user
	 *
	 * @return mixed
	 */
	public function logout();

	/**
	 *
	 * @return Auth_User_Interface|null
	 */
	public function getCurrentUser();

	/**
	 *
	 */
	public function handleLogin();

	/**
	 *
	 * @param string $privilege
	 * @param mixed  $value
	 *
	 * @return bool
	 */
	public function getCurrentUserHasPrivilege( $privilege, $value );

	/**
	 * @param string $module_name
	 * @param string $action
	 *
	 * @return bool
	 */
	public function checkModuleActionAccess( $module_name, $action );


	/**
	 * @param Mvc_Page_Interface $page
	 *
	 * @return bool
	 */
	public function checkPageAccess( Mvc_Page_Interface $page );

}