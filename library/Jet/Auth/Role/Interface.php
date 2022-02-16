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
interface Auth_Role_Interface
{

	/**
	 * @param string $id
	 *
	 * @return static|null
	 */
	public static function get( string $id ): static|null;

	/**
	 * @return Auth_Role_Interface[]
	 */
	public static function getList(): iterable;

	/**
	 * @param string $id
	 *
	 * @return bool
	 */
	public static function idExists( string $id ): bool;


	/**
	 * @return string
	 */
	public function getId(): string;

	/**
	 * @return string
	 */
	public function getName(): string;

	/**
	 * @param string $name
	 */
	public function setName( string $name ): void;

	/**
	 * @return string
	 */
	public function getDescription(): string;

	/**
	 * @param string $description
	 */
	public function setDescription( string $description ): void;

	/**
	 * @return Auth_User_Interface[]
	 */
	public function getUsers(): iterable;


	/**
	 * @return Auth_Role_Privilege_Interface[]
	 */
	public function getPrivileges(): array;

	/**
	 *
	 * @param string $privilege
	 *
	 * @return array
	 */
	public function getPrivilegeValues( string $privilege ): array;

	/**
	 * Data format:
	 *
	 * <code>
	 * [
	 *      'privilege' => ['value1', 'value2']
	 * ]
	 * </code>
	 *
	 * @param array $privileges
	 */
	public function setPrivileges( array $privileges ): void;

	/**
	 * Example:
	 *
	 * privilege: can_do_something
	 * values: operation_id_1,operation_id_2, operation_id_M
	 *
	 *
	 * @param string $privilege
	 * @param array $values
	 */
	public function setPrivilege( string $privilege, array $values ) : void;

	/**
	 * Example:
	 *
	 * privilege: save_object
	 *
	 * @param string $privilege
	 */
	public function removePrivilege( string $privilege ): void;

	/**
	 * Example:
	 *
	 * privilege: can_do_something
	 * values: operation_id_1
	 *
	 * @param string $privilege
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function hasPrivilege( string $privilege, mixed $value=null ): bool;

}