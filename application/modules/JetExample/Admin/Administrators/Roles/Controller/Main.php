<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Admin\Administrators\Roles;

use JetExampleApp\Auth_Administrator_Role as Role;
use JetExampleApp\Mvc_Controller_AdminStandard;

use JetUI\UI;
use JetUI\dataGrid;
use JetUI\breadcrumbNavigation;
use JetUI\messages;

use Jet\Mvc;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Tr;

use JetApplicationModule\JetExample\AdminUI\Main as AdminUI_module;

/**
 *
 */
class Controller_Main extends Mvc_Controller_AdminStandard {

	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	/**
	 * @var array
	 */
	protected static $ACL_actions_check_map = [
		'default' => Main::ACTION_GET_ROLE,
		'add' => Main::ACTION_ADD_ROLE,
		'edit' => Main::ACTION_UPDATE_ROLE,
		'view' => Main::ACTION_GET_ROLE,
		'delete' => Main::ACTION_DELETE_ROLE,
	];

	/**
	 *
	 * @return Controller_Main_Router
	 */
	public function getControllerRouter() {
		return $this->module_instance->getAdminControllerRouter();
	}


	/**
	 * @param string $current_label
	 */
	protected function _setBreadcrumbNavigation($current_label='' ) {
		$menu_item = AdminUI_module::getMenuItems()['system/administrator_roles'];

		breadcrumbNavigation::addItem(
			UI::icon($menu_item->getIcon()).'&nbsp;&nbsp;'. $menu_item->getLabel(),
			$menu_item->getUrl()
		);

		if($current_label) {
			breadcrumbNavigation::addItem( $current_label );

		}
	}

	/**
	 *
	 */
	public function default_Action() {
		$this->_setBreadcrumbNavigation();

		$search_form = UI::searchForm('role');
		$this->view->setVar('search_form', $search_form);


		$grid = new dataGrid();

		$grid->setIsPersistent('admin_roles_list_grid');
		$grid->setDefaultSort('name');

		$grid->addColumn('_edit_', '')->setAllowSort(false);
		$grid->addColumn('id', Tr::_('ID'));
		$grid->addColumn('name', Tr::_('Name'));
		$grid->addColumn('description', Tr::_('Description'));

		$grid->setData( Role::getList($search_form->getValue()) );

		$this->view->setVar('grid', $grid);

		$this->render('default');
	}


	/**
	 *
	 */
	public function add_Action() {
		$this->_setBreadcrumbNavigation( Tr::_('Create a new Role') );

		/**
		 * @var Role $role
		 */
		$role = new Role();

		$form = $role->getCommonForm();

		if( $role->catchForm( $form ) ) {
			$role->save();
			$this->logAllowedAction( $role );
			messages::success( Tr::_('Role <b>%ROLE_NAME%</b> has been created', ['ROLE_NAME'=>$role->getName() ]) );

			Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI($role->getId()) );
		}



		$this->view->setVar('has_access', true);
		$this->view->setVar('form', $form);
		$this->view->setVar('available_privileges_list', Role::getAvailablePrivilegesList() );

		$this->render('edit');
	}

	/**
	 */
	public function edit_Action() {

		/**
		 * @var Role $role
		 */
		$role = $this->getActionParameterValue('role');

		$this->_setBreadcrumbNavigation( Tr::_('Edit role <b>%ROLE_NAME%</b>', ['ROLE_NAME'=>$role->getName() ]) );

		$form = $role->getCommonForm();

		if( $role->catchForm( $form ) ) {
			$role->save();
			$this->logAllowedAction( $role );
			messages::success( Tr::_('Role <b>%ROLE_NAME%</b> has been updated', ['ROLE_NAME'=>$role->getName() ]) );

			Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI($role->getId()) );
		}

		$this->view->setVar('form', $form);
		$this->view->setVar('role', $role);
		$this->view->setVar('available_privileges_list', Role::getAvailablePrivilegesList() );

		$this->render('edit');
	}

	/**
	 *
	 */
	public function view_Action() {

		/**
		 * @var Role $role
		 */
		$role = $this->getActionParameterValue('role');

		$this->_setBreadcrumbNavigation( Tr::_('Role detail <b>%ROLE_NAME%</b>', ['ROLE_NAME'=>$role->getName() ]) );

		$form = $role->getCommonForm();
		$this->view->setVar('has_access', false);
		$this->view->setVar('form', $form);
		$this->view->setVar('role', $role);
		$this->view->setVar('available_privileges_list', Role::getAvailablePrivilegesList() );

		$form->setIsReadonly();

		$this->render('edit');
	}


	/**
	 *
	 */
	public function delete_action() {

		/**
		 * @var Role $role
		 */
		$role = $this->getActionParameterValue('role');

		$this->_setBreadcrumbNavigation( Tr::_('Delete role <b>%ROLE_NAME%</b>', ['ROLE_NAME'=>$role->getName() ]) );

		if( Http_Request::POST()->getString('delete')=='yes' ) {
			$role->delete();
			$this->logAllowedAction( $role );
			messages::info( Tr::_('Role <b>%ROLE_NAME%</b> has been deleted', ['ROLE_NAME'=>$role->getName() ]) );
			Http_Headers::movedTemporary( Mvc::getCurrentPageURI() );
		}


		$this->view->setVar( 'role', $role );

		$this->render('delete-confirm');
	}


}