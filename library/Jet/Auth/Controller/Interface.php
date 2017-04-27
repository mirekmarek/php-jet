<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Interface Auth_Controller_Interface
 * @package Jet
 */
interface Auth_Controller_Interface {

	/**
	 * Returns true if authentication (for example login dialog...) is required
	 *
	 * @return bool
	 */
	public function getUserIsLoggedIn();

	/**
	 * Authenticates given user and returns true if given username and password is OK
	 *
	 * @param string $login
	 * @param string $password
	 *
	 * @return bool
	 */
	public function login( $login, $password );

	/**
	 * Logout current user
	 *
	 * @return mixed
	 */
	public function logout();

	/**
	 * Return current user data or FALSE
	 *
	 * @return Auth_User_Interface|bool
	 */
	public function getCurrentUser();

	/**
	 * Log auth event
	 *
	 * @abstract
	 *
	 * @param string $event
	 * @param mixed $event_data
	 * @param string $event_txt
	 * @param string $user_id (optional; default: null = current user ID)
	 * @param string $user_login (optional; default: null = current user login)
	 */
	public function logEvent( $event, $event_data, $event_txt, $user_id=null, $user_login=null );

	/**
	 *
	 */
	public function handleLogin();

}