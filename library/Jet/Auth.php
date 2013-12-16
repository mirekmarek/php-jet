<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Auth
 */

namespace Jet;

class Auth extends Object {

	/**
	 * Privilege to sites/page
	 */
	const PRIVILEGE_VISIT_PAGE = "visit_page";

	/**
	 * Privilege for modules/actions
	 */
	const PRIVILEGE_MODULE_ACTION = "module_action";

	/**
	 * Auth module instance
	 *
	 * @var Auth_ManagerModule_Abstract
	 */
	protected static $current_auth_manager_module_instance;

	/**
	 * List of Auth modules instances
	 *
	 * @var Auth_ManagerModule_Abstract[]
	 */
	protected static $auth_manager_module_instances = array();

	/**
	 * Initialize new Auth module
	 *
	 * @param Mvc_Router_Abstract $router
	 *
	 * @throws Auth_ManagerModule_Exception
	 */
	public static function initialize( Mvc_Router_Abstract $router ) {

		static::$current_auth_manager_module_instance = $router->getUIManagerModuleInstance()->getAuthManagerModuleInstance();
		static::$auth_manager_module_instances[] = static::$current_auth_manager_module_instance;



		if( !static::$current_auth_manager_module_instance instanceof Auth_ManagerModule_Abstract ) {
			$module_name = static::$current_auth_manager_module_instance->getModuleInfo()->getName();

			throw new Auth_ManagerModule_Exception(
					"Auth manager module '{$module_name}' instance must be subclass of Auth_ManagerModule_Abstract",
					Auth_ManagerModule_Exception::CODE_INVALID_AUTH_MANAGER_MODULE_CLASS
				);
		}

		$router->setAuthManagerModuleInstance( static::$current_auth_manager_module_instance );
	}

	/**
	 * Close current Auth module and switch to previous if available
	 *
	 * @static
	 *
	 */
	public static function shutdown() {
		if(!static::$auth_manager_module_instances) {
			return;
		}
		unset(static::$auth_manager_module_instances[count(static::$auth_manager_module_instances)-1]);

		if(static::$auth_manager_module_instances) {
			static::$current_auth_manager_module_instance = static::$auth_manager_module_instances[count(static::$auth_manager_module_instances)-1];
		} else {
			static::$current_auth_manager_module_instance = null;
		}
	}

	/**
	 * Get instance of current Auth module
	 *
	 * @return Auth_ManagerModule_Abstract
	 */
	public static function getCurrentAuthManagerModuleInstance() {
		return static::$current_auth_manager_module_instance;
	}

	/**
	 * Authenticates given user and returns true if given username and password is OK
	 *
	 * @param string $login
	 * @param string $password
	 *
	 * @return bool
	 */
	public static function login( $login, $password ) {
		if(!static::$current_auth_manager_module_instance->login( $login, $password )) {
			static::logEvent("login", array("login"=>$login), "Login successful. Login: '{$login}'");
			return false;
		} else {
			$user_ID = static::getCurrentUser()->getID();
			static::logEvent("login_failed", array("login"=>$login, "user_ID"=>$user_ID), "Login failed. Login: '{$login}', User ID: '{$user_ID}'");
			return true;
		}
	}

	/**
	 * Logout current user
	 *
	 * @return mixed
	 */
	public static function logout() {
		$user = static::getCurrentUser();
		if($user) {
			$login = $user->getLogin();
			$user_ID = $user->getID();
			static::logEvent("logout", array("login"=>$login, "user_ID"=>$user_ID), "Logout successful. Login: '{$login}', User ID: '{$user_ID}'");
		}

		return static::$current_auth_manager_module_instance->logout();
	}

	/**
	 * Return current user data or FALSE
	 *
	 * @return Auth_User_Abstract|bool
	 */
	public static function getCurrentUser() {
		if(!static::$current_auth_manager_module_instance) {
			return null;
		}
		return static::$current_auth_manager_module_instance->getCurrentUser();
	}

	/**
	 * Is current user assigned to given role?
	 *
	 * @param string $role_ID
	 * @return bool
	 */
	public static function getCurrentUserHasRole( $role_ID ) {
		return static::$current_auth_manager_module_instance->getCurrentUser()->getHasRole( $role_ID );
	}

	/**
	 * Does current user have given privilege?
	 *
	 * @param string $privilege
	 * @param mixed $value
	 * @param bool $log_if_false (optional, default: true)
	 *
	 * @return bool
	 */
	public static function getCurrentUserHasPrivilege( $privilege, $value, $log_if_false=true ) {
		$res = static::$current_auth_manager_module_instance->getCurrentUserHasPrivilege( $privilege, $value, $log_if_false );

		if(!$res && $log_if_false) {
			$login = "unknown";
			$user_ID = "unknown";


			$user = static::getCurrentUser();
			if($user) {
				$login = $user->getLogin();
				$user_ID = $user->getID();
			}


			static::logEvent("privilege_access_denied",
				array(
					"privilege"=>$privilege,
					"value"=>$value
				),
				"Privilege access denied. Login: '{$login}', User ID: '{$user_ID}', Privilege: '{$privilege}', Value: '{$value}'"
			);
		}

		return $res;
	}

	/**
	 * Get new role data
	 *
	 * @return Auth_Role_Abstract
	 */
	public static function getNewRole() {
		return static::$current_auth_manager_module_instance->getNewRole();
	}

	/**
	 * Get role data by it's ID or NULL if not found
	 *
	 * @param string $ID
	 *
	 * @return Auth_Role_Abstract|null
	 */
	public static function getRole( $ID ) {
		return static::$current_auth_manager_module_instance->getRole( $ID );
	}

	/**
	 * Get list of all roles
	 *
	 * @return Auth_Role_Abstract[]
	 */
	public static function getRolesList() {
		return static::$current_auth_manager_module_instance->getRolesList();
	}

	/**
	 *
	 * @return DataModel_Fetch_Data_Assoc
	 */
	public static function getRolesListAsData() {
		return static::$current_auth_manager_module_instance->getRolesListAsData();
	}


	/**
	 * Get new user data
	 *
	 * @return Auth_User_Abstract
	 */
	public static function getNewUser() {
		return static::$current_auth_manager_module_instance->getNewUser();
	}

	/**
	 * Get user data by ID or NULL if not found
	 *
	 * @param string $ID
	 *
	 * @return Auth_User_Abstract|null
	 */
	public static function getUser( $ID ) {
		return static::$current_auth_manager_module_instance->getUser( $ID );
	}

	/**
	 * Get list of users
	 *
	 * @param string $role_ID
	 *
	 * @return Auth_User_Abstract[]
	 */
	public static function getUsersList( $role_ID=null ) {
		return static::$current_auth_manager_module_instance->getUsersList( $role_ID );
	}

	/**
	 *
	 * @param string $role_ID
	 *
	 * @return DataModel_Fetch_Data_Assoc
	 */
	public static function getUsersListAsData( $role_ID=null ) {
		return static::$current_auth_manager_module_instance->getUsersListAsData( $role_ID );
	}

	/**
	 * Log auth event
	 *
	 * @param string $event
	 * @param mixed $event_data
	 * @param string $event_txt
	 * @param string $user_ID (optional; default: null = current user ID)
	 * @param string $user_login (optional; default: null = current user login)
	 */
	public static function logEvent( $event, $event_data, $event_txt, $user_ID=null, $user_login=null ) {
		static::$current_auth_manager_module_instance->logEvent( $event, $event_data, $event_txt, $user_ID, $user_login );
	}

	/**
	 * Get list of available privileges
	 *
	 * @param bool $get_available_values_list (optional, default: false)
	 *
	 * @return Auth_Role_Privilege_AvailablePrivilegesListItem[]
	 */
	public static function getAvailablePrivilegesList( $get_available_values_list=false ) {
		return static::$current_auth_manager_module_instance->getAvailablePrivilegesList( $get_available_values_list );
	}

	/**
	 * Get list of available privilege values or false if the privilege does not exist
	 *
	 * @param string $privilege
	 *
	 * @return Data_Tree_Forest
	 */
	public static function getAvailablePrivilegeValuesList( $privilege ) {
		return static::$current_auth_manager_module_instance->getAvailablePrivilegeValuesList( $privilege );
	}

	/**
	 * Returns password strength (value in range 0-100, where 0 = unsafe, 100 = very safe)
	 *
	 * @param string $password
	 *
	 * @return int
	 */
	public static function getPasswordStrength( $password ) {
		if(!static::$current_auth_manager_module_instance) {
			return Auth_ManagerModule_Abstract::getPasswordStrength( $password );
		}
		return static::$current_auth_manager_module_instance->getPasswordStrength( $password );
	}
}