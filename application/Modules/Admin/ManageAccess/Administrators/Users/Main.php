<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Admin\ManageAccess\Administrators\Users;

use Jet\Application_Module;

/**
 *
 */
class Main extends Application_Module
{
	public const ADMIN_MAIN_PAGE = 'administrators-users';
	
	public const ACTION_GET_USER = 'get_user';
	public const ACTION_ADD_USER = 'add_user';
	public const ACTION_UPDATE_USER = 'update_user';
	public const ACTION_DELETE_USER = 'delete_user';


}