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
namespace JetApplicationModule\JetExample\Admin\Administrators\Users;

use Jet\Application_Modules_Module_Abstract;

class Main extends Application_Modules_Module_Abstract {
	const MODULE_NAME = 'JetExample.Admin.Administrators.Users';
	const PAGE_USERS = 'admin/administrators-users';

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
	 *
	 * @return string|bool
	 */
	public function getUserAddURI()
	{
		return Controller_Main::getControllerRouter()->getActionURI( Controller_Main::ADD_ACTION );
	}

	/**
	 * @param int $user_id
	 *
	 * @return string|bool
	 */
	public function getUserEditURI( $user_id )
	{
		$uri = Controller_Main::getControllerRouter()->getActionURI( Controller_Main::EDIT_ACTION, $user_id );
		if(!$uri) {
			$uri = Controller_Main::getControllerRouter()->getActionURI( Controller_Main::VIEW_ACTION, $user_id );
		}

		return $uri;
	}


	/**
	 * @param int $user_id
	 *
	 * @return string|bool
	 */
	public function getUserViewURI( $user_id )
	{
		return Controller_Main::getControllerRouter()->getActionURI( Controller_Main::VIEW_ACTION, $user_id );
	}

	/**
	 * @param int $user_id
	 *
	 * @return string|bool
	 */
	public function getUserDeleteURI( $user_id )
	{
		return Controller_Main::getControllerRouter()->getActionURI( Controller_Main::DELETE_ACTION, $user_id );
	}

}