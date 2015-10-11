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
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */

namespace JetApplicationModule\JetExample\AuthController;
use Jet;

/**
 * Class Main
 *
 * @JetApplication_Signals:signal = '/user/login'
 * @JetApplication_Signals:signal = '/user/logout'
 */
class Main extends Jet\Auth_ControllerModule_Abstract {

	/**
	 * @var array
	 */
	protected $standard_privileges = array(
		Jet\Auth::PRIVILEGE_VISIT_PAGE => array(
			'label' => 'Sites and pages',
			'get_available_values_list_method_name' => 'getAclActionValuesList_Pages'
		),

		Jet\Auth::PRIVILEGE_MODULE_ACTION => array(
			'label' => 'Modules and actions',
			'get_available_values_list_method_name' => 'getAclActionValuesList_ModulesActions'
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
				$user->validateProperties();
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
				$user->validateProperties();
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
	 * @return Jet\Mvc_Page_Abstract
	 */
	public function getAuthenticationPage() {

        $page = Jet\Mvc::getCurrentPage();


		$action = 'login';

		$user = $this->getCurrentUser();

		if($user) {
			if(!$user->getIsActivated()) {
				$action = 'isNotActivated';
			} else
				if($user->getIsBlocked()) {
					$action = 'isBlocked';
				} else
					if(!$user->getPasswordIsValid()) {
						$action = 'mustChangePassword';
					}
		}


        $page_content = array();
        $page_content_item = Jet\Mvc_Factory::getPageContentInstance();

        $page_content_item->setModuleName( $this->module_manifest->getName() );
        $page_content_item->setControllerAction( $action );
        $page_content_item->setIsDynamic(true);


        $page_content[] = $page_content_item;

        $page->setContents( $page_content );


        $layout = new Jet\Mvc_Layout( $this->getLayoutsDir(), 'default' );

        $page->setLayout( $layout );


		return $page;
	}


	/**
	 * @return Jet\Session
	 */
	protected function getSession() {
		if( Jet\Mvc::getIsAdminUIRequest() ) {
			return new Jet\Session('auth_admin');
		} else {
			return new Jet\Session('auth_web');
		}

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

		$session = $this->getSession();
		$session->setValue( 'user_ID', $user->getID() );

		$this->current_user = $user;

		$this->sendSignal('/user/login');

		return true;
	}

	/**
	 * Logout current user
	 */
	public function logout() {
		$this->sendSignal('/user/logout');

		Jet\Session::destroy();
		$this->current_user = null;
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

		$session = $this->getSession();

		$user_ID = $session->getValue( 'user_ID', null );
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
	 * @param Jet\Auth_Role_Privilege_ContextObject_Interface $context_object (optional)
	 * @param bool $log_if_false (optional, default: true)
	 *
	 * @return bool
	 */
	public function getCurrentUserHasPrivilege( $privilege, $value, Jet\Auth_Role_Privilege_ContextObject_Interface $context_object = null, $log_if_false=true ) {
		$res = false;

		$current_user = $this->getCurrentUser();
		if($current_user) {
			$res = $current_user->getHasPrivilege( $privilege, $value );
		}

		if($res && $context_object) {
			$res = $context_object->getHasACLPrivilege( $privilege, $value );
		}

		if(!$res && $log_if_false) {
			$login = 'unknown';
			$user_ID = 'unknown';


			if($current_user) {
				$login = $current_user->getLogin();
				$user_ID = $current_user->getID();
			}

			if(is_array($value)) {
				$value = implode(',', $value);
			}

			static::logEvent('privilege_access_denied',
				array(
					'privilege'=>$privilege,
					'value'=>$value
				),
				'Privilege access denied. Login: \''.$login.'\', User ID: \''.$user_ID.'\', Privilege: \''.$privilege.'\', Value: \''.$value.'\''
			);
		}


		return $res;
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
	public function logEvent( $event, $event_data, $event_txt, $user_ID=null, $user_login=null ) {
		if($user_ID===null) {
			$c_user = $this->getCurrentUser();

			if($c_user) {
				$user_ID = (string)$c_user->getID();
				$user_login = $c_user->getLogin();
			} else {
				$user_ID = '';
				$user_login = '';
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
		$form = new Jet\Form('login', array(
			Jet\Form_Factory::field('Input', 'login', 'User name: '),
			Jet\Form_Factory::field('Password', 'password', 'Password:')
		));

		$form->getField('login')->setIsRequired( true );
		/**
		 * @var Jet\Form_Field_Password $password
		 */
		$password = $form->getField('password');
		$password->setDisableCheck( true );
		$password->setIsRequired( true );

		return $form;
	}

	/**
	 * @return \Jet\Form
	 */
	function getChangePasswordForm() {
		$form = new Jet\Form('login', array(
			Jet\Form_Factory::field('Password', 'password', 'Password')
		));

		$form->getField('password')->setIsRequired( true );

		return $form;
	}

	/**
	 * Get list of available privileges
	 *
	 *
	 * @return Jet\Auth_Role_Privilege_AvailablePrivilegesListItem[]
	 */
	public function getAvailablePrivilegesList() {
		$data = array();

		foreach( $this->standard_privileges as $privilege=>$d) {
			$available_values_list = null;

			$available_values_list = $this->{$d['get_available_values_list_method_name']}();

			$item = new Jet\Auth_Role_Privilege_AvailablePrivilegesListItem( $privilege, $d['label'], $available_values_list );

			$data[$privilege] = $item;
		}

		foreach( Jet\Application_Modules::getActivatedModulesList() as $manifest ) {
			$module = Jet\Application_Modules::getModuleInstance( $manifest->getName() );
			if( $module instanceof Jet\Auth_Role_Privilege_Provider_Interface ) {
				/**
				 * @var Jet\Auth_Role_Privilege_AvailablePrivilegesListItem[] $av
				 */
				$av = $module->getAvailablePrivileges();

				foreach( $av as $item ) {
					$data[$item->getPrivilege()] = $item;
				}
			}
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
		$available_privileges = $this->getAvailablePrivilegesList();

		if(!isset($available_privileges[$privilege])) {
			return false;
		}

		return $available_privileges[$privilege]->getValuesList();
	}

	/**
	 * Get modules and actions ACL values list
	 *
	 * @return Jet\Data_Tree_Forest
	 */
	public function getAclActionValuesList_ModulesActions() {
		$forest = new Jet\Data_Tree_Forest();
		$forest->setLabelKey('name');
		$forest->setIDKey('ID');

		$modules = Jet\Application_Modules::getActivatedModulesList();

		foreach( $modules as $module_name=>$module_info ) {

			$module = Jet\Application_Modules::getModuleInstance($module_name);

			$actions = $module->getAclActions();

			if(!$actions) {
				continue;
			}


			$data = array();

			$data[] = array(
				'ID' => $module_name,
				'parent_ID' => '',
				'name' => $module_info->getLabel().' ('.$module_name.')'
			);


			foreach($actions as $action=>$action_description) {
				$data[] = array(
					'ID' => $module_name.':'.$action,
					'parent_ID' => $module_name,
					'name' => $action_description
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