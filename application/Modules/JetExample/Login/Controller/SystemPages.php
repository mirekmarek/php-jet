<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Login;

use Jet\Auth;
use Jet\Application_Log;
use Jet\Mvc_Controller_Default;
use Jet\Form;
use Jet\Tr;
use Jet\Http_Headers;
use Jet\Navigation_Breadcrumb;

use Jet\UI_messages;

use JetApplication\Mvc_Page;
use JetApplication\Auth_Administrator_User as User;

/**
 *
 */
class Controller_SystemPages extends Mvc_Controller_Default
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
	protected $module = null;

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
		$form = $this->module->getChangePasswordForm();

		Navigation_Breadcrumb::reset();

		Navigation_Breadcrumb::addURL(
			Tr::_( 'Home page', [], Tr::COMMON_NAMESPACE ),
			Mvc_Page::get( Mvc_Page::ADMIN_HOMEPAGE_ID )->getURL()
		);

		Navigation_Breadcrumb::addURL(
			Tr::_( 'Change password', [], Tr::COMMON_NAMESPACE ),
			Mvc_Page::get( Mvc_Page::CHANGE_PASSWORD_ID )->getURL()
		);

		if( $form->catchInput()&&$form->validate() ) {
			$data = $form->getValues();
			/**
			 * @var User $user
			 */
			$user = Auth::getCurrentUser();

			if( !$user->verifyPassword( $data['current_password'] ) ) {
				UI_messages::danger( Tr::_( 'Current password do not match' ) );
			} else {

				$user->setPassword( $data['password'] );
				$user->setPasswordIsValid( true );
				$user->setPasswordIsValidTill( null );
				$user->save();

				Application_Log::info(
					'password_changed', 'User password changed', $user->getId(), $user->getUsername(), $user
				);

				UI_messages::success( Tr::_( 'Your password has been changed' ) );
			}


			Http_Headers::reload();
		}

		$this->view->setVar( 'form', $form );

		$this->render( 'change-password' );
	}
}