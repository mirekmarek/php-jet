<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Interface Auth_ControllerInterface
 * @package Jet
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

}