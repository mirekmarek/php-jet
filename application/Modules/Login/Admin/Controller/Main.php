<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Login\Admin;

use Jet\Logger;
use Jet\Session;
use Jet\Tr;
use Jet\MVC_Controller_Default;
use Jet\Http_Headers;
use Jet\Auth;
use Jet\Navigation_Breadcrumb;
use Jet\UI_messages;


use JetApplication\Auth_Administrator_User as User;

/**
 *
 */
class Controller_Main extends MVC_Controller_Default
{

	/**
	 *
	 */
	public function login_Action(): void
	{
		/**
		 * @var Main $module
		 */
		$module = $this->getModule();
		$form = $module->getLoginForm();

		if( $form->catchInput() ) {
			if( $form->validate() ) {
				$data = $form->getValues();
				if( Auth::login( $data['username'], $data['password'] ) ) {
					Session::regenerateId();
					Http_Headers::reload();
				} else {
					$form->setCommonMessage( Tr::_( 'Invalid username or password!' ) );
				}
			} else {
				$form->setCommonMessage( Tr::_( 'Please enter username and password' ) );
			}
		}

		$this->view->setVar( 'login_form', $form );

		$this->output( 'login' );
	}


	/**
	 *
	 */
	public function is_not_activated_Action(): void
	{
		$this->output( 'is-not-activated' );
	}

	/**
	 *
	 */
	public function is_blocked_Action(): void
	{
		$this->output( 'is-blocked' );
	}

	/**
	 *
	 */
	public function must_change_password_Action(): void
	{
		/**
		 * @var Main $module
		 */
		$module = $this->getModule();
		$form = $module->getMustChangePasswordForm();

		if( $form->catchInput() && $form->validate() ) {
			$data = $form->getValues();
			/**
			 * @var User $user
			 */
			$user = Auth::getCurrentUser();

			$user->setPassword( $data['password'] );
			$user->setPasswordIsValid( true );
			$user->setPasswordIsValidTill( null );
			$user->save();

			Logger::info(
				event: 'password_changed',
				event_message: 'User ' . $user->getUsername() . ' (id:' . $user->getId() . ') changed password',
				context_object_id: $user->getId(),
				context_object_name: $user->getUsername()
			);

			Http_Headers::reload();
		}

		$this->view->setVar( 'form', $form );

		$this->output( 'must-change-password' );
	}

	/**
	 *
	 */
	public function change_password_Action(): void
	{
		/**
		 * @var Main $module
		 */
		$module = $this->getModule();

		$form = $module->getChangePasswordForm();

		Navigation_Breadcrumb::reset();


		Navigation_Breadcrumb::addURL(
			Tr::_( 'Change password', [], Tr::COMMON_DICTIONARY )
		);

		if( $form->catchInput() && $form->validate() ) {
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

				Logger::info(
					event: 'password_changed',
					event_message: 'User ' . $user->getUsername() . ' (id:' . $user->getId() . ') changed password',
					context_object_id: $user->getId(),
					context_object_name: $user->getUsername()
				);

				UI_messages::success( Tr::_( 'Your password has been changed' ) );
			}


			Http_Headers::reload();
		}

		$this->view->setVar( 'form', $form );

		$this->output( 'change-password' );
	}

}