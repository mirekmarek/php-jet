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
	const PRIVILEGE_VISIT_PAGE = 'visit_page';

	/**
	 * Privilege for modules/actions
	 */
	const PRIVILEGE_MODULE_ACTION = 'module_action';

    /**
     * @var Auth_Config_Abstract
     */
    protected static $config;

	/**
	 * Auth module instance
	 *
	 * @var Auth_ControllerModule_Abstract
	 */
	protected static $current_auth_controller;

    /**
     * @param Auth_Config_Abstract $config
     */
    public static function setConfig( Auth_Config_Abstract $config)
    {
        self::$config = $config;
    }

    /**
     * @return Auth_Config_Abstract
     */
    public static function getConfig()
    {
        if(!self::$config) {
            self::$config = Auth_Factory::getConfigInstance();
        }

        return self::$config;
    }



    /**
     * @param Auth_ControllerModule_Abstract $current_auth_controller
     */
    public static function setCurrentAuthController( Auth_ControllerModule_Abstract $current_auth_controller)
    {
        self::$current_auth_controller = $current_auth_controller;
    }

	/**
	 * Get instance of current Auth module
	 *
	 * @return Auth_ControllerModule_Abstract
	 */
	public static function getCurrentAuthController()
    {
        if(!static::$current_auth_controller) {
            static::$current_auth_controller = Mvc::getCurrentPage()->getAuthController();
        }

        return static::$current_auth_controller;
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
		if(!static::getCurrentAuthController()->login( $login, $password )) {
			static::logEvent('login_failed', array('login'=>$login), 'Login failed. Login: \''.$login.'\'');
			return false;
		} else {
			$user_ID = static::getCurrentUser()->getID();
			static::logEvent('login', array('login'=>$login, 'user_ID'=>$user_ID), 'Login successful. Login: \''.$login.'\', User ID: \''.$user_ID.'\'');
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
			static::logEvent('logout', array('login'=>$login, 'user_ID'=>$user_ID), 'Logout successful. Login: \''.$login.'\', User ID: \''.$user_ID.'\'');
		}

		return static::getCurrentAuthController()->logout();
	}

	/**
	 * Return current user data or FALSE
	 *
	 * @return Auth_User_Abstract|bool
	 */
	public static function getCurrentUser() {
		if(!static::getCurrentAuthController()) {
			return null;
		}
		return static::getCurrentAuthController()->getCurrentUser();
	}

	/**
	 * Is current user assigned to given role?
	 *
	 * @param string $role_ID
	 * @return bool
	 */
	public static function getCurrentUserHasRole( $role_ID ) {
		return static::getCurrentAuthController()->getCurrentUser()->getHasRole( $role_ID );
	}

	/**
	 * Does current user have given privilege?
	 *
	 * @param string $privilege
	 * @param mixed $value
	 * @param Auth_Role_Privilege_ContextObject_Interface $context_object (optional)
	 * @param bool $log_if_false (optional, default: true)
	 *
	 * @return bool
	 */
	public static function getCurrentUserHasPrivilege( $privilege, $value, Auth_Role_Privilege_ContextObject_Interface $context_object = null, $log_if_false=true ) {
		return static::getCurrentAuthController()->getCurrentUserHasPrivilege( $privilege, $value, $context_object, $log_if_false );
	}

	/**
	 * Get new role data
	 *
	 * @return Auth_Role_Abstract
	 */
	public static function getNewRole() {
		return static::getCurrentAuthController()->getNewRole();
	}

	/**
	 * Get role data by it's ID or NULL if not found
	 *
	 * @param string $ID
	 *
	 * @return Auth_Role_Abstract|null
	 */
	public static function getRole( $ID ) {
		return static::getCurrentAuthController()->getRole( $ID );
	}

	/**
	 * Get list of all roles
	 *
	 * @return Auth_Role_Abstract[]
	 */
	public static function getRolesList() {
		return static::getCurrentAuthController()->getRolesList();
	}

	/**
	 *
	 * @return DataModel_Fetch_Data_Assoc
	 */
	public static function getRolesListAsData() {
		return static::getCurrentAuthController()->getRolesListAsData();
	}


	/**
	 * Get new user data
	 *
	 * @return Auth_User_Abstract
	 */
	public static function getNewUser() {
		return static::getCurrentAuthController()->getNewUser();
	}

	/**
	 * Get user data by ID or NULL if not found
	 *
	 * @param string $ID
	 *
	 * @return Auth_User_Abstract|null
	 */
	public static function getUser( $ID ) {
		return static::getCurrentAuthController()->getUser( $ID );
	}

	/**
	 * Get list of users
	 *
	 * @param string $role_ID
	 *
	 * @return Auth_User_Abstract[]
	 */
	public static function getUsersList( $role_ID=null ) {
		return static::getCurrentAuthController()->getUsersList( $role_ID );
	}

	/**
	 *
	 * @param string $role_ID
	 *
	 * @return DataModel_Fetch_Data_Assoc
	 */
	public static function getUsersListAsData( $role_ID=null ) {
		return static::getCurrentAuthController()->getUsersListAsData( $role_ID );
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
		static::getCurrentAuthController()->logEvent( $event, $event_data, $event_txt, $user_ID, $user_login );
	}

	/**
	 * Get list of available privileges
	 *
	 * @return Auth_Role_Privilege_AvailablePrivilegesListItem[]
	 */
	public static function getAvailablePrivilegesList() {

		if(!static::getCurrentAuthController()) {
			return array();
		}

		return static::getCurrentAuthController()->getAvailablePrivilegesList();
	}

	/**
	 * Get list of available privilege values or false if the privilege does not exist
	 *
	 * @param string $privilege
	 *
	 * @return Data_Tree_Forest
	 */
	public static function getAvailablePrivilegeValuesList( $privilege ) {
		return static::getCurrentAuthController()->getAvailablePrivilegeValuesList( $privilege );
	}

	/**
	 * Returns password strength (value in range 0-100, where 0 = unsafe, 100 = very safe)
	 *
	 * @param string $password
	 *
	 * @return int
	 */
	public static function getPasswordStrength( $password ) {
		if(!static::getCurrentAuthController()) {
			return Auth_ControllerModule_Abstract::getPasswordStrength( $password );
		}
		return static::getCurrentAuthController()->getPasswordStrength( $password );
	}
}