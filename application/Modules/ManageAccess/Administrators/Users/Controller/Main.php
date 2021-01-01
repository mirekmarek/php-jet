<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\ManageAccess\Administrators\Users;

use JetApplication\Auth_Administrator_User as User;

use Jet\Mvc_Controller_Router_AddEditDelete;
use Jet\UI_messages;
use Jet\Mvc_Controller_Default;
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
	 * @var ?User
	 */
	protected ?User $user = null;

	/**
	 *
	 * @return Mvc_Controller_Router_AddEditDelete
	 */
	public function getControllerRouter() : Mvc_Controller_Router_AddEditDelete
	{
		if( !$this->router ) {
			$this->router = new Mvc_Controller_Router_AddEditDelete(
				$this,
				function($id) {
					return (bool)($this->user = User::get($id));
				},
				[
					'listing'=> Main::ACTION_GET_USER,
					'view'   => Main::ACTION_GET_USER,
					'add'    => Main::ACTION_ADD_USER,
					'edit'   => Main::ACTION_UPDATE_USER,
					'delete' => Main::ACTION_DELETE_USER,
				]
			);

			$this->router->addAction('reset_password', Main::ACTION_UPDATE_USER)
				->setResolver( function() {
					return (
						Http_Request::GET()->getString('action')=='reset_password' &&
						($this->user = User::get(Http_Request::GET()->getInt('id')))
					);
				} )
				->setURICreator( function($id) {
					return Http_Request::currentURI(['id'=>$id, 'action'=>'reset_password']);
				});
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
	public function listing_Action()
	{
		$this->_setBreadcrumbNavigation();

		$listing = new Listing();
		$listing->handle();

		$this->view->setVar( 'filter_form', $listing->filter_getForm());
		$this->view->setVar( 'grid', $listing->getGrid() );

		$this->render( 'list' );
	}

	/**
	 *
	 */
	public function add_Action()
	{
		$this->_setBreadcrumbNavigation( Tr::_( 'Create a new User' ) );

		$user = new User();


		$form = $user->getAddForm();

		if( $user->catchAddForm() ) {
			$password = User::generatePassword();
			$user->setPassword( $password );
			$user->save();

			$this->logAllowedAction( 'User created', $user->getId(), $user->getUsername(), $user );

			$user->sendWelcomeEmail( $password );

			UI_messages::success(
				Tr::_( 'User <b>%USERNAME%</b> has been created', [ 'USERNAME' => $user->getUsername() ] )
			);

			Http_Headers::reload( ['id'=>$user->getId()], ['action'] );
		}

		$this->view->setVar( 'form', $form );
		$this->view->setVar( 'user', $user );

		$this->render( 'edit' );

	}

	/**
	 *
	 */
	public function reset_password_Action()
	{
		$user = $this->user;

		$user->resetPassword();
		UI_messages::success( Tr::_( 'Password has been re-generated', [ 'USERNAME' => $user->getUsername() ] ) );
		Http_Headers::reload( [], ['action'] );

	}

	/**
	 *
	 */
	public function edit_Action()
	{
		$user = $this->user;

		$this->_setBreadcrumbNavigation( Tr::_( 'Edit user account <b>%USERNAME%</b>', [ 'USERNAME' => $user->getUsername() ] ) );

		$form = $user->getEditForm();

		if( $user->catchEditForm() ) {

			$user->save();
			$this->logAllowedAction( 'User updated', $user->getId(), $user->getUsername(), $user );

			UI_messages::success(
				Tr::_( 'User <b>%USERNAME%</b> has been updated', [ 'USERNAME' => $user->getUsername() ] )
			);

			Http_Headers::reload();
		}

		$this->view->setVar( 'form', $form );
		$this->view->setVar( 'user', $user );

		$this->render( 'edit' );

	}

	/**
	 *
	 */
	public function view_Action()
	{
		$user = $this->user;

		$this->_setBreadcrumbNavigation(
			Tr::_( 'User account detail <b>%USERNAME%</b>', [ 'USERNAME' => $user->getUsername() ] )
		);

		$form = $user->getEditForm();

		$form->setIsReadonly();

		$this->view->setVar( 'form', $form );
		$this->view->setVar( 'user', $user );

		$this->render( 'edit' );

	}

	/**
	 *
	 */
	public function delete_Action()
	{
		$user = $this->user;

		$this->_setBreadcrumbNavigation(
			Tr::_( 'Delete user account <b>%USERNAME%</b>', [ 'USERNAME' => $user->getUsername() ] )
		);

		if( Http_Request::POST()->getString( 'delete' )=='yes' ) {
			$user->delete();
			$this->logAllowedAction( 'User deleted', $user->getId(), $user->getUsername(), $user );

			UI_messages::info(
				Tr::_( 'User <b>%USERNAME%</b> has been deleted', [ 'USERNAME' => $user->getUsername() ] )
			);

			Http_Headers::reload([], ['action', 'id']);
		}


		$this->view->setVar( 'user', $user );

		$this->render( 'delete-confirm' );
	}

}