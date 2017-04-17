<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\Admin\Visitors\Users;

use Jet\Application_Modules_Module_Abstract;

class Main extends Application_Modules_Module_Abstract {
	const PAGE_USERS = 'admin/visitors-users';

	const ACTION_GET_USER = 'get_user';
	const ACTION_ADD_USER = 'add_user';
	const ACTION_UPDATE_USER = 'update_user';
	const ACTION_DELETE_USER = 'delete_user';

	/**
	 * @var array
	 */
	protected $ACL_actions = [
		self::ACTION_GET_USER => 'Get user(s) data',
		self::ACTION_ADD_USER => 'Add new user',
		self::ACTION_UPDATE_USER => 'Update user',
		self::ACTION_DELETE_USER => 'Delete user',
	];

	/**
	 * @var Controller_Main_Router
	 */
	protected $admin_controller_router;


	/**
	 * @return Controller_Main_Router
	 */
	public function getAdminControllerRouter() {

		if(!$this->admin_controller_router) {
			$this->admin_controller_router = new Controller_Main_Router( $this );
		}

		return $this->admin_controller_router;
	}

}