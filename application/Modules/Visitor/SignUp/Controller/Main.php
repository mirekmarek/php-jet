<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek &lt;mirek.marek@web-jet.cz&gt;
 * @license  http://www.php-jet.net/license/license.txt
 * @author  Miroslav Marek &lt;mirek.marek@web-jet.cz&gt;
 */
namespace JetApplicationModule\Visitor\SignUp;

use Jet\Auth;
use Jet\Http_Headers;
use Jet\Locale;
use Jet\MVC;
use Jet\MVC_Controller_Default;
use JetApplication\Auth_Visitor_User;

/**
 *
 */
class Controller_Main extends MVC_Controller_Default
{
	public const MAIN_ROLE_ID = 'main';

	/**
	 *
	 */
	public function default_Action() : void
	{
		if(Auth::getCurrentUser()) {
			$this->output('thanks-for-sign-up');
			
			return;
		}
		
		$user = new Auth_Visitor_User();
		$form = $user->getSignUpForm();
		
		$user->setLocale( Locale::getCurrentLocale() );
		
		$this->view->setVar( 'form', $form );
		
		
		if( $form->catch() ) {
			$password = $form->field('password')->getValue();
			
			$user->save();
			$user->setRoles([static::MAIN_ROLE_ID]);
			$user->sendWelcomeEmail( $password );
			
			Auth::login(
				$user->getUsername(),
				$password
			);
			
			Http_Headers::reload();
		}
		
		$this->output('default');
		
		
	}
	
	public function sign_up_link_Action() : void
	{
		$page = MVC::getPage('sign-up');
		if(!$page) {
			return;
		}
		
		$this->view->setVar('page', $page);
		
		$this->output('sign-up-link');
	}
}