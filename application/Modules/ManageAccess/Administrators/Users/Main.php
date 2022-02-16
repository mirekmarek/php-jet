<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\ManageAccess\Administrators\Users;

use Jet\Application_Module;

/**
 *
 */
class Main extends Application_Module
{
	const ADMIN_MAIN_PAGE = 'administrators-users';

	const ACTION_GET_USER = 'get_user';
	const ACTION_ADD_USER = 'add_user';
	const ACTION_UPDATE_USER = 'update_user';
	const ACTION_DELETE_USER = 'delete_user';


}