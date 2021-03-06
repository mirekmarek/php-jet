<?php

/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\ManageAccess\RESTClients\Roles;

use JetApplication\Auth_RESTClient_Role as Role;

use Jet\Mvc_Controller_Router_AddEditDelete;

use Jet\Mvc_Controller_Default;

use Jet\UI_messages;

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
	 * @var ?Mvc_Controller_Router_AddEditDelete
	 */
	protected ?Mvc_Controller_Router_AddEditDelete $router = null;

	/**
	 * @var ?Role
	 */
	protected ?Role $role = null;


	/**
	 *
	 * @return Mvc_Controller_Router_AddEditDelete
	 */
	public function getControllerRouter(): Mvc_Controller_Router_AddEditDelete
	{
		if( !$this->router ) {
			$this->router = new Mvc_Controller_Router_AddEditDelete(
				$this,
				function( $id ) {
					return (bool)($this->role = Role::get( $id ));
				},
				[
					'listing' => Main::ACTION_GET_ROLE,
					'view'    => Main::ACTION_GET_ROLE,
					'add'     => Main::ACTION_ADD_ROLE,
					'edit'    => Main::ACTION_UPDATE_ROLE,
					'delete'  => Main::ACTION_DELETE_ROLE,
				]
			);
		}

		return $this->router;
	}


	/**
	 * @param string $current_label
	 */
	protected function _setBreadcrumbNavigation( $current_label = '' ): void
	{
		UI_module::initBreadcrumb();

		if( $current_label ) {
			Navigation_Breadcrumb::addURL( $current_label );
		}
	}

	/**
	 *
	 */
	public function listing_Action(): void
	{
		$this->_setBreadcrumbNavigation();

		$listing = new Listing();
		$listing->setDefaultSort( 'name' );
		$listing->handle();

		$this->view->setVar( 'filter_form', $listing->filter_getForm() );
		$this->view->setVar( 'grid', $listing->getGrid() );

		$this->output( 'list' );
	}

	/**
	 *
	 */
	public function add_Action(): void
	{
		$this->_setBreadcrumbNavigation( Tr::_( 'Create a new Role' ) );

		$role = new Role();

		$form = $role->getAddForm();

		if( $role->catchAddForm() ) {
			$role->save();
			$this->logAllowedAction( 'Role created', $role->getId(), $role->getName(), $role );

			UI_messages::success(
				Tr::_( 'Role <b>%ROLE_NAME%</b> has been created', ['ROLE_NAME' => $role->getName()] )
			);

			Http_Headers::reload( ['id' => $role->getId()], ['action'] );
		}


		$this->view->setVar( 'has_access', true );
		$this->view->setVar( 'form', $form );
		$this->view->setVar( 'available_privileges_list', Role::getAvailablePrivilegesList() );

		$this->output( 'edit' );
	}

	/**
	 */
	public function edit_Action(): void
	{
		$role = $this->role;

		$this->_setBreadcrumbNavigation( Tr::_( 'Edit role <b>%ROLE_NAME%</b>', ['ROLE_NAME' => $role->getName()] ) );

		$form = $role->getEditForm();

		if( $role->catchEditForm() ) {
			$role->save();
			$this->logAllowedAction( 'Role updated', $role->getId(), $role->getName(), $role );

			UI_messages::success(
				Tr::_( 'Role <b>%ROLE_NAME%</b> has been updated', ['ROLE_NAME' => $role->getName()] )
			);

			Http_Headers::reload();
		}

		$this->view->setVar( 'form', $form );
		$this->view->setVar( 'role', $role );
		$this->view->setVar( 'available_privileges_list', Role::getAvailablePrivilegesList() );

		$this->output( 'edit' );
	}

	/**
	 *
	 */
	public function view_Action(): void
	{
		$role = $this->role;

		$this->_setBreadcrumbNavigation(
			Tr::_( 'Role detail <b>%ROLE_NAME%</b>', ['ROLE_NAME' => $role->getName()] )
		);

		$form = $role->getEditForm();

		$this->view->setVar( 'has_access', false );
		$this->view->setVar( 'form', $form );
		$this->view->setVar( 'role', $role );
		$this->view->setVar( 'available_privileges_list', Role::getAvailablePrivilegesList() );

		$form->setIsReadonly();

		$this->output( 'edit' );
	}


	/**
	 *
	 */
	public function delete_action(): void
	{
		$role = $this->role;

		$this->_setBreadcrumbNavigation(
			Tr::_( 'Delete role <b>%ROLE_NAME%</b>', ['ROLE_NAME' => $role->getName()] )
		);

		if( Http_Request::POST()->getString( 'delete' ) == 'yes' ) {
			$role->delete();

			$this->logAllowedAction( 'Role deleted', $role->getId(), $role->getName(), $role );

			UI_messages::info( Tr::_( 'Role <b>%ROLE_NAME%</b> has been deleted', ['ROLE_NAME' => $role->getName()] ) );
			Http_Headers::reload( [], [
				'action',
				'id'
			] );
		}


		$this->view->setVar( 'role', $role );

		$this->output( 'delete-confirm' );
	}


}