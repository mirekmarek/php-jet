<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Input;
use Jet\Form_Field_Password;
use Jet\Http_Headers;
use Jet\IO_File;
use Jet\Session;
use Jet\Tr;
use Jet\UI_messages;

/**
 *
 */
class AccessControl
{

	/**
	 * @var ?Session
	 */
	protected static ?Session $session = null;

	/**
	 *
	 */
	public static function handle(): void
	{
		Application::setCurrentPart( 'login' );

		$session = static::getSession();
		$key = static::readKey();
		if( !$key ) {
			static::handle_keyFileProblem();
		}

		if( $session->getValue( 'username' ) != $key['username'] ) {
			static::handle_login();
		}

		Application::setCurrentPart( '' );
	}

	/**
	 *
	 */
	protected static function handle_keyFileProblem(): void
	{
		Application::getLayout( 'login' );
		Application::output( Application::getView()->render( 'error' ) );
		Application::renderLayout();

		Application::end();
	}

	/**
	 *
	 */
	protected static function handle_login(): void
	{
		$form = static::getLoginForm();


		if( $form->catchInput() ) {
			if( $form->validate() ) {
				$data = $form->getValues();
				if( static::checkKey( $data['username'], $data['password'] ) ) {
					Http_Headers::reload();
				} else {
					$form->setCommonMessage( Tr::_( 'Invalid username or password!' ) );
				}
			} else {
				$form->setCommonMessage( Tr::_( 'Please enter username and password' ) );
			}
		}

		Application::getLayout( 'login' );

		$view = Application::getView();
		$view->setVar( 'login_form', $form );

		Application::output( $view->render( 'login' ) );
		Application::renderLayout();

		Application::end();

	}

	/**
	 *
	 */
	public static function logout(): void
	{
		static::getSession()->unsetValue( 'username' );
	}


	/**
	 * @return Form
	 */
	public static function getLoginForm(): Form
	{
		$username_field = new Form_Field_Input( 'username', 'Username: ' );
		$username_field->setErrorMessages(
			[
				Form_Field::ERROR_CODE_EMPTY => 'Please enter username',
			]
		);
		$password_field = new Form_Field_Password( 'password', 'Password:' );
		$password_field->setErrorMessages(
			[
				Form_Field::ERROR_CODE_EMPTY => 'Please enter password',
			]
		);

		$form = new Form(
			'login', [
				$username_field,
				$password_field,
			]
		);
		$form->enableCSRFProtection();

		$form->getField( 'username' )->setIsRequired( true );
		/**
		 * @var Form_Field_Password $password
		 */
		$password = $form->getField( 'password' );
		$password->setIsRequired( true );

		return $form;
	}


	/**
	 * @return Session
	 */
	protected static function getSession(): Session
	{
		if( !static::$session ) {
			static::$session = new Session( '_jet_studio_sess' );
		}

		return static::$session;
	}

	/**
	 * @return bool|array
	 */
	protected static function readKey(): bool|array
	{
		$path = ProjectConf_Path::getData() . '_jet_studio_access.php';

		if( !IO_File::exists( $path ) ) {
			UI_messages::danger( Tr::_( 'The access configuration file does not exist' ) );
			return false;
		}

		if( !IO_File::isReadable( $path ) ) {
			UI_messages::danger( Tr::_( 'The access configuration file is not readable' ) );
			return false;
		}

		$key = require $path;
		if(
			!is_array( $key ) ||
			!isset( $key['username'] ) ||
			!isset( $key['password'] )
		) {
			UI_messages::danger( Tr::_( 'The access configuration is corrupted' ) );
			return false;
		}

		return $key;
	}

	/**
	 * @param string $username
	 * @param string $password
	 * @return bool
	 */
	protected static function checkKey( string $username, string $password ): bool
	{

		$key = static::readKey();

		if( $key['username'] != $username ) {
			return false;
		}

		if( !password_verify( $password, $key['password'] ) ) {
			return false;
		}

		static::getSession()->setValue( 'username', $username );

		return true;
	}
}