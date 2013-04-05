<?php
/**
 *
 *
 *
 * Abstract users, roles and privileges management class implementation
 *
 * @see \Jet\Auth
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Auth
 * @subpackage Auth_ManagerModule
 */

namespace Jet;

abstract class Auth_ManagerModule_Abstract extends Mvc_UIManagerModule_Abstract {

	/**
	 * Returns Auth module instance
	 *
	 * @return Auth_ManagerModule_Abstract
	 */
	public function getAuthManagerModuleInstance() {
		return $this;
	}

	/**
	 * Returns dispatch queue (example: show login dialog )
	 *
	 * @return Mvc_Dispatcher_Queue_Item[]
	 */
	public function getDispatchQueue() {
		$queue = new Mvc_Dispatcher_Queue();

		$queue->addItem(
				new Mvc_Dispatcher_Queue_Item( $this->module_info->getName(), "", "login" )
			);

		return $queue;
	}

	/**
	 * Returns true if authentication (for example login dialog...) is required
	 *
	 * @return bool
	 */
	abstract public function getAuthenticationRequired();

	/**
	 * Authenticates given user and returns true if given username and password is OK
	 *
	 * @param string $login
	 * @param string $password
	 *
	 * @return bool
	 */
	abstract public function login( $login, $password );

	/**
	 * Logout current user
	 *
	 * @return mixed
	 */
	abstract public function logout();

	/**
	 * Return current user data or FALSE
	 *
	 * @return Auth_User_Abstract|bool
	 */
	abstract public function getCurrentUser();

	/**
	 * Does current user have given privilege?
	 *
	 * @param string $privilege
	 * @param mixed $value
	 * @param bool $log_if_false (optional, default: true)
	 *
	 * @return bool
	 */
	abstract public function getCurrentUserHasPrivilege( $privilege, $value, $log_if_false=true );


	/**
	 * Log auth event
	 *
	 * @abstract
	 *
	 * @param string $event
	 * @param mixed $event_data
	 * @param string $event_txt
	 * @param string $user_ID (optional; default: null = current user ID)
	 * @param string $user_login (optional; default: null = current user login)
	 * @return void
	 */
	abstract public function logEvent( $event, $event_data, $event_txt, $user_ID=null, $user_login=null );


	/**
	 * Get list of available privileges
	 *
	 * @param bool $get_available_values_list (optional, default: false)
	 *
	 * @return Auth_Role_Privilege_AvailablePrivilegesListItem[]
	 */
	abstract public function getAvailablePrivilegesList( $get_available_values_list=false );

	/**
	 * Get list of available privilege values or false if the privilege does not exist
	 *
	 * @param string $privilege
	 *
	 * @return Data_Tree_Forest
	 */
	abstract public function getAvailablePrivilegeValuesList( $privilege );

	/**
	 * Get new role data
	 *
	 * @return Auth_Role_Abstract
	 */
	public static function getNewRole() {
		$new = Auth_Factory::getRoleInstance();
		$new->initNewObject();

		return $new;
	}

	/**
	 * Get role data by it's ID or NULL if not found
	 *
	 * @param string $ID
	 *
	 * @return Auth_Role_Abstract|null
	 */
	public static function getRole( $ID ) {
		$role_instance = Auth_Factory::getRoleInstance();
		return $role_instance->load( $role_instance->getEmptyIDInstance()->createID($ID) );
	}

	/**
	 * Get list of all roles
	 *
	 * @return Auth_Role_Abstract[]
	 */
	public static function getRolesList() {
		return Auth_Factory::getRoleInstance()->getRolesList();
	}

	/**
	 * Get roles list as DataModel_Fetch_Data_Assoc instance
	 *
	 * @return DataModel_Fetch_Data_Assoc
	 */
	public static function getRolesListAsData() {
		return Auth_Factory::getRoleInstance()->getRolesListAsData();
	}

	/**
	 * Get new user data
	 *
	 * @return Auth_User_Abstract
	 */
	public static function getNewUser() {
		$new = Auth_Factory::getUserInstance();
		$new->initNewObject();

		return $new;
	}

	/**
	 * Get user data by ID or NULL if not found
	 *
	 * @param string $ID
	 *
	 * @return Auth_User_Abstract|null
	 */
	public static function getUser( $ID ) {
		$user_instance = Auth_Factory::getUserInstance();
		return $user_instance->load( $user_instance->getEmptyIDInstance()->createID($ID) );
	}

	/**
	 * Get list of users
	 *
	 * @param string $role_ID
	 * @return Auth_User_Abstract[]
	 */
	public static function getUsersList( $role_ID=null ) {
		return Auth_Factory::getUserInstance()->getUsersList( $role_ID );
	}

	/**
	 * Get users list as DataModel_Fetch_Data_Assoc instance
	 *
	 * @param string $role_ID
	 * @return DataModel_Fetch_Data_Assoc
	 */
	public static function getUsersListAsData( $role_ID=null ) {
		return Auth_Factory::getUserInstance()->getUsersListAsData( $role_ID );
	}

	/**
	 * Returns password strength (value in range 0-100, where 0 = unsafe, 100 = very safe)
	 *
	 *
	 * @param string $password
	 *
	 * @return int
	 */
	public static function getPasswordStrength( $password ) {

		$score = 0;

		if(strlen($password)<=1 ) {
			return $score;
		}

		$score = 10;
		if(strlen($password)<=4) {
			return $score;
		}

		if (strlen($password) >= 8) {
			$score = $score + 10;
                }

                if (strlen($password) >= 10) {
	                $score = $score + 10;
                }

		if (preg_match("/[a-z]/", $password) && preg_match("/[A-Z]/", $password)) {
			$score = $score + 10;
		}
		if (preg_match("/[0-9]/", $password)) {
			$score = $score + 10;
		}
		if (preg_match("/[^a-zA-Z0-9][^a-zA-Z0-9]+/", $password)) {
			$score = $score + 20;
		}


		$chars = array();
		$password_len = strlen($password);

		for($c=0;$c<$password_len;$c++) {
			$char = ord($password[$c]);

			if(!isset($chars[$char])) {
				$chars[$char] = 0;
			}

			$chars[$char]++;
		}

		arsort($chars);

		$most_common_char_count = 0;
		foreach($chars as $most_common_char_count) {
			break;
		}

		if($most_common_char_count==1) {
			$score = $score + 30;
		}

		if($most_common_char_count==2) {
			$score = $score + 20;
		}

		return $score;
	}
}