<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\OAuth\Manager;

use Jet\Factory_MVC;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\MVC;
use Jet\MVC_Base_LocalizedData_Interface;
use Jet\Session;
use JetApplication\Application_Service_General_OAuth_BackendModule;
use JetApplication\Application_Service_General_OAuth_Manager;
use JetApplication\Application_Service_General;
use JetApplication\OAuth_BackendModule;
use JetApplication\OAuth_UserHandler;

/**
 *
 */
class Main extends Application_Service_General_OAuth_Manager
{
	protected const URL_PATH_PART_PREFIX = 'oauth:';
	
	/**
	 * @var array<string,OAuth_BackendModule>
	 */
	protected array $oauth_modules;
	protected OAuth_UserHandler $auth_user_handler;
	
	public function init( string $cfg_specification_id, OAuth_UserHandler $auth_user_handler ) : void
	{
		$this->auth_user_handler = $auth_user_handler;
		
		$modules = Application_Service_General::list()->getList( Application_Service_General_OAuth_BackendModule::class );
		$this->oauth_modules = [];
		foreach($modules as $module) {
			/** @var OAuth_BackendModule $module */
			$module->init( $cfg_specification_id, $this );
			if($module->isReady()) {
				$this->oauth_modules[$module->getOAuthServiceID()] = $module;
			}
		}
	}
	
	
	/**
	 * @return OAuth_BackendModule[]
	 */
	public function getOAuthModules( bool $only_ready=false ): array
	{
		if($only_ready) {
			$modules = [];
			foreach($this->oauth_modules as $k=>$module) {
				if($module->isReady()) {
					$modules[$k] = $module;
				}
			}
			
			return $modules;
		}
		return $this->oauth_modules;
	}
	
	
	public function handle(): void
	{
		$modules = $this->getOAuthModules();
		
		$main_router = MVC::getRouter();
		$service_path_part = rtrim($main_router->getUrlPath(), '/');
		if(
			!$service_path_part ||
			!str_starts_with( $service_path_part, static::URL_PATH_PART_PREFIX )
		) {
			return;
		}
		
		$service_id = substr( $service_path_part, strlen( static::URL_PATH_PART_PREFIX ) );
		
		if(!$service_id || !isset($modules[$service_id])) {
			$main_router->setIs404();
			return;
		}
		
		$main_router->setUsedUrlPath($service_path_part);
		
		$module = $modules[$service_id];
		
		$this->handleRequest( $module );
		$this->handleOAuthServiceReturn( $module );
	}
	
	protected function getSession() : Session
	{
		return new Session( 'OAuthSession' );
	}
	
	protected function handleRequest( OAuth_BackendModule $current_module ): void
	{
		$POST = Http_Request::POST();
		
		
		if(
			$POST->exists( 'login' ) &&
			($redirect_page_id = $POST->getString( 'redirect_page_id' )) &&
			($page = MVC::getPage( $redirect_page_id ))
		) {
			$session = $this->getSession();
			
			$session->setValue( 'redirect_page_id', $redirect_page_id );
			$session->setValue( 'path_fragments', $POST->getRaw( 'path_fragments', '' ) );
			
			Http_Headers::movedTemporary( $current_module->getOAuthServiceAuthorizationLink() );
		}
		
	}
	
	protected function handleOAuthServiceReturn( OAuth_BackendModule $current_module ): void
	{
		$session = $this->getSession();
		
		if(
			($redirect_page_id = $session->getValue( 'redirect_page_id', '' ))
		) {
			
			$path_fragments = $session->getValue( 'path_fragments', '' );
			if( !$path_fragments ) {
				$path_fragments = [];
			} else {
				$path_fragments = explode( '/', $path_fragments );
			}
			
			$user_handler = $this->auth_user_handler;
			$user_handler->setOauthService( $current_module->getOAuthServiceID() );
			
			if( $current_module->handleOAuthServiceReturn(
				$user_handler
			)) {
				$session->setValue('redirect_page_id', '');
				$session->setValue('path_fragments', '');
				
				$user_handler->handle();
				
				Http_Headers::movedTemporary(
					MVC::getPage( $redirect_page_id )->getURL( path_fragments: $path_fragments )
				);
			}
			
			
			
		}
	}
	
	public function generateHandlerURL( OAuth_BackendModule $module, ?MVC_Base_LocalizedData_Interface $base=null ) : string
	{
		if($base) {
			return $base->getBase()->getHomepage( $base->getLocale() )->getURL( [static::URL_PATH_PART_PREFIX.$module->getOAuthServiceID()] );
		} else {
			return MVC::getHomePage()->getURL( [static::URL_PATH_PART_PREFIX.$module->getOAuthServiceID()] );
		}
	}
	
	public function renderLoginButton( OAuth_BackendModule $module ) : string
	{
		$view = Factory_MVC::getViewInstance( $this->getViewsDir() );
		
		$view->setVar('module', $module);
		
		return $view->render('button');
	}

}