<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\SysServices\Manager;


use Error;
use Jet\Application;
use Jet\Auth;
use Jet\Data_DateTime;
use Jet\Debug;
use Jet\ErrorPages;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Locale;
use Jet\Lock;
use Jet\Logger;
use Jet\MVC;
use Jet\MVC_Base_LocalizedData_Interface;
use JetApplication\Admin_ControlCentre;
use JetApplication\Admin_ControlCentre_Module_Interface;
use JetApplication\Admin_ControlCentre_Module_Trait;
use JetApplication\AppConfig_ModuleConfig_ModuleHasConfig_General_Interface;
use JetApplication\AppConfig_ModuleConfig_ModuleHasConfig_General_Trait;
use JetApplication\SysServices;
use JetApplication\SysServices_Definition;
use JetApplication\Application_Service_General_SysServices;


class Main extends Application_Service_General_SysServices implements
	Admin_ControlCentre_Module_Interface,
	AppConfig_ModuleConfig_ModuleHasConfig_General_Interface
{
	use Admin_ControlCentre_Module_Trait;
	use AppConfig_ModuleConfig_ModuleHasConfig_General_Trait;
	
	protected string $control_centre_group = Admin_ControlCentre::GROUP_SYSTEM;
	protected string $control_centre_title = 'System services';
	protected string $control_centre_icon = 'gears';
	protected int $control_centre_priority = 1;
	protected bool $control_cnter_specification_mode = false;
	
	protected function getConfig() : Config_General
	{
		$c = $this->getGeneralConfig();
		/** @var Config_General $c */
		return $c;
	}
	
	protected function commonError( string $message ) : void
	{
		Http_Headers::response(
			code: Http_Headers::CODE_503_SERVICE_UNAVAILABLE,
			headers: [
				'Content-Type' => 'text/plain; charset=UTF-8'
			]
		);
		
		echo $message;
		
		Application::end();
	}
	
	public function handleSysServices(): void
	{
		Logger::setLogger( new SysServicesLogger() );
		Auth::setController( new AuthController() );
		Debug::setOutputIsJSON( true );
		
		$URL_path = explode('/', MVC::getRouter()->getUrlPath());
		
		
		if(count($URL_path)<2) {
			ErrorPages::handleNotFound( true );
			return;
		}
		
		[$key, $service_code] = $URL_path;
		
		if($key!=$this->getConfig()->getKey()) {
			ErrorPages::handleNotFound( true );
			return;
		}
		
		$service = SysServices::getService( $service_code );
		if( !$service ) {
			ErrorPages::handleNotFound( true );
			return;
		}
		
		
		unset($URL_path[0]);
		unset($URL_path[1]);
		
		$_SERVER['REQUEST_URI'] = '/'.implode('/', $URL_path);
		
		
		if(!$service->isActive()) {
			$this->commonError('Service deactivated');
		}
		
		$base_l = null;
		
		if($service->getRequiresBaseDesignation()) {
			
			$bases = $service->getAllowedBases();
			
			if( ($base_key = Http_Request::GET()->getString('base', valid_values: array_keys($bases))) ) {
				$base_l = $bases[$base_key];
			}
			
			if(!$base_l) {
				$this->commonError( 'Export is not allowed' );
			}
			
			Locale::setCurrentLocale( $base_l->getLocale() );
		}
		
		
		
		Http_Headers::response(
			code: Http_Headers::CODE_200_OK,
			headers: [
				'Content-Type' => 'text/plain; charset=UTF-8'
			]
		);
		
		$lock_name = 'SysService:'.$service_code;
		if($service->getRequiresBaseDesignation()) {
			$lock_name .= ':'.$base_l->getBase()->getId().':'.$base_l->getLocale();
		}
		
		if(!Lock::lockIfPossible( $lock_name )) {
			echo "\n\nLocked - this servise is running right now\n\n";
			Application::end();
		}
		
		try {
			set_time_limit(-1);
			$service->perform( $base_l );
			echo "\n\nDONE\n\n";
		} catch( Error $e) {
			echo 'Error: '.$e->getMessage();
			
			Logger::danger(
				event: 'system_service_fault',
				event_message: 'Problem during system service '.$service->getName(),
				context_object_id: $service->getCode(),
				context_object_data: [
					'URL' => Http_Request::currentURL(),
					'error_message' => $e->getMessage()
				]
			);
		}
		
		Lock::unlock( $lock_name );
		
		Application::end();
	}
	
	public function getSysServiceURL( SysServices_Definition $service, ?MVC_Base_LocalizedData_Interface $base=null ) : string
	{
		$key = $this->getConfig()->getKey();
		$GET_params = [];
		if($base) {
			$GET_params['base'] = $base->getBase()->getId().':'.$base->getLocale();
		}
		
		$base =  MVC::getBase( $this->getConfig()->getBaseId() );
		
		return $base->getHomepage( $base->getDefaultLocale() )->getURL(
			path_fragments: [
				$key,
				$service->getCode()
			],
			GET_params: $GET_params
		);
	}
	
	
	
	/**
	 * @return PlannedOutage[]
	 */
	public function getPlannedOutages( SysServices_Definition $service ) : array
	{
		return PlannedOutage::getList( $service->getCode() );
	}
	
	
	
	public function cancelPlannedOutage( SysServices_Definition $service,int $plan_id ) : void
	{
		foreach($this->getPlannedOutages( $service ) as $plan) {
			if($plan->getId()==$plan_id) {
				$plan->delete();
				
				Logger::info(
					event: 'sys_service:scheduled_outage_cancelled',
					event_message: 'SysService'.$service->getCode().' scheduled outage cancelled',
					context_object_id: $service->getCode(),
					context_object_data: $plan
				);
				
				return;
			}
		}
	}
	
	
	
	public function serviceIsActive( SysServices_Definition $service ): bool
	{
		foreach($this->getPlannedOutages( $service ) as $outage) {
			if($outage->isValid()) {
				return false;
			}
		}
		
		return true;

	}
	
	public function shutdownService( SysServices_Definition $service ): void
	{
		foreach($this->getPlannedOutages( $service ) as $outage) {
			if(
				$outage->isValid() &&
				$outage->getFromDateTime() &&
				$outage->getTillDateTime()
			) {
				return;
			}
		}
		
		PlannedOutage::new( $service, null, null );
		
		Logger::info(
			event: 'sys_service:shutdown',
			event_message: 'SysService '.$service->getCode().' shutdown',
			context_object_id: $service->getCode()
		);
	}
	
	public function startService( SysServices_Definition $service ): void
	{
		$started = false;
		foreach($this->getPlannedOutages( $service ) as $outage) {
			if(
				$outage->isValid()
			) {
				$outage->delete();
				$started = true;
			}
		}
		
		if($started) {
			Logger::info(
				event: 'sys_service:start',
				event_message: 'SysService '.$service->getCode().' start',
				context_object_id: $service->getCode()
			);
		}
	}
	
	public function planServiceOutage( SysServices_Definition $service, ?Data_DateTime $from_date_time, ?Data_DateTime $till_date_time ): void
	{
		if(!$from_date_time && !$till_date_time) {
			return;
		}
		
		PlannedOutage::new( $service, $from_date_time, $till_date_time );
		
		Logger::info(
			event: 'sys_service:outage_scheduled',
			event_message: 'SysService'.$service->getCode().' outage scheduled',
			context_object_id: $service->getCode(),
			context_object_data: [
				'from_date_time' => $from_date_time,
				'till_date_time' => $till_date_time
			]
		);
	}
}