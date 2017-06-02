<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\System\Visitors\Users;

use JetApplication\Mvc_Page;
use JetApplication\Auth_Visitor_User as User;
use JetApplication\Mvc_Controller_AdminStandard;

use Jet\UI;
use Jet\UI_dataGrid;
use Jet\UI_messages;


use Jet\Mvc;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Tr;
use Jet\Form;
use Jet\Navigation_Breadcrumb;

use JetApplicationModule\JetExample\AdminUI\Main as AdminUI_module;

/**
 *
 */
class Controller_Main extends Mvc_Controller_AdminStandard
{

	/**
	 * @var array
	 */
	protected static $ACL_actions_check_map = [
		'default' => Main::ACTION_GET_USER,
		'add'     => Main::ACTION_ADD_USER,
		'edit'    => Main::ACTION_UPDATE_USER,
		'view'    => Main::ACTION_GET_USER,
		'delete'  => Main::ACTION_DELETE_USER,
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
	 */
	public function default_Action()
	{
		$this->_setBreadcrumbNavigation();

		$search_form = UI::searchForm( 'user' );
		$this->view->setVar( 'search_form', $search_form );

		$grid = new UI_dataGrid();

		$grid->setDefaultSort( 'username' );
		$grid->setIsPersistent( 'admin_users_list_grid' );

		$grid->addColumn( '_edit_', '' )->setAllowSort( false );
		$grid->addColumn( 'id', Tr::_( 'ID' ) );
		$grid->addColumn( 'username', Tr::_( 'Username' ) );
		$grid->addColumn( 'first_name', Tr::_( 'First name' ) );
		$grid->addColumn( 'surname', Tr::_( 'Surname' ) );

		$grid->setData( User::getList( null, $search_form->getValue() ) );

		$this->view->setVar( 'grid', $grid );

		$this->render( 'list' );

	}

	/**
	 * @param string $current_label
	 */
	protected function _setBreadcrumbNavigation( $current_label = '' )
	{

		AdminUI_module::initBreadcrumb();

		if( $current_label ) {
			Navigation_Breadcrumb::addURL( $current_label );
		}
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
			$user->save();

			$this->logAllowedAction( 'User created', $user->getId(), $user->getUsername(), $user );

			$user->sendWelcomeEmail( $form->getField( 'password' )->getValue() );
			UI_messages::success(
				Tr::_( 'User <b>%USERNAME%</b> has been created', [ 'USERNAME' => $user->getUsername() ] )
			);

			Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI( $user->getId() ) );
		}

		$this->view->setVar( 'form', $form );
		$this->view->setVar( 'user', $user );

		$this->render( 'edit' );

	}

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
	 *
	 */
	public function edit_Action()
	{

		/**
		 * @var User $user
		 */
		$user = $this->getParameter( 'user' );

		$GET = Http_Request::GET();
		if( ( $action = $GET->getString( 'a' ) ) ) {
			if( $action=='reset_password' ) {
				$user->resetPassword();
				UI_messages::success( Tr::_( 'Password has been re-generated', [ 'USERNAME' => $user->getUsername() ] ) );
			}

			Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI( $user->getId() ) );
		}


		$this->_setBreadcrumbNavigation(
			Tr::_( 'Edit user account <b>%USERNAME%</b>', [ 'USERNAME' => $user->getUsername() ] )
		);

		/**
		 * @var Form $form
		 */
		$form = $user->getEditForm();
		$form->removeField( 'password' );

		if( $user->catchEditForm() ) {

			$user->save();
			$this->logAllowedAction( 'User updated', $user->getId(), $user->getUsername(), $user );
			UI_messages::success(
				Tr::_( 'User <b>%USERNAME%</b> has been updated', [ 'USERNAME' => $user->getUsername() ] )
			);

			Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI( $user->getId() ) );
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

		/**
		 * @var User $user
		 */
		$user = $this->getParameter( 'user' );

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

		/**
		 * @var User $user
		 */
		$user = $this->getParameter( 'user' );

		$this->_setBreadcrumbNavigation(
			Tr::_( 'Delete user account <b>%USERNAME%</b>', [ 'USERNAME' => $user->getUsername() ] )
		);

		if( Http_Request::POST()->getString( 'delete' )=='yes' ) {
			$user->delete();
			$this->logAllowedAction( 'User deleted', $user->getId(), $user->getUsername(), $user );
			UI_messages::info(
				Tr::_( 'User <b>%USERNAME%</b> has been deleted', [ 'USERNAME' => $user->getUsername() ] )
			);

			Http_Headers::movedTemporary( Mvc::getCurrentPage()->getURI() );
		}


		$this->view->setVar( 'user', $user );

		$this->render( 'delete-confirm' );
	}

}