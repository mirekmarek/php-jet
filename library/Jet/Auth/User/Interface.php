<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	 * @return static|null
	 */
	public static function get( string|int $id ): static|null;

	/**
	 *
	 * @param string|null $role_id (optional)
	 *
	 * @return Auth_User_Interface[]
	 */
	public static function getList( string|null $role_id = null ): iterable;

	/**
	 *
	 * @param string $username
	 * @param string $password
	 *
	 * @return static|null
	 */
	public static function getByIdentity( string $username, string $password ): static|null;

	/**
	 *
	 * @param string $username
	 *
	 * @return Auth_User_Interface|null
	 */
	public static function getGetByUsername( string $username ): Auth_User_Interface|null;

	/**
	 * @return string|int
	 */
	public function getId(): string|int;


	/**
	 * @return string
	 */
	public function getUsername(): string;

	/**
	 * @param string $username
	 */
	public function setUsername( string $username ): void;

	/**
	 * @param string $username
	 *
	 * @return bool
	 */
	public function usernameExists( string $username ): bool;

	/**
	 * @param string $password
	 */
	public function setPassword( string $password ): void;

	/**
	 * @param string $password
	 *
	 * @return string
	 */
	public function encryptPassword( string $password ): string;

	/**
	 * @param string $plain_password
	 *
	 * @return bool
	 */
	public function verifyPassword( string $plain_password ): bool;

	/**
	 *
	 * @return string
	 */
	public function getName(): string;

	/**
	 *
	 * @param string[] $role_ids
	 */
	public function setRoles( array $role_ids ): void;

	/**
	 *
	 * @return Auth_Role_Interface[]
	 */
	public function getRoles(): array;

	/**
	 *
	 * @param string $role_id
	 *
	 * @return bool
	 */
	public function hasRole( string $role_id ): bool;

	/**
	 *
	 * @param string $privilege
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function hasPrivilege( string $privilege, mixed $value ): bool;

	/**
	 * @param string $privilege
	 *
	 * @return array
	 */
	public function getPrivilegeValues( string $privilege ): array;


}