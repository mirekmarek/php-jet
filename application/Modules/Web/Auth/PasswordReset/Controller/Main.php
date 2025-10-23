<?php
/**
 *
 * @copyright 
 * @license  
 * @author  
 */
namespace JetApplicationModule\Web\Auth\PasswordReset;

use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Email;
use Jet\Form_Field_Hidden;
use Jet\Form_Field_Input;
use Jet\Http_Request;
use Jet\MVC_Controller_Default;
use Jet\Tr;
use Jet\UI_messages;
use JetApplicationModule\Web\Auth\Entity\Visitor;
use JetApplication\Application_Web_Pages;

/**
 *
 */
class Controller_Main extends MVC_Controller_Default
{
	
	protected ?Visitor $user;
	protected PasswordResetToken $token;
	
	public function resolve(): bool|string
	{
		$GET = Http_Request::GET();
		/**
		 * @var Main $module
		 */
		$module = $this->module;
		
		if(
			!($user_id=$GET->getInt('validate')) ||
			(!$this->user = Visitor::get( $user_id )) ||
			($GET->getString('key')!=$module->generateKey($this->user))
		) {
			return 'enter_email';
		}
		
		$token = PasswordResetToken::getValidToken( $this->user->getId() );
		if(!$token || !$token->isValid()) {
			return 'invalid_token';
		}
		
		$this->token = $token;
		
		return 'validate_code_and_reset';
	}
	
	public function enter_email_Action() : void
	{
		/**
		 * @var Main $module
		 */
		$module = $this->module;
		
		$email_field = new Form_Field_Email('email', 'Your e-mail address');
		$email_field->setPlaceholder( 'Your e-mail address' );
		$email_field->setIsRequired(true);
		$email_field->setErrorMessages([
			Form_Field_Email::ERROR_CODE_EMPTY => 'Please enter e-mail',
			Form_Field_Email::ERROR_CODE_INVALID_FORMAT => 'Please enter e-mail'
		]);
		
		$form = new Form('enter_email', [$email_field]);
		
		$this->view->setVar('form', $form);
		
		if($form->catch()) {
			$user = Visitor::getByEmail( $email_field->getValue() );
			
			
			if($user) {
				$module->generateToken( $user );
			} else {
				$form->setCommonMessage( UI_messages::createInfo(Tr::_('Sorry, unknown e-mail address')) );
			}
		}
		
		$this->output('enter-email');
	}
	
	public function invalid_token_Action() : void
	{
		/**
		 * @var Main $module
		 */
		$module = $this->module;
		
		$email_field = new Form_Field_Hidden('email', 'Your e-mail address');
		$email_field->setDefaultValue( $this->user->getEmail() );
		$form = new Form('enter_email', [$email_field]);
		
		$form->setAction( Application_Web_Pages::resetPassword()->getURL() );
		
		$this->view->setVar('form', $form);
		
		$this->output('invalid-token');
	}
	
	public function validate_code_and_reset_Action() : void
	{
		/**
		 * @var Main $module
		 */
		$module = $this->module;
		
		$code_field = new Form_Field_Input('code', 'Code');
		$code_field->setPlaceholder( 'Code' );
		$code_field->setIsRequired(true);
		$code_field->setErrorMessages([
			Form_Field_Email::ERROR_CODE_EMPTY => 'Please enter code',
			'invalid_code' => 'Invalid code'
		]);
		$code_field->setValidator(function() use ($code_field) : bool {
			if($this->token->getCode()!=$code_field->getValue()) {
				$code_field->setError('invalid_code');
				return false;
			}
			
			return true;
		});
		
		$new_password_field = new Form_Field_Input('new_password', 'New password');
		$new_password_field->setPlaceholder( 'New password' );
		$new_password_field->setIsRequired(true);
		$new_password_field->setErrorMessages([
			Form_Field::ERROR_CODE_EMPTY         => 'Please enter new password',
			Form_Field::ERROR_CODE_WEAK_PASSWORD => 'Password is not strong enough',
		]);
		
		$new_password_field->setValidator( function( Form_Field_Input $field ) : bool {
			if(!Visitor::verifyPasswordStrength($field->getValue())) {
				$field->setError( Form_Field::ERROR_CODE_WEAK_PASSWORD);
				return false;
			}
			
			return true;
		} );
		
		
		
		$form = new Form('validate', [$code_field, $new_password_field]);
		if(
			$form->catchInput()
		) {
			if($form->validate()) {
				$module->passwordReset( $this->user, $this->token, $new_password_field->getValue() );
			} else {
				sleep(3);
			}
		}
		
		$this->view->setVar('form', $form);
		
		$this->output('validate-code-and-reset');
	}
	
	
}