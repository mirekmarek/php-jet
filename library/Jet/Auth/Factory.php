<?php
/**
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

class Auth_Factory {

	/**
	 *
	 * @param bool $soft_mode (optional, default:false)
	 *
	 * @return Auth_Config_Abstract
	 */
	public static function getConfigInstance( $soft_mode=false ) {
		$class_name =  JET_AUTH_CONFIG_CLASS;
		return new $class_name($soft_mode);

	}

	/**
	 * Returns instance of Auth User class
	 *
	 * @param string|null $login
	 * @param string|null $password
	 *
	 * @return Auth_User_Abstract
	 */
	public static function getUserInstance( $login=null, $password=null ) {
		$class_name =  JET_AUTH_USER_CLASS;
		return new $class_name( $login, $password );
	}

	/**
	 * Returns instance of Auth Role class
	 *
	 * @return Auth_Role_Abstract
	 */
	public static function getRoleInstance() {
		$class_name =  JET_AUTH_ROLE_CLASS;
		return new $class_name();
	}

	/**
	 * Returns instance of Auth Privilege class
	 *
	 * @param string $privilege
	 * @param mixed[] $values
	 *
	 * @return Auth_Role_Privilege_Abstract
	 */
	public static function getPrivilegeInstance( $privilege='', array $values= []) {
		$class_name =  JET_AUTH_ROLE_PRIVILEGE_CLASS;
		return new $class_name( $privilege, $values );
	}

}