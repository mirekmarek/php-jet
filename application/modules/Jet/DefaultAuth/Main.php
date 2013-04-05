<?php
/**
 *
 *
 *
 * Default authentication and authorization module
 *
 * @see Jet\Auth
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule\Jet\DefaultAuth
 */

namespace JetApplicationModule\Jet\DefaultAuth;
use Jet;

class Main extends Jet\Auth_ManagerModule_Abstract {
	protected static $__signals = array(
		"/user/login",
		"/user/logout",
	);

	/**
	 * List of available privileges
	 *
	 * @var array
	 */
	protected $available_privileges = array(

		Jet\Auth::PRIVILEGE_VISIT_PAGE => array(
			"label" => "Sites and pages",
			"get_available_values_list_method_name" => "getAclActionValuesList_Pages"
		),

		Jet\Auth::PRIVILEGE_MODULE_ACTION => array(
			"label" => "Modules and actions",
			"get_available_values_list_method_name" => "getAclActionValuesList_ModulesActions"
		)

	);

	/**
	 * Currently logged user
	 *
	 * @var Jet\Auth_User_Abstract
	 */
	protected $current_user;

	/**
	 * Returns true if authentication (for example login dialog...) is required
	 *
	 * @return bool
	 */
	public function getAuthenticationRequired() {

		$user = $this->getCurrentUser();
		if(!$user) {
			return true;
		}

		if(
			!$user->getIsActivated()
		) {
			return true;
		}

		if($user->getIsBlocked()) {
			$till = $user->getIsBlockedTill();
			if(
				$till!==null &&
				$till<=Jet\DateTime::now()
			) {
				$user->unBlock();
				$user->validateData();
				$user->save();
			} else {
				return true;
			}
		}

		if( $user->getPasswordIsValid() ) {
			$pwd_valid_till = $user->getPasswordIsValidTill();

			if(
				$pwd_valid_till!==null &&
				$pwd_valid_till<=Jet\DateTime::now()
			) {
				$user->setPasswordIsValid(false);
				$user->validateData();
				$user->save();

				return true;
			}
		} else {
			return true;
		}

		return false;
	}

	/**
	 * Returns dispatch queue (example: show login dialog )
	 *
	 * @return Jet\Mvc_Dispatcher_Queue
	 */
	public function getDispatchQueue() {

		$queue = new Jet\Mvc_Dispatcher_Queue();

		$action = "login";

		$user = $this->getCurrentUser();

		if($user) {
			if(!$user->getIsActivated()) {
				$action = "isNotActivated";
			} else
				if($user->getIsBlocked()) {
					$action = "isBlocked";
				} else
					if(!$user->getPasswordIsValid()) {
						$action = "mustChangePassword";
					}
		}

		$queue->addItem(
			new Jet\Mvc_Dispatcher_Queue_Item( $this->module_info->getName(), "", $action )
		);

		return $queue;
	}


	/**
	 * Initialize layout
	 *
	 * @return Jet\Mvc_Layout
	 */
	function initializeLayout() {
		$layout = new Jet\Mvc_Layout( $this->module_info->getLayoutsDir(), "default" );
		$layout->setRouter($this->router);

		return $layout;
	}

	/**
	 * Authenticates given user and returns TRUE if given credentials are valid, otherwise returns FALSE
	 *
	 * @param string $login
	 * @param string $password
	 *
	 * @return bool
	 */
	public function login( $login, $password ) {
		$user = Jet\Auth_Factory::getUserInstance()->getByIdentity(  $login, $password  );

		if(!$user)  {
			return false;
		}

		$session = new Jet\Session("auth");
		$session->setValue( "user_ID", $user->getID() );

		$this->current_user = $user;

		$this->sendSignal("/user/login");

		return true;
	}

	/**
	 * Logout current user
	 *
	 * @return void
	 */
	public function logout() {
		$this->sendSignal("/user/logout");

		Jet\Session::destroy();
		$this->current_user = null;
		Jet\Http_Headers::reload();
	}

	/**
	 * Return current user data or FALSE
	 *
	 * @return Jet\Auth_User_Abstract|bool
	 */
	public function getCurrentUser() {
		if($this->current_user!==null) {
			return $this->current_user;
		}

		$session = new Jet\Session("auth");

		$user_ID = $session->getValue( "user_ID", null );
		if(!$user_ID) {
			$this->current_user = false;
			return null;
		}


		$this->current_user = Jet\Auth::getUser($user_ID);

		return $this->current_user;
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
	public function getCurrentUserHasPrivilege( $privilege, $value, $log_if_false=true ) {
		if(!$this->getCurrentUser()->getHasPrivilege( $privilege, $value )) {
			return false;
		}

		return true;
	}

	/**
	 * Log auth event
	 *
	 * @param string $event
	 * @param mixed $event_data
	 * @param string $event_txt
	 * @param string $user_ID (optional; default: null = current user ID)
	 * @param string $user_login (optional; default: null = current user login)
	 * @return void
	 */
	public function logEvent( $event, $event_data, $event_txt, $user_ID=null, $user_login=null ) {
		if($user_ID===null) {
			$c_user = $this->getCurrentUser();

			if($c_user) {
				$user_ID = (string)$c_user->getID();
				$user_login = $c_user->getLogin();
			} else {
				$user_ID = "";
				$user_login = "";
			}

		}

		Event::logEvent($event, $event_data, $event_txt, $user_ID, $user_login);
	}

	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


	/**
	 * Get login form instance
	 *
	 * @return Jet\Form
	 */
	function getLoginForm() {
		$form = new Jet\Form("login", array(
			Jet\Form_Factory::field("Input", "login", "User name: "),
			Jet\Form_Factory::field("Password", "password", "Password:")
		));

		$form->getField("login")->setIsRequired( true );
		/**
		 * @var Jet\Form_Field_Password $password
		 */
		$password = $form->getField("password");
		$password->setDisableCheck( true );
		$password->setIsRequired( true );

		return $form;
	}

	/**
	 * @return \Jet\Form
	 */
	function getChangePasswordForm() {
		$form = new Jet\Form("login", array(
			Jet\Form_Factory::field("Password", "password", "Password")
		));

		$form->getField("password")->setIsRequired( true );

		return $form;
	}

	/**
	 * Get list of available privileges
	 *
	 * @param bool $get_available_values_list (optional, default: false)
	 *
	 * @return Jet\Auth_Role_Privilege_AvailablePrivilegesListItem[]
	 */
	public function getAvailablePrivilegesList( $get_available_values_list=false ) {
		$data = array();

		foreach($this->available_privileges as $privilege=>$d) {
			$available_values_list = null;

			if($get_available_values_list) {
				$available_values_list = $this->{$d["get_available_values_list_method_name"]}();
			}

			$item = new Jet\Auth_Role_Privilege_AvailablePrivilegesListItem( $privilege, $d["label"], $available_values_list );

			$data[$privilege] = $item;
		}

		return $data;
	}

	/**
	 * Get list of available privilege values or false if the privilege does not exist
	 *
	 * @param $privilege
	 *
	 * @return Jet\Data_Tree_Forest
	 */
	public function getAvailablePrivilegeValuesList( $privilege ) {
		if(!isset($this->available_privileges[$privilege])) {
			return false;
		}

		$method = $this->available_privileges[$privilege]["get_available_values_list_method_name"];

		return $this->{$method}();
	}

	/**
	 * Get modules and actions ACL values list
	 *
	 * @return Jet\Data_Tree_Forest
	 */
	public function getAclActionValuesList_ModulesActions() {
		$forest = new Jet\Data_Tree_Forest();
		$forest->setLabelKey("name");
		$forest->setIDKey("ID");

		$modules = Jet\Application_Modules::getActivatedModulesList();

		foreach( $modules as $module_name=>$module_info ) {

			$module = Jet\Application_Modules::getModuleInstance($module_name);

			$actions = $module->getAclActions();

			if(!$actions) {
				continue;
			}


			$data = array();

			$data[] = array(
				"ID" => $module_name,
				"parent_ID" => "",
				"name" => $module_info->getLabel()." ({$module_name})"
			);


			foreach($actions as $action=>$action_description) {
				$data[] = array(
					"ID" => $module_name.":".$action,
					"parent_ID" => $module_name,
					"name" => $action_description
				);
			}

			$tree = new Jet\Data_Tree();
			$tree->setData($data);

			$forest->appendTree($tree);

		}

		return $forest;
	}

	/**
	 * Get sites and pages ACL values list
	 *
	 * @return Jet\Data_Tree_Forest
	 */
	public function getAclActionValuesList_Pages() {
		return Jet\Mvc_Factory::getPageInstance()->getAllPagesTree();
	}

}