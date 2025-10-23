<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Admin\Auth\Admin\Users;

use Jet\Application_Module;

/**
 *
 */
class Main extends Application_Module
{
	public const ADMIN_MAIN_PAGE = 'administrators-users';
	
	public const ACTION_GET = 'get_user';
	public const ACTION_ADD = 'add_user';
	public const ACTION_UPDATE = 'update_user';
	public const ACTION_DELETE = 'delete_user';


}