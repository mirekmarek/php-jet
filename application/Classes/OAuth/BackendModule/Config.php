<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplication;

use Jet\Config;
use Jet\Config_Definition;
use Jet\Form_Definition;
use Jet\Form_Definition_Interface;
use Jet\Form_Definition_Trait;
use Jet\Form_Field;

#[Config_Definition]
class OAuth_BackendModule_Config extends AppConfig_ModuleConfig_Specific implements Form_Definition_Interface
{
	use Form_Definition_Trait;
	
	#[Config_Definition(
		type: Config::TYPE_STRING,
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_INPUT,
		label: 'Client ID: ',
	)]
	protected string $client_id = '';
	
	
	#[Config_Definition(
		type: Config::TYPE_STRING,
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_INPUT,
		label: 'Client secret: ',
	)]
	protected string $client_secret = '';
	
	#[Config_Definition(
		type: Config::TYPE_STRING,
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_INPUT,
		label: 'Auth Endpoint URL: ',
	)]
	protected string $oauth_endpoint_URL = '';
	
	#[Config_Definition(
		type: Config::TYPE_STRING,
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_INPUT,
		label: 'Token Endpoint URL: ',
	)]
	protected string $token_endpoint_URL = '';
	
	#[Config_Definition(
		type: Config::TYPE_STRING,
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_INPUT,
		label: 'User detail Endpoint URL: ',
	)]
	protected string $user_detail_endpoint_URL = '';
	
	
	public function getClientId(): string
	{
		return $this->client_id;
	}
	
	public function setClientId( string $client_id ): void
	{
		$this->client_id = $client_id;
	}
	
	public function getClientSecret(): string
	{
		return $this->client_secret;
	}
	
	public function setClientSecret( string $client_secret ): void
	{
		$this->client_secret = $client_secret;
	}
	
	public function getOauthEndpointURL(): string
	{
		return $this->oauth_endpoint_URL;
	}
	
	public function setOauthEndpointURL( string $oauth_endpoint_URL ): void
	{
		$this->oauth_endpoint_URL = $oauth_endpoint_URL;
	}
	
	public function getTokenEndpointURL(): string
	{
		return $this->token_endpoint_URL;
	}
	
	public function setTokenEndpointURL( string $token_endpoint_URL ): void
	{
		$this->token_endpoint_URL = $token_endpoint_URL;
	}
	
	public function getUserDetailEndpointURL(): string
	{
		return $this->user_detail_endpoint_URL;
	}
	
	public function setUserDetailEndpointURL( string $user_detail_endpoint_URL ): void
	{
		$this->user_detail_endpoint_URL = $user_detail_endpoint_URL;
	}
	
}