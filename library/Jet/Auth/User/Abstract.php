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

/**
 * Class Auth_User_Abstract
 *
 * @JetFactory:class = 'Jet\\Auth_Factory'
 * @JetFactory:method = 'getUserInstance'
 * @JetFactory:mandatory_parent_class = 'Jet\\Auth_User_Abstract'
 *
 * @JetDataModel:name = 'Jet_Auth_User'
 */
abstract class Auth_User_Abstract extends DataModel {

	/**
	 * @abstract
	 * @param string $login
	 * @param string $password
	 */
	abstract public function initNew( $login, $password );

	/**
	 * @abstract
	 * @return string
	 */
	abstract public function getLogin();


	/**
	 * @return boolean
	 */
	abstract public function getIsSuperuser();

	/**
	 * @param boolean $is_superuser
	 */
	abstract public function setIsSuperuser($is_superuser);

	/**
	 * @abstract
	 *
	 * @param $login
	 *
	 * @return bool
	 */
	abstract public function getLoginExists( $login );

	/**
	 * @abstract
	 * @param string $login
	 */
	abstract public function setLogin( $login );

	/**
	 * @abstract
	 * @return string
	 */
	abstract public function getPassword();

	/**
	 * @abstract
	 * @param string $password
	 */
	abstract public function setPassword( $password );

	/**
	 * @abstract
	 * @param string $password
	 * @return string
	 */
	abstract public function encryptPassword( $password );

	/**
	 * @abstract
	 * @return string
	 */
	abstract public function getEmail();

	/**
	 * @abstract
	 * @param string $email
	 */
	abstract public function setEmail( $email );

	/**
	 * @abstract
	 * @return Locale
	 */
	abstract public function getLocale();

	/**
	 * @abstract
	 * @param Locale|string $locale
	 */
	abstract public function setLocale( $locale );

	/**
	 * @abstract
	 * @return string
	 */
	abstract public function getFirstName();

	/**
	 * @abstract
	 * @param string $first_name
	 */
	abstract public function setFirstName( $first_name );

	/**
	 * @abstract
	 * @return string
	 */
	abstract public function getSurname();

	/**
	 * @abstract
	 * @param string $surname
	 */
	abstract public function setSurname($surname);

	/**
	 * @abstract
	 * @return string
	 */
	abstract public function getName();

	/**
	 * @abstract
	 * @return string
	 */
	abstract public function getDescription();

	/**
	 * @abstract
	 * @param string $description
	 */
	abstract public function setDescription($description);

	/**
	 * @abstract
	 * @return bool
	 */
	abstract public function getPasswordIsValid();

	/**
	 * @abstract
	 * @param bool $password_is_valid
	 */
	abstract public function setPasswordIsValid( $password_is_valid );

	/**
	 * @abstract
	 * @return DateTime
	 */
	abstract public function getPasswordIsValidTill();

	/**
	 * @abstract
	 * @param string|DateTime $password_is_valid_till
	 * @return mixed
	 */
	abstract public function setPasswordIsValidTill( $password_is_valid_till );

	/**
	 * @abstract
	 * @return bool
	 */
	abstract public function getIsBlocked();

	/**
	 *
	 * @abstract
	 * @return DateTime|null
	 */
	abstract public function getIsBlockedTill();

	/**
	 * @abstract
	 * @param DateTime|string|null $till
	 */
	abstract public function block( $till=null );

	/**
	 * @abstract
	 *
	 */
	abstract public function unBlock();


	/**
	 * @abstract
	 * @return bool
	 */
	abstract public function getIsActivated();

	/**
	 * @abstract
	 *
	 * @param string $user_activation_hash (optional)
	 * @return bool
	 */
	abstract public function activate( $user_activation_hash=null );


	/**
	 * @abstract
	 *
	 * @return string
	 */
	abstract public function getActivationHash();

	/**
	 * @abstract
	 *
	 * @param string $user_activation_hash
	 */
	abstract public function setActivationHash($user_activation_hash);

	/**
	 * @abstract
	 *
	 * @param string[] $roles_IDs
	 */
	abstract public function setRoles( array $roles_IDs );

	/**
	 * @abstract
	 * @return Auth_Role_Abstract[]
	 */
	abstract public function getRoles();

	/**
	 * @abstract
	 *
	 * @param string $role_ID
	 * @return bool
	 */
	abstract public function getHasRole( $role_ID );

	/**
	 * @abstract
	 *
	 * @param string $privilege
	 * @param mixed $value
	 * @return bool
	 */
	abstract public function getHasPrivilege( $privilege, $value );

	/**
	 * @param $privilege
	 *
	 * @return array
	 */
	abstract public function getPrivilegeValues($privilege);


	/**
	 * @abstract
	 *
	 * @param string $role_ID (optional)
	 * @return Auth_User_Abstract[]
	 */
	abstract public function getUsersList( $role_ID=null );

	/**
	 * @abstract
	 *
	 * @param string $role_ID (optional)
	 * @return DataModel_Fetch_Data_Assoc
	 */
	abstract public function getUsersListAsData( $role_ID=null );

	/**
	 * @abstract
	 *
	 * @param string $login
	 * @param string $password
	 *
	 * @return Auth_User_Abstract|null
	 */
	abstract public function getByIdentity(  $login, $password  );

	/**
	 * @abstract
	 *
	 * @param string $login
	 * @return Auth_User_Abstract|null
	 */
	abstract public function getGetByLogin(  $login  );

	/**
	 * @param string $form_name
	 *
	 * @return Form
	 */
	abstract public function getSimpleForm( $form_name='' );
}