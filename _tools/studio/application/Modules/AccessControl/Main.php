<?php

/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\AccessControl;

use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Input;
use Jet\Form_Field_Password;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\IO_File;
use Jet\Session;
use Jet\Tr;
use Jet\UI_messages;
use JetStudio\JetStudio;
use JetStudio\JetStudio_Conf_Path;
use JetStudio\JetStudio_Module;
use JetStudio\JetStudio_Module_Service_AccessControl;

class Main extends JetStudio_Module implements JetStudio_Module_Service_AccessControl
{
	
	protected ?Session $session = null;
	
	public function handleAccessControl(): void
	{
		if( in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']) ) {
			return;
		}
		
		$session = $this->getSession();
		$key = $this->readKey();
		if( !$key ) {
			$this->handle_keyFileProblem();
			JetStudio::end();
		}
		
		if( $session->getValue( 'username' ) != $key['username'] ) {
			$this->handle_login();
			JetStudio::end();
		}
		
		if(Http_Request::GET()->exists('logout')) {
			$this->handle_logout();
			Http_Headers::reload(unset_GET_params: ['logout']);
		}
	}
	
	protected function handle_keyFileProblem(): void
	{
		JetStudio::initLayout( 'login' );
		$this->output('error');
	}
	
	
	protected function handle_login(): void
	{
		$form = $this->getLoginForm();
		
		
		if( $form->catchInput() ) {
			if( $form->validate() ) {
				$data = $form->getValues();
				if( $this->checkKey( $data['username'], $data['password'] ) ) {
					Http_Headers::reload();
				} else {
					$form->setCommonMessage( Tr::_( 'Invalid username or password!' ) );
				}
			} else {
				$form->setCommonMessage( Tr::_( 'Please enter username and password' ) );
			}
		}
		
		JetStudio::initLayout( 'login' );
		
		$view = $this->getView();
		$view->setVar( 'login_form', $form );
		
		$this->output('login');
		
	}

	public function handle_logout(): void
	{
		$this->getSession()->unsetValue( 'username' );
	}
	
	
	public function getLoginForm(): Form
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
	

	protected function getSession(): Session
	{
		if( !$this->session ) {
			$this->session = new Session( '_jet_studio_sess' );
		}
		
		return $this->session;
	}
	
	protected function readKey(): bool|array
	{
		$path = JetStudio_Conf_Path::getData() . '_jet_studio_access.php';
		
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
	
	protected function checkKey( string $username, string $password ): bool
	{
		
		$key = $this->readKey();
		
		if( $key['username'] != $username ) {
			return false;
		}
		
		if( !password_verify( $password, $key['password'] ) ) {
			return false;
		}
		
		$this->getSession()->setValue( 'username', $username );
		
		return true;
	}
	
}
