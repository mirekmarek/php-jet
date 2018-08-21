<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\ManageAccess\Visitors\Users;

use Jet\Application_Module;

/**
 *
 */
class Main extends Application_Module
{
	const ADMIN_MAIN_PAGE = 'visitors-users';

	const ACTION_GET_USER = 'get_user';
	const ACTION_ADD_USER = 'add_user';
	const ACTION_UPDATE_USER = 'update_user';
	const ACTION_DELETE_USER = 'delete_user';


}