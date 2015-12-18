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
namespace JetApplicationModule\JetExample\AdminUsers;
use Jet;
use Jet\Mvc_Controller_REST;
use Jet\Auth_User_Abstract;
use Jet\Auth;

class Controller_REST extends Mvc_Controller_REST {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;


	protected static $ACL_actions_check_map = array(
		'get_user' => 'get_user',
		'post_user' => 'add_user',
		'put_user' => 'update_user',
		'delete_user' => 'delete_user',
	);

	/**
	 *
	 */
	public function initialize() {
	}


	/**
	 * @param null|string $ID
	 */
	public function get_user_Action( $ID=null ) {
		if($ID) {
			$user = $this->_getUser($ID);
			$this->responseData($user);
		} else {
			$this->responseDataModelsList( Auth::getUsersListAsData() );
		}

	}

	public function post_user_Action() {
		$user = Auth::getNewUser();

		$form = $user->getCommonForm();

		if($user->catchForm( $form, $this->getRequestData(), true )) {
			$user->validateProperties();
			$user->save();
			$this->responseData($user);
		} else {
			$this->responseFormErrors( $form->getAllErrors() );
		}
	}

	public function put_user_Action( $ID ) {
		$user = $this->_getUser($ID);

		$form = $user->getCommonForm();

		if($user->catchForm( $form, $this->getRequestData(), true )) {
			$user->validateProperties();
			$user->save();
			$this->responseData($user);
		} else {
			$this->responseFormErrors( $form->getAllErrors() );
		}
	}

	public function delete_user_Action( $ID ) {
		$user = $this->_getUser($ID);

		$user->delete();

		$this->responseOK();
	}

	/**
	 * @param $ID
	 * @return Auth_User_Abstract
	 */
	protected  function _getUser($ID) {
		$user = Auth::getUser($ID);

		if(!$user) {
			$this->responseUnknownItem($ID);
		}

		return $user;
	}
}