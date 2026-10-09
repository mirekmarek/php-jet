<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\OAuth\Backend\Facebook;

use JetApplication\Application_Service_General_OAuth_BackendModule;
use JetApplication\OAuth_UserHandler;


class Main extends Application_Service_General_OAuth_BackendModule
{
	public const SERVICE_ID = 'facebook';
	
	protected string $control_centre_title = 'OAuth - Facebook';
	protected string $control_centre_icon = 'gears';
	protected int $control_centre_priority = 50;
	
	
	public function getOAuthServiceID(): string
	{
		return static::SERVICE_ID;
	}
	
	protected function handleTokenResponse( OAuth_UserHandler $user_handler ) : bool
	{

		if(empty($this->last_response_data['access_token'])) {
			return false;
		}
		
		if($this->_request(
			static::METHOD_GET,
			$this->getUserDetailURL(),
			auth_token: $this->last_response_data['access_token']
		)) {
			
			$profile = $this->last_response_data;
			
			if(
				($profile['id']??'') &&
				($profile['email']??'')
			) {
				
				$user_handler->setOauthUserId( $profile['id'] );
				$user_handler->setOauthUserEmail( $profile['email'] );
				
				return true;
			}
		}
		
		return false;
	}
	
}