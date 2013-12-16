<?php
/**
 *
 *
 *
 * Default admin UI module
 *
 * @see Jet\Mvc/readme.txt
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule_AdminRoles
 * @subpackage JetApplicationModule_AdminRoles_Controller
 */
namespace JetApplicationModule\Jet\AdminRoles;
use Jet;

class Controller_Standard extends Jet\Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = NULL;

	protected static $ACL_actions_check_map = array(
		"default" => false
	);

	public function default_Action() {
		Jet\Mvc::requireJavascriptLib("Jet");

		$GET = Jet\Http_Request::GET();

		$role = false;
		if($GET->exists("new")) {
			$role = Jet\Auth::getNewRole();

		} else if( $GET->exists("ID") ) {
			$role = Jet\Auth::getRole( $GET->getString("ID") );
		}

		if($role) {

			$form = $role->getCommonForm();

			if($role->catchForm( $form )) {
				$role->validateProperties();
				$role->save();
				Jet\Http_Headers::formSent( $form );
			}

			if($role->getIsNew()) {
				$this->view->setVar("bnt_label", Jet\Tr::_("ADD"));

				$this->getUIManagerModuleInstance()->addBreadcrumbNavigationData(Jet\Tr::_("New role"));
			} else {
				$this->view->setVar("bnt_label", Jet\Tr::_("Save"));

				$this->getUIManagerModuleInstance()->addBreadcrumbNavigationData(Jet\Tr::_("Edit role: ").$role->getName());
			}

			$this->view->setVar("form", $form);
			$this->view->setVar("role", $role);
			$this->view->setVar("available_privileges_list", Jet\Auth::getAvailablePrivilegesList(true));

			$this->render("edit");
		} else {

			$p = new Jet\Data_Paginator(
				$GET->getInt("p", 1),
				10,
				"?p=".Jet\Data_Paginator::URL_PAGE_NO_KEY
			);
			$p->setDataSource( Jet\Auth::getRolesListAsData() );

			$this->view->setVar("roles", $p->getData());
			$this->view->setVar("paginator", $p);

			$this->render("default");
		}

	}
}