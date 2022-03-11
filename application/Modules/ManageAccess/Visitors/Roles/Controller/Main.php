<?php

/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\ManageAccess\Visitors\Roles;

use Jet\Logger;
use JetApplication\Auth_Visitor_Role as Role;

use Jet\MVC_Controller_Router_AddEditDelete;

use Jet\MVC_Controller_Default;

use Jet\UI_messages;

use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Tr;
use Jet\Navigation_Breadcrumb;

/**
 *
 */
class Controller_Main extends MVC_Controller_Default
{

	/**
	 * @var ?MVC_Controller_Router_AddEditDelete
	 */
	protected ?MVC_Controller_Router_AddEditDelete $router = null;

	/**
	 * @var ?Role
	 */
	protected ?Role $role = null;


	/**
	 *
	 * @return MVC_Controller_Router_AddEditDelete
	 */
	public function getControllerRouter(): MVC_Controller_Router_AddEditDelete
	{
		if( !$this->router ) {
			$this->router = new MVC_Controller_Router_AddEditDelete(
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
	 *
	 */
	public function listing_Action(): void
	{
		$listing = new Listing();
		$listing->handle();

		$this->view->setVar( 'filter_form', $listing->getFilterForm() );
		$this->view->setVar( 'grid', $listing->getGrid() );

		$this->output( 'list' );
	}

	/**
	 *
	 */
	public function add_Action(): void
	{
		Navigation_Breadcrumb::addURL( Tr::_( 'Create a new Role' ) );

		$role = new Role();

		$form = $role->getAddForm();

		if( $role->catchAddForm() ) {
			$role->save();

			Logger::success(
				event: 'visitor_role_created',
				event_message: 'Role created',
				context_object_id: $role->getId(),
				context_object_name: $role->getName(),
				context_object_data: $role
			);

			UI_messages::success(
				Tr::_( 'Role <b>%ROLE_NAME%</b> has been created', ['ROLE_NAME' => $role->getName()] )
			);

			Http_Headers::reload( ['id' => $role->getId()], ['action'] );
		}


		$this->view->setVar( 'has_access', true );
		$this->view->setVar( 'form', $form );
		$this->view->setVar( 'available_privileges_list', array_keys(Role::getAvailablePrivilegesList()) );

		$this->output( 'edit' );
	}

	/**
	 */
	public function edit_Action(): void
	{
		$role = $this->role;
		
		Navigation_Breadcrumb::addURL( Tr::_( 'Edit role <b>%ROLE_NAME%</b>', ['ROLE_NAME' => $role->getName()] ) );

		$form = $role->getEditForm();

		if( $role->catchEditForm() ) {
			$role->save();

			Logger::success(
				event: 'visitor_role_updated',
				event_message: 'Role updated',
				context_object_id: $role->getId(),
				context_object_name: $role->getName(),
				context_object_data: $role
			);

			UI_messages::success(
				Tr::_( 'Role <b>%ROLE_NAME%</b> has been updated', ['ROLE_NAME' => $role->getName()] )
			);

			Http_Headers::reload();
		}

		$this->view->setVar( 'form', $form );
		$this->view->setVar( 'role', $role );
		$this->view->setVar( 'available_privileges_list', array_keys(Role::getAvailablePrivilegesList()) );

		$this->output( 'edit' );
	}

	/**
	 *
	 */
	public function view_Action(): void
	{
		$role = $this->role;
		
		Navigation_Breadcrumb::addURL(
			Tr::_( 'Role detail <b>%ROLE_NAME%</b>', ['ROLE_NAME' => $role->getName()] )
		);

		$form = $role->getEditForm();

		$this->view->setVar( 'has_access', false );
		$this->view->setVar( 'form', $form );
		$this->view->setVar( 'role', $role );
		$this->view->setVar( 'available_privileges_list', array_keys(Role::getAvailablePrivilegesList()) );

		$form->setIsReadonly();

		$this->output( 'edit' );
	}


	/**
	 *
	 */
	public function delete_action(): void
	{
		$role = $this->role;
		
		Navigation_Breadcrumb::addURL(
			Tr::_( 'Delete role <b>%ROLE_NAME%</b>', ['ROLE_NAME' => $role->getName()] )
		);

		if( Http_Request::POST()->getString( 'delete' ) == 'yes' ) {
			$role->delete();

			Logger::success(
				event: 'visitor_role_deleted',
				event_message: 'Role deleted',
				context_object_id: $role->getId(),
				context_object_name: $role->getName(),
				context_object_data: $role
			);

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