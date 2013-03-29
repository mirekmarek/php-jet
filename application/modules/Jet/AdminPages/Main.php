<?php
/**
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule_Adminpages
 */
namespace JetApplicationModule\Jet\AdminPages;
use Jet;

class Main extends Jet\Application_Modules_Module_Abstract {
	protected $ACL_actions = array(
		"get_page" => "Get page(s) data",
		"add_page" => "Add new page",
		"update_page" => "Update page",
		"delete_page" => "Delete page",
	);

}