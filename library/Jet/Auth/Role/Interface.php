<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Interface Auth_Role_Interface
 * @package Jet
 */
interface Auth_Role_Interface extends BaseObject_Interface {

    /**
     * @param string|int $id
     *
     * @return Auth_Role_Interface
     */
    public static function get( $id );

	/**
	 * @return Auth_Role_Interface[]
	 */
	public static function getList();

    /**
	 * @return string
	 */
	public function toString();

    /**
     * @return string
     */
    public function getId();

	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @param string $name
	 */
	public function setName($name);

	/**
	 * @return string
	 */
	public function getDescription();

	/**
	 * @param string $description
	 */
	public function setDescription($description);

	/**
	 * @return Auth_User_Interface[]
	 */
	public function getUsers();


	/**
	 * @return Auth_Role_Privilege_Interface[]
	 */
	public function getPrivileges();

	/**
	 * Returns privilege values or empty array if the role does not have the privilege
	 *
	 * @param string $privilege
	 * @return array
	 */
	public function getPrivilegeValues( $privilege );

	/**
	 * Data format:
	 *
	 * <code>
	 * array(
	 *      'privilege' => array('value1', 'value2')
	 * )
	 * </code>
	 *
	 * @param array $privileges
	 */
	public function setPrivileges(array $privileges);

	/**
	 * Example:
	 *
	 * privilege: save_object
	 * values: object_id_1,object_id_2, object_id_N
	 *
	 *
	 * @param string $privilege
	 * @param array $values
	 */
	public function setPrivilege( $privilege, array $values );

	/**
	 * Example:
	 *
	 * privilege: save_object
	 *
	 * @param string $privilege
	 */
	public function removePrivilege( $privilege );

	/**
	 * Example:
	 *
	 * privilege: save_object
	 * values: object_id_1
	 *
	 * @param string $privilege
	 * @param mixed $value
	 * @return bool
	 */
	public function getHasPrivilege( $privilege, $value );



}