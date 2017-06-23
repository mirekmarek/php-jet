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
interface Auth_ControllerInterface
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
	 * @param Mvc_Page_Interface $page
	 *
	 * @return bool
	 */
	public function checkPageAccess( Mvc_Page_Interface $page );

}