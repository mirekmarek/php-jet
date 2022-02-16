<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\ManageAccess\Administrators\Roles;

use Jet\Application_Module;

/**
 *
 */
class Main extends Application_Module
{
	const ADMIN_MAIN_PAGE = 'administrators-roles';

	const ACTION_GET_ROLE = 'get_role';
	const ACTION_ADD_ROLE = 'add_role';
	const ACTION_UPDATE_ROLE = 'update_role';
	const ACTION_DELETE_ROLE = 'delete_role';

}