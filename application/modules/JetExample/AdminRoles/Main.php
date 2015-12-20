<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\AdminRoles;
use Jet;
use Jet\Application_Modules_Module_Abstract;
use Jet\Mvc_MicroRouter;

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
	 * @var Mvc_MicroRouter
	 */
	protected $_micro_router;

}