<?php
/**
 *
 * @copyright 
 * @license  
 * @author  
 */
namespace JetApplicationModule\Web\Auth\PasswordReset;

use Jet\Application_Module;
use Jet\Application_Module_HasEmailTemplates_Interface;
use Jet\Application_Module_HasEmailTemplates_Trait;
use Jet\Auth;
use Jet\Http_Headers;
use Jet\Logger;
use JetApplication\Application_Web;
use JetApplication\Application_Web_Pages;
use JetApplicationModule\Web\Auth\Entity\Visitor;

/**
 *
 */
class Main extends Application_Module implements Application_Module_HasEmailTemplates_Interface
{
	use Application_Module_HasEmailTemplates_Trait;
	
	public function generateKey( Visitor $user ) : string
	{
		return sha1($user->getId().':'.$user->getEmail().':@@#%$sw4$');
	}
	
	public function generateToken( Visitor $user ) : void
	{
		$token = PasswordResetToken::generate( $user );
		
		Logger::info(
			event: 'user_password_reset_started',
			event_message: 'User ' . $user->getUsername() . ' (id:' . $user->getId() . ') password reset started',
			context_object_id: $user->getId(),
			context_object_name: $user->getUsername()
		);
		
		$email_template = static::createEmailTemplate(
			template_id: 'request',
			locale: $user->getLocale()
		);
		$email_template->setVar( 'code', $token->getCode() );
		$email_template->setVar( 'user', $user );
		
		$email = $email_template->getEmail();
		$email->setTo( $user->getEmail() );
		$email->send();
		
		Http_Headers::reload(set_GET_params: ['validate'=>$user->getId(), 'key'=>$this->generateKey($user)]);
		
	}
	
	public function passwordReset( Visitor $user, PasswordResetToken $token, string $new_password) : void
	{
		$token->used();
		$user->setPassword( $new_password );
		
		Logger::info(
			event: 'user_password_reset_done',
			event_message: 'User ' . $user->getUsername() . ' (id:' . $user->getId() . ') password reset done',
			context_object_id: $user->getId(),
			context_object_name: $user->getUsername()
		);
		
		$email_template = static::createEmailTemplate(
			template_id: 'done',
			locale: $user->getLocale()
		);
		$email_template->setVar( 'user', $user );
		
		$email = $email_template->getEmail();
		$email->setTo( $user->getEmail() );
		$email->send();
		
		
		Auth::loginUser( $user );
		
		$page = Application_Web_Pages::secretArea();
		if(!$page) {
			$page = Application_Web::getBase()->getHomepage();
		}
		
		Http_Headers::movedTemporary( $page->getURL() );
		
	}
}