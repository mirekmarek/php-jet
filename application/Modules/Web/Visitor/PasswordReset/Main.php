<?php
/**
 *
 * @copyright 
 * @license  
 * @author  
 */
namespace JetApplicationModule\Web\Visitor\PasswordReset;

use Jet\Application_Module;
use Jet\Auth;
use Jet\Http_Headers;
use Jet\Logger;
use Jet\Mailing_Email_Template;
use JetApplication\Application_Web;
use JetApplication\Application_Web_Pages;
use JetApplication\Auth_Visitor_User as User;

/**
 *
 */
class Main extends Application_Module
{
	public function generateKey( User $user ) : string
	{
		return sha1($user->getId().':'.$user->getEmail().':@@#%$sw4$');
	}
	
	public function generateToken( User $user ) : void
	{
		$token = PasswordResetToken::generate( $user );
		
		Logger::info(
			event: 'user_password_reset_started',
			event_message: 'User ' . $user->getUsername() . ' (id:' . $user->getId() . ') password reset started',
			context_object_id: $user->getId(),
			context_object_name: $user->getUsername()
		);
		
		$email_template = new Mailing_Email_Template(
			template_id: 'visitor/password_reset/request',
			locale: $user->getLocale()
		);
		$email_template->setVar( 'code', $token->getCode() );
		$email_template->setVar( 'user', $user );
		
		$email = $email_template->getEmail();
		$email->setTo( $user->getEmail() );
		$email->send();
		
		Http_Headers::reload(set_GET_params: ['validate'=>$user->getId(), 'key'=>$this->generateKey($user)]);
		
	}
	
	public function passwordReset(User $user, PasswordResetToken $token, string $new_password) : void
	{
		$token->used();
		$user->setPassword( $new_password );
		
		Logger::info(
			event: 'user_password_reset_done',
			event_message: 'User ' . $user->getUsername() . ' (id:' . $user->getId() . ') password reset done',
			context_object_id: $user->getId(),
			context_object_name: $user->getUsername()
		);
		
		$email_template = new Mailing_Email_Template(
			template_id: 'visitor/password_reset/done',
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