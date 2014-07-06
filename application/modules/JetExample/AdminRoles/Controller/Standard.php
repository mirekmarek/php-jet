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

	/**
	 * @var Jet\Mvc_MicroRouter
	 */
	protected $micro_router;

	protected static $ACL_actions_check_map = array(
		'default' => 'get_role',
		'add' => 'add_role',
		'edit' => 'update_role',
		'view' => 'get_role',
		'delete' => 'delete_role',
	);

	/**
	 *
	 */
	public function initialize() {
		Jet\Mvc::setProvidesDynamicContent();
		$this->getFrontController()->breadcrumbNavigationShift( -2 );
		$this->micro_router = $this->module_instance->getMicroRouter();
	}


	/**
	 *
	 */
	public function default_Action() {
		/**
		 * @var UIElements\Main $UI_m
		 */
		$UI_m = Jet\Application_Modules::getModuleInstance('JetExample\UIElements');
		$grid = $UI_m->getDataGridInstance();

		$grid->setIsPersistent('admin_classic_roles_list_grid');

		$grid->addColumn('_edit_', '')->setAllowSort(false);
		$grid->addColumn('ID', Jet\Tr::_('ID'));
		$grid->addColumn('name', Jet\Tr::_('Name'));
		$grid->addColumn('description', Jet\Tr::_('Description'));

		$grid->setData( Jet\Auth::getRolesList() );

		$this->view->setVar('grid', $grid);
		$this->view->setVar( 'router', $this->micro_router );

		$this->render('classic/default');
	}


	/**
	 *
	 */
	public function add_Action() {

		$role = Jet\Auth::getNewRole();

		$form = $role->getCommonForm();

		if( $role->catchForm( $form ) ) {
			$role->validateProperties();
			$role->save();
			Jet\Http_Headers::movedTemporary( $this->micro_router->getActionURI( 'edit', $role ) );
		}

		$this->getFrontController()->addBreadcrumbNavigationData( Jet\Tr::_('New role') );


		$this->view->setVar('btn_label', Jet\Tr::_('ADD') );
		$this->view->setVar('has_access', true);
		$this->view->setVar('form', $form);
		$this->view->setVar('available_privileges_list', Jet\Auth::getAvailablePrivilegesList(true));

		$this->render('classic/edit');
	}

	/**
	 * @param Jet\Auth_Role_Abstract $role
	 */
	public function edit_Action( Jet\Auth_Role_Abstract $role ) {

		$form = $role->getCommonForm();

		if( $role->catchForm( $form ) ) {
			$role->validateProperties();
			$role->save();
			Jet\Http_Headers::movedTemporary( $this->micro_router->getActionURI( 'edit', $role ) );
		}

		$this->getFrontController()->addBreadcrumbNavigationData( $role->getName() );

		$this->view->setVar('btn_label', Jet\Tr::_('SAVE') );
		$this->view->setVar('has_access', true);
		$this->view->setVar('form', $form);
		$this->view->setVar('role', $role);
		$this->view->setVar('available_privileges_list', Jet\Auth::getAvailablePrivilegesList(true));

		$this->render('classic/edit');
	}

	/**
	 * @param Jet\Auth_Role_Abstract $role
	 */
	public function view_Action( Jet\Auth_Role_Abstract $role ) {

		$this->getFrontController()->addBreadcrumbNavigationData( $role->getName() );

		$form = $role->getCommonForm();
		$this->view->setVar('has_access', false);
		$this->view->setVar('form', $form);
		$this->view->setVar('role', $role);
		$this->view->setVar('available_privileges_list', Jet\Auth::getAvailablePrivilegesList(true));

		$this->render('classic/edit');
	}


	/**
	 * @param Jet\Auth_Role_Abstract $role
	 */
	public function delete_action( Jet\Auth_Role_Abstract $role ) {

		if( Jet\Http_Request::POST()->getString('delete')=='yes' ) {
			$role->delete();

			Jet\Http_Headers::movedTemporary( Jet\Mvc::getCurrentURI() );
		}


		$this->getFrontController()->addBreadcrumbNavigationData('Delete role');

		$this->view->setVar( 'role', $role );

		$this->render('classic/delete-confirm');
	}


}