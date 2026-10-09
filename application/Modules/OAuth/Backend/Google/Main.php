<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\OAuth\Backend\Google;

use JetApplication\Application_Service_General_OAuth_BackendModule;
use JetApplication\OAuth_UserHandler;

class Main extends Application_Service_General_OAuth_BackendModule
{
	public const SERVICE_ID = 'google';
	
	protected string $control_centre_title = 'OAuth - Google';
	protected string $control_centre_icon = 'gears';
	protected int $control_centre_priority = 50;
	
	public function getOAuthServiceID(): string
	{
		return static::SERVICE_ID;
	}
	
	protected function handleTokenResponse( OAuth_UserHandler $user_handler ) : bool
	{
		
		$id_token = $this->last_response_data['id_token']??'';
			
		if( substr_count( $id_token, '.' ) != 2 ) {
			return false;
		}
		
		$parts = explode( '.', $id_token );
		
		$payload = json_decode(base64_decode($parts[1]), true);
		
		
		$user_handler->setOauthUserId( $payload['sub'] );
		$user_handler->setOauthUserEmail( $payload['email'] );
		
		return true;
	}
	
}