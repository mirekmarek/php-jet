<?php
/**
 *
 *
 *
 * DefaultAdminUI admin mode REST controller
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule_DefaultAdminUI
 * @subpackage JetApplicationModule_DefaultAdminUI_Controller
 */
namespace JetApplicationModule\Jet\DefaultAdminUI;
use Jet;

class Controller_REST extends Jet\Mvc_Controller_REST {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = NULL;

	protected static $ACL_actions_check_map = array(
	);
}