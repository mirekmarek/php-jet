<?php
/**
 *
 *
 *
 * users, roles and privileges management class implementation
 *
 * @see Auth
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Auth
 * @subpackage Auth_ControllerModule
 */

namespace Jet;

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
	 * @param string $user_ID (optional; default: null = current user ID)
	 * @param string $user_login (optional; default: null = current user login)
	 */
	public function logEvent( $event, $event_data, $event_txt, $user_ID=null, $user_login=null );

	/**
	 * Returns Mvc page with contains some ACL actions (example: show login dialog )
	 *
	 * @return Mvc_Page_Interface
	 */
	public function getAuthenticationPage();

}