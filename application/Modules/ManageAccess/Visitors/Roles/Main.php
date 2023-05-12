<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\ManageAccess\Visitors\Roles;

use Jet\Application_Module;

/**
 *
 */
class Main extends Application_Module
{
	public const ADMIN_MAIN_PAGE = 'visitors-roles';
	
	public const ACTION_GET_ROLE = 'get_role';
	public const ACTION_ADD_ROLE = 'add_role';
	public const ACTION_UPDATE_ROLE = 'update_role';
	public const ACTION_DELETE_ROLE = 'delete_role';


}