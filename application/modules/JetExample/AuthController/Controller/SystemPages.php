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
use JetShop\Admin\Custom\User;
use JetShop\Admin\Custom\Page;
use JetUI\messages;
use JetUI_breadcrumbNavigation;

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

		UI_breadcrumbNavigation::addItem( Tr::_('Home page', [], Tr::COMMON_NAMESPACE), Page::get(Page::HOMEPAGE_ID)->getURL() );
		UI_breadcrumbNavigation::addItem( Tr::_('Change password', [], Tr::COMMON_NAMESPACE), Page::get(Page::CHANGE_PASSWORD_ID)->getURL() );

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
				UI_messages::danger( Tr::_('Current password do not match') );
			} else {

				$user->setPassword( $data['password'] );
				$user->setPasswordIsValid(true);
				$user->setPasswordIsValidTill(null);
				$user->save();

				UI_messages::success( Tr::_('Your password has been changed') );
			}


			Http_Headers::reload();
		}

		$this->view->setVar('form', $form);

		$this->render('change-password');
	}
}