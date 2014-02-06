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
namespace JetApplicationModule\JetExample\AdminRoles;
use Jet;

class Controller_Standard extends Jet\Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	protected static $ACL_actions_check_map = array(
		'default' => 'get_role'
	);

	/**
	 *
	 */
	public function default_Action() {

		Jet\Mvc::setProvidesDynamicContent();

		$GET = Jet\Http_Request::GET();

		$role = false;
		if( $GET->exists('new') ) {
			$role = Jet\Auth::getNewRole();
		} else if( $GET->exists('ID') ) {
			$role = Jet\Auth::getRole( $GET->getString('ID') );
		}

		if($role) {
			$this->handleEdit( $role );
		} else {
			$this->handleList();
		}
	}

	/**
	 * @param Jet\Auth_Role_Abstract $role
	 */
	protected function handleEdit( Jet\Auth_Role_Abstract $role ) {
		$has_access = false;

		if($role->getIsNew()) {
			if( $this->module_instance->checkAclCanDoAction('add_role') ) {
				$has_access = true;
			}
		} else {
			if( $this->module_instance->checkAclCanDoAction('update_role') ) {
				$has_access = true;
			}
		}

		if(!$has_access) {
			//TODO:
			return;
		}

		$form = $role->getCommonForm();

		if($role->catchForm( $form )) {
			$role->validateProperties();
			$role->save();
			Jet\Http_Headers::movedTemporary( '?ID='.$role->getID() );
		}

		if($role->getIsNew()) {
			$this->view->setVar('bnt_label', Jet\Tr::_('ADD'));

			$this->getUIManagerModuleInstance()->addBreadcrumbNavigationData(Jet\Tr::_('New role'));
		} else {
			$this->view->setVar('bnt_label', Jet\Tr::_('Save'));

			$this->getUIManagerModuleInstance()->addBreadcrumbNavigationData( $role->getName() );
		}

		$this->getUIManagerModuleInstance()->breadcrumbNavigationShift( -3 );

		$this->view->setVar('form', $form);
		$this->view->setVar('role', $role);
		$this->view->setVar('available_privileges_list', Jet\Auth::getAvailablePrivilegesList(true));

		$this->render('classic/edit');

	}

	/**
	 *
	 */
	protected function handleList() {

		$this->getUIManagerModuleInstance()->breadcrumbNavigationShift( -2 );

		$p = new Jet\Data_Paginator(
			Jet\Http_Request::GET()->getInt('p', 1),
			10,
			'?p='.Jet\Data_Paginator::URL_PAGE_NO_KEY
		);
		$p->setDataSource( Jet\Auth::getRolesList() );

		$this->view->setVar('roles', $p->getData());
		$this->view->setVar('paginator', $p);

		$this->render('classic/default');

	}
}