<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\Admin\Administrators\Roles;

use Jet\Application_Modules_Module_Abstract;

class Main extends Application_Modules_Module_Abstract {
	const MODULE_NAME = 'JetExample.Admin.Administrators.Roles';
	const PAGE_ROLES = 'admin/administrators-roles';

	const ACTION_GET_ROLE = 'get_role';
	const ACTION_ADD_ROLE = 'add_role';
	const ACTION_UPDATE_ROLE = 'update_role';
	const ACTION_DELETE_ROLE = 'delete_role';

	/**
	 * @var array
	 */
	protected $ACL_actions = [
		self::ACTION_GET_ROLE => 'Get role(s) data',
		self::ACTION_ADD_ROLE => 'Add new role',
		self::ACTION_UPDATE_ROLE => 'Update role',
		self::ACTION_DELETE_ROLE => 'Delete role',
	];

	/**
	 *
	 * @return string|bool
	 */
	public function getRoleAddURI()
	{
		return Controller_Main::getControllerRouter()->getActionURI( Controller_Main::ADD_ACTION );
	}

	/**
	 * @param int $role_id
	 *
	 * @return string|bool
	 */
	public function getRoleEditURI( $role_id )
	{
		$uri = Controller_Main::getControllerRouter()->getActionURI( Controller_Main::EDIT_ACTION, $role_id );
		if(!$uri) {
			$uri = Controller_Main::getControllerRouter()->getActionURI( Controller_Main::VIEW_ACTION, $role_id );
		}

		return $uri;
	}


	/**
	 * @param int $role_id
	 *
	 * @return string|bool
	 */
	public function getRoleViewURI( $role_id )
	{
		return Controller_Main::getControllerRouter()->getActionURI( Controller_Main::VIEW_ACTION, $role_id );
	}

	/**
	 * @param int $role_id
	 *
	 * @return string|bool
	 */
	public function getRoleDeleteURI( $role_id )
	{
		return Controller_Main::getControllerRouter()->getActionURI( Controller_Main::DELETE_ACTION, $role_id );
	}


}