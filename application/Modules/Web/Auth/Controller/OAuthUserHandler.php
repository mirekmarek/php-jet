<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Web\Auth\Controller;

use Jet\Auth;
use Jet\MVC;
use JetApplicationModule\Web\Auth\Entity\Visitor;
use JetApplication\OAuth_UserHandler;

class OAuthUserHandler extends OAuth_UserHandler
{
	
	public function handle(): void
	{
		if( !($visitor = Visitor::getByOAuth( $this->getOauthService(), $this->getOauthUserId() )) ) {
			
			if( !($visitor = Visitor::getByEmail( $this->getOauthUserEmail() )) ) {
				$visitor = new Visitor();
				$visitor->setEmail( $this->getOauthUserEmail() );
				$visitor->setLocale( MVC::getLocale() );
				//$customer->setRegistrationIp( Http_Request::clientIP() );
				//$customer->setRegistrationDateTime( Data_DateTime::now() );
				
			}
			
			$visitor->setOauthService( $this->getOauthService() );
			$visitor->setOauthKey( $this->getOauthUserId() );
			
			$visitor->save();
		}
		
		Auth::loginUser( $visitor );
	}
}