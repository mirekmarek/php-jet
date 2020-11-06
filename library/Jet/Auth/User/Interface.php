<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
interface Auth_User_Interface
{

	/**
	 * @param string|int $id
	 *
	 * @return Auth_User_Interface
	 */
	public static function get( $id );

	/**
	 *
	 * @param string|int|null $role_id (optional)
	 *
	 * @return Auth_User_Interface[]
	 */
	public static function getList( $role_id = null );

	/**
	 *
	 * @param string $username
	 * @param string $password
	 *
	 * @return Auth_User_Interface|null
	 */
	public static function getByIdentity( $username, $password );

	/**
	 *
	 * @param string $username
	 *
	 * @return Auth_User_Interface|null
	 */
	public static function getGetByUsername( $username );

	/**
	 * @return string|int
	 */
	public function getId();


	/**
	 * @return string
	 */
	public function getUsername();

	/**
	 * @param string $username
	 */
	public function setUsername( $username );

	/**
	 * @param string $username
	 *
	 * @return bool
	 */
	public function usernameExists( $username );

	/**
	 * @param string $password
	 */
	public function setPassword( $password );

	/**
	 * @param string $password
	 *
	 * @return string
	 */
	public function encryptPassword( $password );

	/**
	 * @param string $plain_password
	 *
	 * @return bool
	 */
	public function verifyPassword( $plain_password );

	/**
	 *
	 * @return string
	 */
	public function getName();

	/**
	 *
	 * @param string[] $role_ids
	 */
	public function setRoles( array $role_ids );

	/**
	 *
	 * @return Auth_Role_Interface[]
	 */
	public function getRoles();

	/**
	 *
	 * @param string $role_id
	 *
	 * @return bool
	 */
	public function hasRole( $role_id );

	/**
	 *
	 * @param string $privilege
	 * @param mixed  $value
	 *
	 * @return bool
	 */
	public function hasPrivilege( $privilege, $value );

	/**
	 * @param string $privilege
	 *
	 * @return array
	 */
	public function getPrivilegeValues( $privilege );


}