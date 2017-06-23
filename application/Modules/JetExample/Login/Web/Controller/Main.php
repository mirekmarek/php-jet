<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Login\Web;

use Jet\Application_Log;
use Jet\Session;
use Jet\Tr;
use Jet\Mvc_Controller_Default;
use Jet\Form;
use Jet\Http_Headers;
use Jet\Auth;

use JetApplication\Auth_Visitor_User as User;

/**
 *
 */
class Controller_Main extends Mvc_Controller_Default
{
	/**
	 *
	 * @var Main
	 */
	protected $module = null;

	/**
	 *
	 * @return bool
	 */
	public function checkAccess()
	{
		return true;
	}


	/**
	 *
	 */
	public function login_Action()
	{

		/**
		 * @var Form $form
		 */
		$form = $this->module->getLoginForm();

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

		$this->render( 'login' );
	}


	/**
	 *
	 */
	public function is_not_activated_Action()
	{
		$this->render( 'is-not-activated' );
	}

	/**
	 *
	 */
	public function is_blocked_Action()
	{
		$this->render( 'is-blocked' );
	}

	/**
	 *
	 */
	public function must_change_password_Action()
	{
		/**
		 * @var Form $form
		 */
		$form = $this->module->getMustChangePasswordForm();

		if( $form->catchInput()&&$form->validate() ) {
			$data = $form->getValues();
			/**
			 * @var User $user
			 */
			$user = Auth::getCurrentUser();

			if( !$user->verifyPassword( $data['password'] ) ) {
				$user->setPassword( $data['password'] );
				$user->setPasswordIsValid( true );
				$user->setPasswordIsValidTill( null );
				$user->save();

				Application_Log::info(
					'password_changed', 'User password changed', $user->getId(), $user->getUsername(), $user
				);

				Http_Headers::reload();
			} else {
				$form->getField( 'password' )->setCustomError( Tr::_( 'Please enter <strong>new</strong> password' ) );
			}
		}

		$this->view->setVar( 'form', $form );

		$this->render( 'must-change-password' );
	}
}