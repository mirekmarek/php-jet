<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\Login\Admin;

use Jet\Application_Module;

use Jet\Auth;

use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Form_Field_Password;
use Jet\Form_Field_RegistrationPassword;


use JetApplication\Auth_Administrator_User as Administrator;


/**
 *
 */
class Main extends Application_Module
{

	/**
	 *
	 * @return Form
	 */
	public function getLoginForm()
	{
		$username_field = new Form_Field_Input( 'username', 'Username: ' );
		$username_field->setErrorMessages(
			[
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter username',
			]
		);
		$password_field = new Form_Field_Password( 'password', 'Password:' );
		$password_field->setErrorMessages(
			[
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter password',
			]
		);

		$form = new Form(
			'login', [
				$username_field,
				$password_field,
			]
		);

		$form->getField( 'username' )->setIsRequired( true );
		/**
		 * @var Form_Field_Password $password
		 */
		$password = $form->getField( 'password' );
		$password->setIsRequired( true );

		return $form;
	}

	/**
	 * @return Form
	 */
	public function getChangePasswordForm()
	{
		$user = new Administrator();

		$current_password = new Form_Field_Password( 'current_password', 'Current password' );
		$current_password->setIsRequired( true );
		$current_password->setErrorMessages(
			[
				Form_Field_RegistrationPassword::ERROR_CODE_EMPTY => 'Please enter new password',
			]
		);

		$new_password = new Form_Field_RegistrationPassword( 'password', 'New password' );
		$new_password->setPasswordConfirmationLabel( 'Confirm new password' );

		$new_password->setPasswordStrengthCheckCallback( [
			$user,
			'verifyPasswordStrength'
		] );

		$new_password->setIsRequired( true );
		$new_password->setErrorMessages(
			[
				Form_Field_RegistrationPassword::ERROR_CODE_EMPTY           => 'Please enter new password',
				Form_Field_RegistrationPassword::ERROR_CODE_CHECK_EMPTY     => 'Please confirm new password',
				Form_Field_RegistrationPassword::ERROR_CODE_CHECK_NOT_MATCH => 'Password confirmation do not match',
				Form_Field_RegistrationPassword::ERROR_CODE_WEAK_PASSWORD   => 'Password is not strong enough',
			]
		);


		$form = new Form(
			'change_password', [
				$current_password,
				$new_password,
			]
		);


		return $form;
	}

	/**
	 * @return Form
	 */
	public function getMustChangePasswordForm()
	{

		$password = new Form_Field_RegistrationPassword( 'password', 'New password: ' );
		$form = new Form(
			'change_password', [
				$password,
			]
		);

		/**
		 * @var Administrator $user
		 */
		$user = Auth::getCurrentUser();
		$password->setPasswordStrengthCheckCallback( function( $password ) use ( $user ) {
			return $user->verifyPasswordStrength( $password );
		} );

		$password->setErrorMessages(
			[
				Form_Field_RegistrationPassword::ERROR_CODE_EMPTY           => 'Please enter new password',
				Form_Field_RegistrationPassword::ERROR_CODE_CHECK_EMPTY     => 'Please confirm new password',
				Form_Field_RegistrationPassword::ERROR_CODE_CHECK_NOT_MATCH => 'Password confirmation do not match',
				Form_Field_RegistrationPassword::ERROR_CODE_WEAK_PASSWORD   => 'Password is not strong enough',
			]
		);
		$password->setIsRequired( true );
		$password->setPasswordConfirmationLabel( 'Confirm new password' );

		return $form;
	}

}