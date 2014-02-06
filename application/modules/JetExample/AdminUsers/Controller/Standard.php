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
	protected function handleEdit( Jet\Auth_User_Abstract $user ) {
		$has_access = false;

		if($user->getIsNew()) {
			if( $this->module_instance->checkAclCanDoAction('add_user') ) {
				$has_access = true;
			}
		} else {
			if( $this->module_instance->checkAclCanDoAction('update_user') ) {
				$has_access = true;
			}
		}

		if(!$has_access) {
			//TODO:
			return;
		}

		$form = $user->getCommonForm();

		if( $user->catchForm( $form ) ) {
			$user->validateProperties();
			$user->save();
			Jet\Http_Headers::movedTemporary( '?ID='.$user->getID() );
		}


		if($user->getIsNew()) {
			$this->view->setVar('bnt_label', 'ADD');

			$this->getUIManagerModuleInstance()->addBreadcrumbNavigationData('New user');

		} else {
			$this->view->setVar('bnt_label', 'SAVE' );

			$this->getUIManagerModuleInstance()->addBreadcrumbNavigationData( $user->getLogin() );
		}


		$this->view->setVar('form', $form);
		$this->getUIManagerModuleInstance()->breadcrumbNavigationShift( -3 );

		$this->render('classic/edit');

	}

	/**
	 *
	 */
	protected function handleList() {
		$p = new Jet\Data_Paginator(
			Jet\Http_Request::GET()->getInt('p', 1),
			10,
			'?p='.Jet\Data_Paginator::URL_PAGE_NO_KEY
		);
		$p->setDataSource( Jet\Auth::getUsersList() );

		$this->getUIManagerModuleInstance()->breadcrumbNavigationShift( -2 );

		$this->view->setVar('users', $p->getData());
		$this->view->setVar('paginator', $p);

		$this->render('classic/default');
	}

}