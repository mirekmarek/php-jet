<?php
/**
 *
 *
 *
 * @see Factory
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Auth
 * @subpackage Auth_Factory
 */
namespace Jet;

class Auth_Factory extends Factory {
	
	const DEFAULT_USER_CLASS = 'Jet\\Auth_User_Default';
	const DEFAULT_USER_ROLES_CLASS = 'Jet\\Auth_User_Roles';
	const DEFAULT_ROLE_CLASS = 'Jet\\Auth_Role_Default';
	const DEFAULT_PRIVILEGE_CLASS = 'Jet\\Auth_Role_Privilege_Default';

	/**
	 * @return string
	 */
	public static function getUserClassName() {
		return static::getClassName( static::DEFAULT_USER_CLASS );
	}

	/**
	 * Returns instance of Auth User class @see Factory
	 *
	 * @param string|null $login
	 * @param string|null $password
	 *
	 * @return Auth_User_Abstract
	 */
	public static function getUserInstance( $login=null, $password=null ) {
		$class_name =  static::getUserClassName();
		$instance = new $class_name( $login, $password );

		static::checkInstance(static::DEFAULT_USER_CLASS, $instance);
		return $instance;
	}

	/**
	 * @return string
	 */
	public static function getRoleClassName() {
		return static::getClassName( static::DEFAULT_ROLE_CLASS );
	}

	/**
	 *
	 * @return Auth_Role_ID_Abstract
	 */
	public static function getRoleIDInstance() {
		$class_name =  static::getRoleClassName();

		/**
		 * @var Auth_Role_Abstract $class_name
		 */
		return $class_name::getEmptyIDInstance();
	}


	/**
	 * Returns instance of Auth Role class @see Factory
	 *
	 * @return Auth_Role_Abstract
	 */
	public static function getRoleInstance() {
		$class_name =  static::getRoleClassName();
		$instance = new $class_name();
		static::checkInstance(static::DEFAULT_ROLE_CLASS, $instance);
		return $instance;
	}

	/**
	 * Returns instance of Auth Privilege class
	 * @see Factory
	 *
	 * @param string $privilege
	 * @param mixed[] $values
	 *
	 * @return Auth_Role_Privilege_Abstract
	 */
	public static function getPrivilegeInstance( $privilege='', array $values=array() ) {
		$class_name =  static::getClassName( static::DEFAULT_PRIVILEGE_CLASS );
		$instance = new $class_name( $privilege, $values );
		static::checkInstance(static::DEFAULT_PRIVILEGE_CLASS, $instance);
		return $instance;
	}

	/**
	 * @see Factory
	 *
	 * @param string $class_name
	 */
	public static function setUserClass( $class_name ) {
		static::setClassName(static::DEFAULT_USER_CLASS, $class_name);
	}

	/**
	 * @see Factory
	 *
	 * @param string $class_name
	 */
	public static function setUserRolesClass( $class_name ) {
		static::setClassName(static::DEFAULT_USER_ROLES_CLASS, $class_name);
	}


	/**
	 * @see Factory
	 *
	 * @param string $class_name
	 */
	public static function setRoleClass( $class_name ) {
		static::setClassName(static::DEFAULT_ROLE_CLASS, $class_name);
	}

	/**
	 * @see Factory
	 *
	 * @param string $class_name
	 */
	public static function setPrivilegeClass( $class_name ) {
		static::setClassName(static::DEFAULT_PRIVILEGE_CLASS, $class_name);
	}

}