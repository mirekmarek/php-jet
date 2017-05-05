<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\LoginForm;

use Jet\Auth;
use Jet\Application_Log;
use Jet\Mvc_Controller_Standard;
use Jet\Form;
use Jet\Tr;
use Jet\Http_Headers;
use JetUI\messages;
use JetUI\breadcrumbNavigation;

use JetExampleApp\Mvc_Page;
use JetExampleApp\Auth_Administrator_User as User;

/**
 *
 */
class Controller_SystemPages extends Mvc_Controller_Standard
{
	/**
	 * @var array
	 */
	protected static $ACL_actions_check_map = [
		'change_password' => false,
	];
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	/**
	 *
	 */
	public function initialize()
	{
	}

	/**
	 *
	 */
	public function change_password_Action()
	{
		/**
		 * @var Form $form
		 */
		$form = $this->module_instance->getChangePasswordForm();

		breadcrumbNavigation::addItem(
			Tr::_( 'Home page', [], Tr::COMMON_NAMESPACE ), Mvc_Page::get( Mvc_Page::ADMIN_HOMEPAGE_ID )->getURL()
		);
		breadcrumbNavigation::addItem(
			Tr::_( 'Change password', [], Tr::COMMON_NAMESPACE ),
			Mvc_Page::get( Mvc_Page::CHANGE_PASSWORD_ID )->getURL()
		);

		if( $form->catchValues()&&$form->validateValues() ) {
			$data = $form->getValues();
			/**
			 * @var User $user
			 */
			$user = Auth::getCurrentUser();

			if( !$user->verifyPassword( $data['current_password'] ) ) {
				messages::danger( Tr::_( 'Current password do not match' ) );
			} else {

				$user->setPassword( $data['password'] );
				$user->setPasswordIsValid( true );
				$user->setPasswordIsValidTill( null );
				$user->save();

				Application_Log::info(
					'password_changed', 'User password changed', $user->getId(), $user->getUsername(), $user
				);

				messages::success( Tr::_( 'Your password has been changed' ) );
			}


			Http_Headers::reload();
		}

		$this->view->setVar( 'form', $form );

		$this->render( 'change-password' );
	}
}