<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Interface Auth_User_Interface
 * @package Jet
 */
interface Auth_User_Interface extends BaseObject_Interface {

    /**
     * @param string $id
     *
     * @return Auth_User_Interface
     */
    public static function get($id );

	/**
	 * @return string
	 */
	public function getId();


	/**
	 * @return bool
	 */
	public function getIsSuperuser();

	/**
	 * @param bool $is_superuser
	 */
	public function setIsSuperuser($is_superuser);


	/**
	 * @return string
	 */
	public function getUsername();

	/**
	 * @param string $username
	 *
	 * @return bool
	 */
	public function usernameExists($username );

	/**
	 * @param string $username
	 */
	public function setUsername($username );

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
	 * @param string $password
	 * @return bool
	 */
	public function verifyPasswordStrength( $password );

	/**
	 *
	 * @return string
	 */
	public function getEmail();

	/**
	 *
	 * @param string $email
	 */
	public function setEmail( $email );

	/**
	 *
	 * @return Locale
	 */
	public function getLocale();

	/**
	 *
	 * @param Locale|string $locale
	 */
	public function setLocale( $locale );

	/**
	 *
	 * @return string
	 */
	public function getFirstName();

	/**
	 *
	 * @param string $first_name
	 */
	public function setFirstName( $first_name );

	/**
	 *
	 * @return string
	 */
	public function getSurname();

	/**
	 *
	 * @param string $surname
	 */
	public function setSurname($surname);

	/**
	 *
	 * @return string
	 */
	public function getName();

	/**
	 *
	 * @return string
	 */
	public function getDescription();

	/**
	 *
	 * @param string $description
	 */
	public function setDescription($description);

	/**
	 *
	 * @return bool
	 */
	public function getPasswordIsValid();

	/**
	 *
	 * @param bool $password_is_valid
	 */
	public function setPasswordIsValid( $password_is_valid );

	/**
	 *
	 * @return Data_DateTime
	 */
	public function getPasswordIsValidTill();

	/**
	 *
	 * @param string|Data_DateTime $password_is_valid_till
	 * @return mixed
	 */
	public function setPasswordIsValidTill( $password_is_valid_till );

	/**
	 *
	 * @return bool
	 */
	public function isBlocked();

	/**
	 *
	 * @return Data_DateTime|null
	 */
	public function isBlockedTill();

	/**
	 *
	 * @param Data_DateTime|string|null $till
	 */
	public function block( $till=null );

	/**
	 *
	 */
	public function unBlock();


	/**
	 * @return bool
	 */
	public function isActivated();

	/**
	 *
	 * @param string $user_activation_hash (optional)
	 * @return bool
	 */
	public function activate( $user_activation_hash=null );


	/**
	 *
	 * @return string
	 */
	public function getActivationHash();

	/**
	 *
	 * @param string $user_activation_hash
	 */
	public function setActivationHash($user_activation_hash);

	/**
	 *
	 * @param string[] $roles_ids
	 */
	public function setRoles( array $roles_ids );

	/**
	 *
	 * @return Auth_Role_Interface[]
	 */
	public function getRoles();

	/**
	 *
	 * @param string $role_id
	 * @return bool
	 */
	public function hasRole($role_id );

	/**
	 *
	 * @param string $privilege
	 * @param mixed $value
	 * @return bool
	 */
	public function hasPrivilege($privilege, $value );

	/**
	 * @param $privilege
	 *
	 * @return array
	 */
	public function getPrivilegeValues($privilege);


	/**
	 *
	 * @param string $role_id (optional)
	 * @return Auth_User_Interface[]
	 */
	public static function getList($role_id=null );

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
	 * @return Auth_User_Interface|null
	 */
	public static function getGetByUsername( $username );


}