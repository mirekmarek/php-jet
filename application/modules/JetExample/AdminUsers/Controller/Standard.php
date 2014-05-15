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
 * @package JetApplicationModule_AdminUsers
 * @subpackage JetApplicationModule_AdminUsers_Controller
 */
namespace JetApplicationModule\JetExample\AdminUsers;
use Jet;
use JetApplicationModule\JetExample\UIElements;

class Controller_Standard extends Jet\Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	protected static $ACL_actions_check_map = array(
		'default' => 'get_user'
	);


	/**
	 *
	 */
	public function default_Action() {

		Jet\Mvc::setProvidesDynamicContent();

		$GET = Jet\Http_Request::GET();


		if( $delete_ID = $GET->getString('delete')) {
			$user = Jet\Auth::getUser( $delete_ID );
			if($user) {
				$this->handleDelete($user);

				return;
			}
		}

		$user = false;

		if($GET->exists('new')) {
			$user = Jet\Auth_Factory::getUserInstance();
		} else if( $GET->exists('ID') ) {
			$user = Jet\Auth::getUser( $GET->getString('ID') );
		}

		if($user) {
			$this->handleEdit($user);
		} else {
			$this->handleList();
		}

	}

	/**
	 * @param Jet\Auth_User_Abstract $user
	 */
	public function handleDelete( Jet\Auth_User_Abstract $user ) {
		if( !$this->module_instance->checkAclCanDoAction('delete_user') ) {
			return;
		}

		if( Jet\Http_Request::POST()->getString('delete')=='yes' ) {
			$user->delete();
			Jet\Http_Headers::movedTemporary('?');
		}


		$this->getUIManagerModuleInstance()->addBreadcrumbNavigationData('Delete user');

		$this->view->setVar( 'user', $user );

		$this->render('classic/delete-confirm');
	}


	/**
	 * @param Jet\Auth_User_Abstract $user
	 */
	protected function handleEdit( Jet\Auth_User_Abstract $user ) {
		$has_access = false;

		if($user->getIsNew()) {
			if( !$this->module_instance->checkAclCanDoAction('add_user') ) {
				return;
			}
            $has_access = true;
		} else {
			if( $this->module_instance->checkAclCanDoAction('update_user') ) {
				$has_access = true;
			}
		}


		$form = $user->getCommonForm();

		if( $has_access ) {
			if( $user->catchForm( $form ) ) {
				$user->validateProperties();
				$user->save();
				Jet\Http_Headers::movedTemporary( '?ID='.$user->getID() );
			}
		}


		if($user->getIsNew()) {
			$this->view->setVar('bnt_label', 'ADD');

			$this->getUIManagerModuleInstance()->addBreadcrumbNavigationData('New user');

		} else {
			$this->view->setVar('bnt_label', 'SAVE' );

			$this->getUIManagerModuleInstance()->addBreadcrumbNavigationData( $user->getLogin() );
		}


		$this->view->setVar('has_access', $has_access);
		$this->view->setVar('form', $form);
		$this->getUIManagerModuleInstance()->breadcrumbNavigationShift( -3 );

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

		$grid->setIsPersistent('admin_classic_users_list_grid');

		$grid->addColumn('_edit_', '')->setAllowSort(false);
		$grid->addColumn('login', Jet\Tr::_('Login') );
		$grid->addColumn('ID', Jet\Tr::_('ID') );

		$grid->setData( Jet\Auth::getUsersList() );

		$this->view->setVar('can_add_user', $this->module_instance->checkAclCanDoAction('add_user'));
		$this->view->setVar('can_delete_user', $this->module_instance->checkAclCanDoAction('delete_user'));
		$this->view->setVar('can_update_user', $this->module_instance->checkAclCanDoAction('update_user'));
		$this->view->setVar('grid', $grid);

		$this->render('classic/default');
	}

}