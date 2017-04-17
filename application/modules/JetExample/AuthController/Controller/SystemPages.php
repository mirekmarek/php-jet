<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\AuthController;
use Jet\Mvc_Controller_Standard;
use Jet\Form;
use Jet\Tr;
use Jet\Http_Headers;
use JetUI\messages;
use JetUI\breadcrumbNavigation;

use JetExampleApp\Mvc_Page;
use JetExampleApp\Auth_Administrator_User as User;

class Controller_SystemPages extends Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	/**
	 * @var array
	 */
	protected static $ACL_actions_check_map = [
		'change_password' => false
	];

	/**
	 *
	 */
	public function initialize() {
	}

    /**
     *
     */
	public function change_password_Action() {
		/**
		 * @var Form $form
		 */
		$form = $this->module_instance->getChangePasswordForm();

		breadcrumbNavigation::addItem( Tr::_('Home page', [], Tr::COMMON_NAMESPACE), Mvc_Page::get(Mvc_Page::ADMIN_HOMEPAGE_ID)->getURL() );
		breadcrumbNavigation::addItem( Tr::_('Change password', [], Tr::COMMON_NAMESPACE), Mvc_Page::get(Mvc_Page::CHANGE_PASSWORD_ID)->getURL() );

		if(
			$form->catchValues() &&
			$form->validateValues()
		) {
			$data = $form->getValues();
			/**
			 * @var User $user
			 */
			$user = $this->module_instance->getCurrentUser();

			if(!$user->verifyPassword($data['current_password'])) {
				messages::danger( Tr::_('Current password do not match') );
			} else {

				$user->setPassword( $data['password'] );
				$user->setPasswordIsValid(true);
				$user->setPasswordIsValidTill(null);
				$user->save();

				messages::success( Tr::_('Your password has been changed') );
			}


			Http_Headers::reload();
		}

		$this->view->setVar('form', $form);

		$this->render('change-password');
	}
}