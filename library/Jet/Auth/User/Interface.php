<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Auth
 * @subpackage Auth_User
 */
namespace Jet;

interface Auth_User_Interface extends Object_Interface {

    /**
     * @param string $ID
     *
     * @return Auth_User_Interface
     */
    public static function get( $ID );

	/**
	 * @abstract
	 * @return string
	 */
	public function getLogin();

    /**
     * @return string
     */
    public function getID();


	/**
	 * @return boolean
	 */
	public function getIsSuperuser();

	/**
	 * @param boolean $is_superuser
	 */
	public function setIsSuperuser($is_superuser);

	/**
	 * @abstract
	 *
	 * @param $login
	 *
	 * @return bool
	 */
	public function getLoginExists( $login );

	/**
	 * @abstract
	 * @param string $login
	 */
	public function setLogin( $login );

	/**
	 * @abstract
	 * @return string
	 */
	public function getPassword();

	/**
	 * @abstract
	 * @param string $password
	 */
	public function setPassword( $password );

	/**
	 * @abstract
	 * @param string $password
	 * @return string
	 */
	public function encryptPassword( $password );

	/**
	 * @abstract
	 * @return string
	 */
	public function getEmail();

	/**
	 * @abstract
	 * @param string $email
	 */
	public function setEmail( $email );

	/**
	 * @abstract
	 * @return Locale
	 */
	public function getLocale();

	/**
	 * @abstract
	 * @param Locale|string $locale
	 */
	public function setLocale( $locale );

	/**
	 * @abstract
	 * @return string
	 */
	public function getFirstName();

	/**
	 * @abstract
	 * @param string $first_name
	 */
	public function setFirstName( $first_name );

	/**
	 * @abstract
	 * @return string
	 */
	public function getSurname();

	/**
	 * @abstract
	 * @param string $surname
	 */
	public function setSurname($surname);

	/**
	 * @abstract
	 * @return string
	 */
	public function getName();

	/**
	 * @abstract
	 * @return string
	 */
	public function getDescription();

	/**
	 * @abstract
	 * @param string $description
	 */
	public function setDescription($description);

	/**
	 * @abstract
	 * @return bool
	 */
	public function getPasswordIsValid();

	/**
	 * @abstract
	 * @param bool $password_is_valid
	 */
	public function setPasswordIsValid( $password_is_valid );

	/**
	 * @abstract
	 * @return Data_DateTime
	 */
	public function getPasswordIsValidTill();

	/**
	 * @abstract
	 * @param string|Data_DateTime $password_is_valid_till
	 * @return mixed
	 */
	public function setPasswordIsValidTill( $password_is_valid_till );

	/**
	 * @abstract
	 * @return bool
	 */
	public function getIsBlocked();

	/**
	 *
	 * @abstract
	 * @return Data_DateTime|null
	 */
	public function getIsBlockedTill();

	/**
	 * @abstract
	 * @param Data_DateTime|string|null $till
	 */
	public function block( $till=null );

	/**
	 * @abstract
	 *
	 */
	public function unBlock();


	/**
	 * @abstract
	 * @return bool
	 */
	public function getIsActivated();

	/**
	 * @abstract
	 *
	 * @param string $user_activation_hash (optional)
	 * @return bool
	 */
	public function activate( $user_activation_hash=null );


	/**
	 * @abstract
	 *
	 * @return string
	 */
	public function getActivationHash();

	/**
	 * @abstract
	 *
	 * @param string $user_activation_hash
	 */
	public function setActivationHash($user_activation_hash);

	/**
	 * @abstract
	 *
	 * @param string[] $roles_IDs
	 */
	public function setRoles( array $roles_IDs );

	/**
	 * @abstract
	 * @return Auth_Role_Interface[]
	 */
	public function getRoles();

	/**
	 * @abstract
	 *
	 * @param string $role_ID
	 * @return bool
	 */
	public function getHasRole( $role_ID );

	/**
	 * @abstract
	 *
	 * @param string $privilege
	 * @param mixed $value
	 * @return bool
	 */
	public function getHasPrivilege( $privilege, $value );

	/**
	 * @param $privilege
	 *
	 * @return array
	 */
	public function getPrivilegeValues($privilege);


	/**
	 * @abstract
	 *
	 * @param string $role_ID (optional)
	 * @return Auth_User_Interface[]
	 */
	public function getUsersList( $role_ID=null );

	/**
	 * @abstract
	 *
	 * @param string $role_ID (optional)
	 * @return DataModel_Fetch_Data_Assoc
	 */
	public function getUsersListAsData( $role_ID=null );

	/**
	 * @abstract
	 *
	 * @param string $login
	 * @param string $password
	 *
	 * @return Auth_User_Interface|null
	 */
	public function getByIdentity(  $login, $password  );

	/**
	 * @abstract
	 *
	 * @param string $login
	 * @return Auth_User_Interface|null
	 */
	public function getGetByLogin(  $login  );

	/**
	 * @param string $form_name
	 *
	 * @return Form
	 */
	public function getSimpleForm( $form_name='' );


}