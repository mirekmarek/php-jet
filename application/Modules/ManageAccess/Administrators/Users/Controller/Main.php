<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\ManageAccess\Administrators\Users;

use Jet\Logger;
use JetApplication\Auth_Administrator_User as User;

use Jet\MVC_Controller_Router_AddEditDelete;
use Jet\UI_messages;
use Jet\MVC_Controller_Default;
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
	 * @var ?User
	 */
	protected ?User $user = null;

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
					return (bool)($this->user = User::get( (int)$id ));
				},
				[
					'listing' => Main::ACTION_GET_USER,
					'view'    => Main::ACTION_GET_USER,
					'add'     => Main::ACTION_ADD_USER,
					'edit'    => Main::ACTION_UPDATE_USER,
					'delete'  => Main::ACTION_DELETE_USER,
				]
			);

			$this->router->addAction( 'reset_password', Main::ACTION_UPDATE_USER )
				->setResolver( function() {
					return (
						Http_Request::GET()->getString( 'action' ) == 'reset_password' &&
						($this->user = User::get( Http_Request::GET()->getInt( 'id' ) ))
					);
				} )
				->setURICreator( function( $id ) {
					return Http_Request::currentURI( [
						'id' => $id,
						'action' => 'reset_password'
					] );
				} );
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
		Navigation_Breadcrumb::addURL( Tr::_( 'Create a new User' ) );

		$user = new User();


		$form = $user->getAddForm();

		if( $user->catchAddForm() ) {
			$password = User::generatePassword();
			$user->setPassword( $password );
			$user->save();

			Logger::success(
				event: 'admin_user_created',
				event_message: 'User created',
				context_object_id: $user->getId(),
				context_object_name: $user->getUsername(),
				context_object_data: $user
			);


			$user->sendWelcomeEmail( $password );

			UI_messages::success(
				Tr::_( 'User <b>%USERNAME%</b> has been created', ['USERNAME' => $user->getUsername()] )
			);

			Http_Headers::reload( ['id' => $user->getId()], ['action'] );
		}

		$this->view->setVar( 'form', $form );
		$this->view->setVar( 'user', $user );

		$this->output( 'edit' );

	}

	/**
	 *
	 */
	public function reset_password_Action(): void
	{
		$user = $this->user;

		$user->resetPassword();
		UI_messages::success( Tr::_( 'Password has been re-generated', ['USERNAME' => $user->getUsername()] ) );
		Http_Headers::reload( [], ['action'] );

	}

	/**
	 *
	 */
	public function edit_Action(): void
	{
		$user = $this->user;
		
		Navigation_Breadcrumb::addURL( Tr::_( 'Edit user account <b>%USERNAME%</b>', ['USERNAME' => $user->getUsername()] ) );

		$form = $user->getEditForm();

		if( $user->catchEditForm() ) {

			$user->save();


			Logger::success(
				event: 'admin_user_updated',
				event_message: 'User updated',
				context_object_id: $user->getId(),
				context_object_name: $user->getUsername(),
				context_object_data: $user
			);

			UI_messages::success(
				Tr::_( 'User <b>%USERNAME%</b> has been updated', ['USERNAME' => $user->getUsername()] )
			);

			Http_Headers::reload();
		}

		$this->view->setVar( 'form', $form );
		$this->view->setVar( 'user', $user );

		$this->output( 'edit' );

	}

	/**
	 *
	 */
	public function view_Action(): void
	{
		$user = $this->user;
		
		Navigation_Breadcrumb::addURL(
			Tr::_( 'User account detail <b>%USERNAME%</b>', ['USERNAME' => $user->getUsername()] )
		);

		$form = $user->getEditForm();

		$form->setIsReadonly();

		$this->view->setVar( 'form', $form );
		$this->view->setVar( 'user', $user );

		$this->output( 'edit' );

	}

	/**
	 *
	 */
	public function delete_Action(): void
	{
		$user = $this->user;
		
		Navigation_Breadcrumb::addURL(
			Tr::_( 'Delete user account <b>%USERNAME%</b>', ['USERNAME' => $user->getUsername()] )
		);

		if( Http_Request::POST()->getString( 'delete' ) == 'yes' ) {
			$user->delete();

			Logger::success(
				event: 'admin_user_deleted',
				event_message: 'User deleted',
				context_object_id: $user->getId(),
				context_object_name: $user->getUsername(),
				context_object_data: $user
			);

			UI_messages::info(
				Tr::_( 'User <b>%USERNAME%</b> has been deleted', ['USERNAME' => $user->getUsername()] )
			);

			Http_Headers::reload( [], [
				'action',
				'id'
			] );
		}


		$this->view->setVar( 'user', $user );

		$this->output( 'delete-confirm' );
	}

}