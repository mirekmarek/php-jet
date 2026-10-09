<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Application_Module;
use Jet\Application_Service_MetaInfo;
use Jet\Factory_MVC;
use Jet\Http_Request;

#[Application_Service_MetaInfo(
	group: Application_Service_General::GROUP,
	is_mandatory: false,
	multiple_mode: true,
	name: 'OAuth service backend modules',
	description: '',
)]
abstract class OAuth_BackendModule extends Application_Module implements
	AppConfig_ModuleConfig_ModuleHasConfig_Specific_Interface,
	Admin_ControlCentre_Module_Interface_BaseIsSpecification
{
	use AppConfig_ModuleConfig_ModuleHasConfig_Specific_Trait;
	use Admin_ControlCentre_Module_Trait_BaseIsSpecification;

	protected string $control_centre_group = Admin_ControlCentre::GROUP_SYSTEM;
	protected bool $control_cnter_specification_mode = true;
	
	public const METHOD_POST = 'POST';
	public const METHOD_GET = 'GET';
	
	public const HTTP_STATUS_OK = 200;
	public const HTTP_STATUS_CREATED = 201;
	public const HTTP_STATUS_ACCEPTED = 202;
	public const HTTP_STATUS_NO_CONTENT = 204;
	
	protected string $cfg_specification_id;
	protected OAuth_Manager $manager;
	
	protected int $last_response_status = 0;
	
	protected string $last_error_message = '';
	
	protected mixed $last_response_data=null;
	
	public function getControlCentreGroup() : string
	{
		return Admin_ControlCentre::GROUP_SYSTEM;
	}
	
	public function getManager(): OAuth_Manager
	{
		return $this->manager;
	}
	
	public function init( string $cfg_specification_id, OAuth_Manager $manager ): void
	{
		$this->cfg_specification_id = $cfg_specification_id;
		$this->manager = $manager;
	}
	
	
	public function isReady(): bool
	{
		return (
			$this->getClientId() &&
			$this->getClientSecret() &&
			$this->getOAuthURL() &&
			$this->getTokenURL() &&
			$this->getUserDetailURL()
		);
	}
	
	public function getConfig( ?string $cfg_specification_id = null ) : OAuth_BackendModule_Config
	{
		$cfg = $this->getSpecificConfig( $cfg_specification_id ? : $this->cfg_specification_id );
		/**
		 * @var OAuth_BackendModule_Config $cfg
		 */
		return $cfg;
	}
	
	
	abstract public function getOAuthServiceID(): string;
	
	/**
	 * @param array<string,string> $data
	 * @return string
	 */
	public function getOAuthServiceURL( array $data ): string
	{
		return $this->getOAuthURL().'?'.http_build_query($data);
	}
	
	protected function getOAuthURL() : string
	{
		return $this->getConfig()->getOauthEndpointURL();
	}
	
	protected function getTokenURL() : string
	{
		return $this->getConfig()->getTokenEndpointURL();
	}
	
	protected function getUserDetailURL() : string
	{
		return $this->getConfig()->getUserDetailEndpointURL();
	}
	
	
	protected function getClientId(): string
	{
		return $this->getConfig()->getClientId();
	}
	
	protected function getClientSecret(): string
	{
		return $this->getConfig()->getClientSecret();
	}
	
	public function handleOAuthServiceReturn( OAuth_UserHandler $user_handler ) : bool
	{
		
		$GET = Http_Request::GET();
		if(
			!$GET->exists('code')
		) {
			return false;
		}
		
		$post_data = [
			'code'          => $GET->getString('code'),
			'client_id'     => $this->getClientId(),
			'client_secret' => $this->getClientSecret(),
			'redirect_uri'  => $this->getHandlerUrl(),
			'grant_type'    => 'authorization_code'
		];
		
		
		if($this->_request(
			static::METHOD_POST,
			$this->getTokenURL(),
			$post_data
		)) {
			return $this->handleTokenResponse( $user_handler );
		}
		
		return false;
	}
	
	abstract protected function handleTokenResponse( OAuth_UserHandler $user_handler ) : bool;
	
	
	public function getHandlerUrl(): string
	{
		return $this->manager->generateHandlerURL( $this );
	}
	
	
	public function getOAuthServiceAuthorizationLink() : string
	{
		$data = [
			'client_id'     => $this->getClientId(),
			'redirect_uri'  => $this->getHandlerUrl(),
			'scope'         => 'email',
			'response_type' => 'code'
		];
		
		return  $this->getOAuthServiceURL( $data );
	}
	
	
	public function _renderLoginButtonUI() : string
	{
		$view = Factory_MVC::getViewInstance( $this->getViewsDir() );
		
		$view->setVar('module', $this);
		
		return $view->render('button');
	}
	
	public function renderLoginButton() : string
	{
		return $this->manager->renderLoginButton( $this );
	}
	
	
	/**
	 * @param string $method
	 * @param string $URL
	 * @param array<string,string> $post_data
	 * @param string $auth_token
	 * @return bool
	 */
	protected function _request( string $method, string $URL, array $post_data = [], string $auth_token='') : bool
	{
		
		$this->last_error_message = '';
		$this->last_response_status = 0;
		
		$curl_handle = curl_init();
		
		$headers = [
			'Accept: application/json',
			'User-Agent: CURL',
		];
		if($auth_token) {
			$headers[] = 'Authorization: Bearer ' . $auth_token;
		}
		
		/** @phpstan-ignore argument.type */
		curl_setopt($curl_handle, CURLOPT_URL, $URL );
		curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
		
		switch ($method) {
			case self::METHOD_POST:
				curl_setopt($curl_handle, CURLOPT_POST, true);
				curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $post_data);
				break;
			case self::METHOD_GET:
				curl_setopt($curl_handle, CURLOPT_HTTPGET, true);
				break;
		}
		
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
		
		$response_data = curl_exec($curl_handle);
		$response_status = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
		
		$this->last_response_status = $response_status;
		
		$message = '';
		
		
		if($response_data===false) {
			$this->last_error_message = 'CURL_ERR:' . curl_errno($curl_handle) . ' - ' . curl_error($curl_handle);
			$this->last_response_data = null;
			
			return false;
		} else {
			/** @var string $response_data */
			$this->last_response_data = json_decode($response_data, true);
			
			
			if(!is_array($this->last_response_data)) {
				$this->last_error_message = "Unexpected response: HTTP status: $response_status, Response: $response_data";
				
				return false;
			} else {
				if(isset($this->last_response_data['result']['message'])) {
					$message = $this->last_response_data['result']['message'];
				}
				
				switch ($response_status) {
					case self::HTTP_STATUS_OK:
					case self::HTTP_STATUS_CREATED:
					case self::HTTP_STATUS_ACCEPTED:
						return true;
					default:
						$this->last_error_message = "http error: $response_status, message: " . $message;
						return false;
				}
				
			}
			
		}
	}
	
}