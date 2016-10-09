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
namespace JetApplicationModule\JetExample\AdminRoles;

use Jet\Auth;
use Jet\Auth_Role_Privilege_Provider_Interface;
use Jet\Data_Tree;
use Jet\Data_Tree_Forest;
use Jet\Application_Modules;
use Jet\Application_Modules_Module_Abstract;
use Jet\Mvc_Controller_Router;
use Jet\Auth_Role_Privilege_AvailablePrivilegesListItem;
use Jet\Mvc_Factory;

class Main extends Application_Modules_Module_Abstract {

	protected $ACL_actions = [
		'get_role' => 'Get role(s) data',
		'add_role' => 'Add new role',
		'update_role' => 'Update role',
		'delete_role' => 'Delete role',
	];

	/**
	 * @var int
	 */
	protected $public_list_items_per_page = 10;

	/**
	 * @var Mvc_Controller_Router
	 */
	protected $_micro_router;

}