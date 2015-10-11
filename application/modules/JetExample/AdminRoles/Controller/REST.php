<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\AdminRoles;
use Jet;

class Controller_REST extends Jet\Mvc_Controller_REST {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	protected static $ACL_actions_check_map = array(
		'get_role' => 'get_role',
		'post_role' => 'add_role',
		'put_role' => 'update_role',
		'delete_role' => 'delete_role',
		'get_privilege_values_scope' => false
	);

	/**
	 *
	 */
	public function initialize() {
	}


	/**
	 * @param null|int $ID
	 */
	public function get_role_Action( $ID=null ) {
		if($ID) {
			$role = $this->_getRole($ID);
			$this->responseData($role);
		} else {
			$this->responseDataModelsList( Jet\Auth::getRolesListAsData() );
		}
	}

	public function post_role_Action() {
		$role = Jet\Auth::getNewRole();

		$form = $role->getCommonForm();

		if($role->catchForm( $form, $this->getRequestData(), true )) {
			$role->validateProperties();
			$role->save();
			$this->responseData($role);
		} else {
			$this->responseFormErrors( $form->getAllErrors() );
		}
	}

	public function put_role_Action( $ID ) {

		$role = $this->_getRole($ID);

		$form = $role->getCommonForm();

		if($role->catchForm( $form, $this->getRequestData(), true )) {
			$role->validateProperties();
			$role->save();
			$this->responseData($role);
		} else {
			$this->responseFormErrors( $form->getAllErrors() );
		}
	}

	public function delete_role_Action( $ID ) {
		$role = $this->_getRole($ID);

		$role->delete();

		$this->responseOK();

	}

	/**
	 * @param $ID
	 * @return Jet\Auth_Role_Abstract
	 */
	protected  function _getRole($ID) {
		$role = Jet\Auth::getRole($ID);

		if(!$role) {
			$this->responseUnknownItem($ID);
		}

		return $role;
	}

	/**
	 * @param string $privilege
	 */
	public function get_privilege_values_scope_Action( $privilege ) {

		$values = Jet\Auth::getAvailablePrivilegeValuesList($privilege);

		if(!$values) {
			$this->responseUnknownItem( $privilege );
		}

		$this->responseData($values);
	}

}