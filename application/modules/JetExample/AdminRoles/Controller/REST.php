<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\AdminRoles;

use Jet\Mvc_Controller_REST;
use Jet\Auth_Role;

class Controller_REST extends Mvc_Controller_REST {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	protected static $ACL_actions_check_map = [
		'get_role' => 'get_role',
		'post_role' => 'add_role',
		'put_role' => 'update_role',
		'delete_role' => 'delete_role',
		'get_privilege_values_scope' => false
	];

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
			$this->responseDataModelsList( (new Auth_Role())->getList() );
		}
	}

	public function post_role_Action() {
		/**
		 * @var Auth_Role $role
		 */
		$role = new Auth_Role();

		$form = $role->getCommonForm();

		if($role->catchForm( $form, $this->getRequestData(), true )) {
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
	 * @return Auth_Role
	 */
	protected  function _getRole($ID) {
		$role = Auth_Role::get($ID);

		if(!$role) {
			$this->responseUnknownItem($ID);
		}

		return $role;
	}


}