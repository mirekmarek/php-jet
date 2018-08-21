<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\ManageAccess\Administrators\Roles;

use JetApplication\Auth_Administrator_Role as Role;

use Jet\Mvc_Controller_Default;

use Jet\UI;
use Jet\UI_dataGrid;
use Jet\UI_messages;

use Jet\Mvc;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Tr;
use Jet\Navigation_Breadcrumb;

use JetApplicationModule\UI\Admin\Main as UI_module;

/**
 *
 */
class Controller_Main extends Mvc_Controller_Default
{

	/**
	 * @var array
	 */
	const ACL_ACTIONS_MAP = [
		'default' => Main::ACTION_GET_ROLE,
		'add'     => Main::ACTION_ADD_ROLE,
		'edit'    => Main::ACTION_UPDATE_ROLE,
		'view'    => Main::ACTION_GET_ROLE,
		'delete'  => Main::ACTION_DELETE_ROLE,
	];
	/**
	 *
	 * @var Main
	 */
	protected $module = null;


	/**
	 * @var Controller_Main_Router
	 */
	protected $router;


	/**
	 *
	 * @return Controller_Main_Router
	 */
	public function getControllerRouter()
	{
		if( !$this->router ) {
			$this->router = new Controller_Main_Router( $this );
		}

		return $this->router;
	}

	/**
	 * @param string $current_label
	 */
	protected function _setBreadcrumbNavigation( $current_label = '' )
	{
		UI_module::initBreadcrumb();

		if( $current_label ) {
			Navigation_Breadcrumb::addURL( $current_label );
		}
	}

	/**
	 *
	 */
	public function default_Action()
	{
		$this->_setBreadcrumbNavigation();

		$search_form = UI::searchForm( 'role' );
		$this->view->setVar( 'search_form', $search_form );


		$grid = new UI_dataGrid();

		$grid->setIsPersistent( 'admin_roles_list_grid' );
		$grid->setDefaultSort( 'name' );

		$grid->addColumn( '_edit_', '' )->setAllowSort( false );
		$grid->addColumn( 'id', Tr::_( 'ID' ) );
		$grid->addColumn( 'name', Tr::_( 'Name' ) );
		$grid->addColumn( 'description', Tr::_( 'Description' ) );

		$grid->setData( Role::getList( $search_form->getValue() ) );

		$this->view->setVar( 'grid', $grid );

		$this->render( 'list' );
	}

	/**
	 *
	 */
	public function add_Action()
	{
		$this->_setBreadcrumbNavigation( Tr::_( 'Create a new Role' ) );

		/**
		 * @var Role $role
		 */
		$role = new Role();

		$form = $role->getAddForm();

		if( $role->catchAddForm() ) {
			$role->save();
			$this->logAllowedAction( 'Role created', $role->getId(), $role->getName(), $role );

			UI_messages::success(
				Tr::_( 'Role <b>%ROLE_NAME%</b> has been created', [ 'ROLE_NAME' => $role->getName() ] )
			);

			Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI( $role->getId() ) );
		}


		$this->view->setVar( 'has_access', true );
		$this->view->setVar( 'form', $form );
		$this->view->setVar( 'available_privileges_list', Role::getAvailablePrivilegesList() );

		$this->render( 'edit' );
	}

	/**
	 */
	public function edit_Action()
	{

		/**
		 * @var Role $role
		 */
		$role = $this->getParameter( 'role' );

		$this->_setBreadcrumbNavigation( Tr::_( 'Edit role <b>%ROLE_NAME%</b>', [ 'ROLE_NAME' => $role->getName() ] ) );

		$form = $role->getEditForm();

		if( $role->catchEditForm() ) {
			$role->save();
			$this->logAllowedAction( 'Role updated', $role->getId(), $role->getName(), $role );

			UI_messages::success(
				Tr::_( 'Role <b>%ROLE_NAME%</b> has been updated', [ 'ROLE_NAME' => $role->getName() ] )
			);

			Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI( $role->getId() ) );
		}

		$this->view->setVar( 'form', $form );
		$this->view->setVar( 'role', $role );
		$this->view->setVar( 'available_privileges_list', Role::getAvailablePrivilegesList() );

		$this->render( 'edit' );
	}

	/**
	 *
	 */
	public function view_Action()
	{

		/**
		 * @var Role $role
		 */
		$role = $this->getParameter( 'role' );

		$this->_setBreadcrumbNavigation(
			Tr::_( 'Role detail <b>%ROLE_NAME%</b>', [ 'ROLE_NAME' => $role->getName() ] )
		);

		$form = $role->getEditForm();

		$this->view->setVar( 'has_access', false );
		$this->view->setVar( 'form', $form );
		$this->view->setVar( 'role', $role );
		$this->view->setVar( 'available_privileges_list', Role::getAvailablePrivilegesList() );

		$form->setIsReadonly();

		$this->render( 'edit' );
	}


	/**
	 *
	 */
	public function delete_action()
	{

		/**
		 * @var Role $role
		 */
		$role = $this->getParameter( 'role' );

		$this->_setBreadcrumbNavigation(
			Tr::_( 'Delete role <b>%ROLE_NAME%</b>', [ 'ROLE_NAME' => $role->getName() ] )
		);

		if( Http_Request::POST()->getString( 'delete' )=='yes' ) {
			$role->delete();

			$this->logAllowedAction( 'Role deleted', $role->getId(), $role->getName(), $role );

			UI_messages::info( Tr::_( 'Role <b>%ROLE_NAME%</b> has been deleted', [ 'ROLE_NAME' => $role->getName() ] ) );
			Http_Headers::movedTemporary( Mvc::getCurrentPage()->getURI() );
		}


		$this->view->setVar( 'role', $role );

		$this->render( 'delete-confirm' );
	}


}