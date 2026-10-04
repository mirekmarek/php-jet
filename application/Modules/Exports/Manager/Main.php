<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Exports\Manager;

use Error;
use Jet\Application;
use Jet\Auth;
use Jet\Data_DateTime;
use Jet\Debug;
use Jet\ErrorPages;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Locale;
use Jet\Logger;
use Jet\MVC;
use Jet\MVC_Base_LocalizedData_Interface;
use Jet\SysConf_Jet_Debug;
use JetApplication\Admin_ControlCentre;
use JetApplication\Admin_ControlCentre_Module_Interface;
use JetApplication\Admin_ControlCentre_Module_Trait;
use JetApplication\Exports;
use JetApplication\Exports_Definition;
use JetApplication\Application_Service_General_ExportsManager;
use JetApplication\AppConfig_ModuleConfig_ModuleHasConfig_General_Interface;
use JetApplication\AppConfig_ModuleConfig_ModuleHasConfig_General_Trait;


class Main extends Application_Service_General_ExportsManager implements
	Admin_ControlCentre_Module_Interface,
	AppConfig_ModuleConfig_ModuleHasConfig_General_Interface
{
	use Admin_ControlCentre_Module_Trait;
	use AppConfig_ModuleConfig_ModuleHasConfig_General_Trait;
	
	protected string $control_centre_group = Admin_ControlCentre::GROUP_MAIN;
	protected string $control_centre_title = 'Exports';
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
	
	public function handleExports(): void
	{
		Logger::setLogger( new ExportLogger() );
		Auth::setController( new AuthController() );
		Debug::setOutputIsJSON( true );
		
		$URL_path = explode('/', MVC::getRouter()->getUrlPath());
		
		if(count($URL_path)!=2) {
			ErrorPages::handleNotFound( true );
			return;
		}
		
		[$key, $export_code] = $URL_path;
		
		$valid_key = $this->generateKey( $export_code );
		
		if($key!=$valid_key) {
			ErrorPages::handleNotFound( true );
			return;
		}
		
		$export = Exports::getExport( $export_code );
		
		if( !$export ) {
			ErrorPages::handleNotFound( true );
			return;
		}
		
		
		if(!$export->isActive()) {
			$this->commonError( 'Export deactivated' );
		}
		
		
		
		$base_l = null;
		
		if($export->getRequiresBaseDesignation()) {
			
			$bases = $export->getAllowedBases();
			
			if( ($base_key = Http_Request::GET()->getString('base', valid_values: array_keys($bases))) ) {
				$base_l = $bases[$base_key];
			}
			
			if(!$base_l) {
				$this->commonError( 'Export is not allowed' );
			}
			
			Locale::setCurrentLocale( $base_l->getLocale() );
		}
		
		
		try {
			$export->perform( $base_l );
		} catch( Error $e) {
			
			if(SysConf_Jet_Debug::getDevelMode()) {
				throw $e;
			} else {
				Logger::danger(
					event: 'export_fault',
					event_message: 'Problem during export '.$export->getName(),
					context_object_id: $export->getCode(),
					context_object_data: [
						'URL' => Http_Request::currentURL(),
						'error_message' => $e->getMessage()
					]
				);
			}
		}
		
		
		Application::end();
	}
	
	public function getExportURL( Exports_Definition $export, ?MVC_Base_LocalizedData_Interface $base=null ) : string
	{
		$GET_params = [];
		if($base) {
			$GET_params['base'] = $base->getBase()->getId().':'.$base->getLocale();
		}
		
		$exports_base =  MVC::getBase( $this->getConfig()->getBaseId() );
		
		return $exports_base->getHomepage( $exports_base->getDefaultLocale() )->getURL(
			path_fragments: [
				$this->generateKey( $export->getCode() ),
				$export->getCode()
			],
			GET_params: $GET_params
		);
	}
	
	protected function generateKey( string $eport_code ) : string
	{
		return sha1( $this->getConfig()->getKey(). $eport_code );
	}
	
	public function exportIsActive( Exports_Definition $export ) : bool
	{
		foreach($this->getPlannedOutages( $export ) as $outage) {
			if($outage->isValid()) {
				return false;
			}
		}
		
		return true;
		
	}
	
	/**
	 * @return PlannedOutage[]
	 */
	public function getPlannedOutages( Exports_Definition $export ) : array
	{
		return PlannedOutage::getList( $export->getCode() );
	}
	
	public function shutdownExport( Exports_Definition $export ) : void
	{
		
		foreach($this->getPlannedOutages( $export ) as $outage) {
			if(
				$outage->isValid() &&
				$outage->getFromDateTime() &&
				$outage->getTillDateTime()
			) {
				return;
			}
		}
		
		PlannedOutage::new( $export, null, null );
		
		Logger::info(
			event: 'export:shutdown',
			event_message: 'Export '.$export->getCode().' shutdown',
			context_object_id: $export->getCode()
		);

	}
	
	public function startExport( Exports_Definition $export ) : void
	{
		$started = false;
		foreach($this->getPlannedOutages( $export ) as $outage) {
			if(
				$outage->isValid()
			) {
				$outage->delete();
				$started = true;
			}
		}
		
		if($started) {
			Logger::info(
				event: 'export:start',
				event_message: 'Export '.$export->getCode().' start',
				context_object_id: $export->getCode()
			);
		}

	}
	
	public function planExportOutage( Exports_Definition $export, ?Data_DateTime $from_date_time, ?Data_DateTime $till_date_time ) : void
	{
		if(!$from_date_time && !$till_date_time) {
			return;
		}
		
		PlannedOutage::new( $export, $from_date_time, $till_date_time );
		
		Logger::info(
			event: 'export:outage_scheduled',
			event_message: 'Export'.$export->getCode().' outage scheduled',
			context_object_id: $export->getCode(),
			context_object_data: [
				'from_date_time' => $from_date_time,
				'till_date_time' => $till_date_time
			]
		);
		
		$export->planOutage( $from_date_time, $till_date_time );
	}
	
	public function cancelPlannedOutage( Exports_Definition $export, int $plan_id ) : void
	{
		foreach($this->getPlannedOutages( $export ) as $plan) {
			if($plan->getId()==$plan_id) {
				$plan->delete();
				
				Logger::info(
					event: 'export:scheduled_outage_cancelled',
					event_message: 'Export'.$export->getCode().' scheduled outage cancelled',
					context_object_id: $export->getCode(),
					context_object_data: $plan
				);
				
				return;
			}
		}

	}
	
	
}