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
use JetApplicationModule\JetExample\UIElements;

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

		if( $delete_ID = $GET->getString('delete')) {
			$role = Jet\Auth::getRole( $delete_ID );
			if($role) {
				$this->handleDelete($role);

				return;
			}
		}


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
	public function handleDelete( Jet\Auth_Role_Abstract $role ) {
		if( !$this->module_instance->checkAclCanDoAction('delete_role') ) {
			//TODO:
			return;
		}

		if( Jet\Http_Request::POST()->getString('delete')=='yes' ) {
			$role->delete();
			Jet\Http_Headers::movedTemporary('?');
		}


		$this->getUIManagerModuleInstance()->addBreadcrumbNavigationData('Delete role');

		$this->view->setVar( 'role', $role );

		$this->render('classic/delete-confirm');
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

		/**
		 * @var UIElements\Main $UI_m
		 */
		$UI_m = Jet\Application_Modules::getModuleInstance('JetExample\UIElements');
		$grid = $UI_m->getDataGridInstance();

		$grid->addColumn('_edit_', '')->setAllowSort(false);
		$grid->addColumn('ID', Jet\Tr::_('ID'));
		$grid->addColumn('name', Jet\Tr::_('Name'));
		$grid->addColumn('description', Jet\Tr::_('Description'));

		$grid->setData( Jet\Auth::getRolesList() );

		$this->view->setVar('grid', $grid);

		$this->render('classic/default');

	}
}