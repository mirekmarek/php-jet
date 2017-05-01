<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Admin\Visitors\Users;

use JetExampleApp\Auth_Visitor_User as User;
use JetExampleApp\Mvc_Controller_AdminStandard;

use JetUI\UI;
use JetUI\dataGrid;
use JetUI\breadcrumbNavigation;
use JetUI\messages;


use Jet\Mvc;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Tr;
use Jet\Form;

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
		'default' => Main::ACTION_GET_USER,
		'add' => Main::ACTION_ADD_USER,
		'edit' => Main::ACTION_UPDATE_USER,
		'view' => Main::ACTION_GET_USER,
		'delete' => Main::ACTION_DELETE_USER,
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
		$menu_item = AdminUI_module::getMenuItems()['system/visitor_roles'];

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

		$search_form = UI::searchForm('user');
		$this->view->setVar('search_form', $search_form);

		$grid = new dataGrid();

		$grid->setDefaultSort('login');
		$grid->setIsPersistent('admin_users_list_grid');

		$grid->addColumn('_edit_', '')->setAllowSort(false);
		$grid->addColumn('id', Tr::_('ID') );
		$grid->addColumn('login', Tr::_('Login') );
		$grid->addColumn('first_name', Tr::_('First name') );
		$grid->addColumn('surname', Tr::_('Surname') );

		$grid->setData( User::getList(null, $search_form->getValue()) );

		$this->view->setVar('grid', $grid);

		$this->render('default');

	}

	/**
	 *
	 */
	public function add_Action() {
		$this->_setBreadcrumbNavigation( Tr::_('Create a new User') );

		$user = new User();


		$form = $user->getEditForm();

		if( $user->catchForm( $form ) ) {
			$user->save();

			$this->logAllowedAction( 'User created', $user->getId(), $user->getLogin(), $user );

			$user->sendWelcomeEmail( $form->getField('password')->getValue() );
			messages::success( Tr::_('User <b>%LOGIN%</b> has been created', ['LOGIN'=>$user->getLogin() ]) );

			Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI($user->getId()) );
		}

		$this->view->setVar('form', $form);
		$this->view->setVar('user', $user);

		$this->render('edit');

	}

	/**
	 *
	 */
	public function edit_Action() {

		/**
		 * @var User $user
		 */
		$user = $this->getActionParameterValue('user');

		$GET = Http_Request::GET();
		if(($action=$GET->getString('a'))) {
			if($action=='reset_password') {
				$user->resetPassword();
				messages::success( Tr::_('Password has been re-generated', ['LOGIN'=>$user->getLogin() ]) );
			}

			Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI($user->getId()) );
		}


		$this->_setBreadcrumbNavigation( Tr::_('Edit user account <b>%LOGIN%</b>', ['LOGIN'=>$user->getLogin() ]) );

		/**
		 * @var Form $form
		 */
		$form = $user->getEditForm();
		$form->removeField('password');

		if( $user->catchForm( $form ) ) {

			$user->save();
			$this->logAllowedAction( 'User updated', $user->getId(), $user->getLogin(), $user );
			messages::success( Tr::_('User <b>%LOGIN%</b> has been updated', ['LOGIN'=>$user->getLogin() ]) );

			Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI($user->getId()) );
		}

		$this->view->setVar('form', $form);
		$this->view->setVar('user', $user);

		$this->render('edit');

	}

	/**
	 *
	 */
	public function view_Action() {

		/**
		 * @var User $user
		 */
		$user = $this->getActionParameterValue('user');

		$this->_setBreadcrumbNavigation( Tr::_('User account detail <b>%LOGIN%</b>', ['LOGIN'=>$user->getLogin() ]) );

		$form = $user->getEditForm();

		$form->setIsReadonly();

		$this->view->setVar('form', $form);
		$this->view->setVar('user', $user);

		$this->render('edit');

	}

	/**
	 *
	 */
	public function delete_Action() {

		/**
		 * @var User $user
		 */
		$user = $this->getActionParameterValue('user');

		$this->_setBreadcrumbNavigation( Tr::_('Delete user account <b>%LOGIN%</b>', ['LOGIN'=>$user->getLogin() ]) );

		if( Http_Request::POST()->getString('delete')=='yes' ) {
			$user->delete();
			$this->logAllowedAction( 'User deleted', $user->getId(), $user->getLogin(), $user );
			messages::info( Tr::_('User <b>%LOGIN%</b> has been deleted', ['LOGIN'=>$user->getLogin() ]) );

			Http_Headers::movedTemporary( Mvc::getCurrentPage()->getURI() );
		}


		$this->view->setVar( 'user', $user );

		$this->render('delete-confirm');
	}

}