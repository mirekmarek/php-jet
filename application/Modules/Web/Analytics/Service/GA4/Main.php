<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Web\Analytics\Service\GA4;


use Jet\MVC_Page_Interface;
use JetApplication\Admin_ControlCentre;
use JetApplication\Admin_ControlCentre_Module_Interface_WebIsSpecification;
use JetApplication\Admin_ControlCentre_Module_Trait_WebIsSpecification;
use JetApplication\Application_Service_Web_Analytics_Service;
use JetApplication\AppConfig_ModuleConfig_ModuleHasConfig_Specific_Interface;
use JetApplication\AppConfig_ModuleConfig_ModuleHasConfig_Specific_Trait;


class Main extends Application_Service_Web_Analytics_Service implements
	AppConfig_ModuleConfig_ModuleHasConfig_Specific_Interface,
	Admin_ControlCentre_Module_Interface_WebIsSpecification
{
	use AppConfig_ModuleConfig_ModuleHasConfig_Specific_Trait;
	use Admin_ControlCentre_Module_Trait_WebIsSpecification;
	
	
	protected string $control_centre_group = Admin_ControlCentre::GROUP_ANALYTICS;
	protected string $control_centre_title = 'Google Analytics v4';
	protected string $control_centre_icon = 'chart-line';
	protected int $control_centre_priority = 99;
	protected bool $control_cnter_specification_mode = true;
	

	protected bool $native_mode = true;
	protected string $id = '';
	
	public function allowed(): bool
	{
		return true;
	}
	
	public function init( MVC_Page_Interface $page ) : void
	{
		parent::init( $page );
		
		/**
		 * @var Config_Specific $config
		 */
		$config = $this->getSpecificConfig( $this->getSpecificationIdByPage( $page ) );
		
		$this->id = $config->getGoogleId();
		$this->native_mode = $config->getNativeMode();
		
		if( $this->id ) {
			$this->enabled = true;
		}
		
	}
	
	
	public function getNativeMode(): bool
	{
		return $this->native_mode;
	}
	
	public function setNativeMode( bool $native_mode ): void
	{
		$this->native_mode = $native_mode;
	}
	
	
	/**
	 * @param string $event
	 * @param array<string,mixed> $event_data
	 * @return string
	 */
	protected function generateEvent_dataLayer( string $event, array $event_data ) : string {
		$this->view->setVar('event', $event);
		$this->view->setVar('event_data', $event_data);
		
		return $this->view->render('dataLayer/event');
	}
	
	
	/**
	 * @param string $event
	 * @param array<string,mixed> $event_data
	 * @return string
	 */
	protected function generateEvent_native( string $event, array $event_data ) : string
	{
		$this->view->setVar('event', $event);
		$this->view->setVar('event_data', $event_data);
		
		return $this->view->render('native/event');
	}
	
	/**
	 * @param string $event
	 * @param array<string,mixed> $event_data
	 * @return string
	 */
	public function generateEvent( string $event, array $event_data ) : string
	{
		if($this->native_mode) {
			return $this->generateEvent_native( $event, $event_data );
		} else {
			return $this->generateEvent_dataLayer( $event, $event_data );
		}
	}
	
	public function header(): string
	{
		$this->view->setVar('id', $this->id);
		
		return $this->view->render('header');
	}
	
	
	public function documentStart(): string
	{
		return '';
	}
	
	public function documentEnd(): string
	{
		return '';
	}
	
	public function viewHomePage() : string
	{
		return '';
	}
	
	
	public function customEvent( string $event, array $event_data = [] ): string
	{
		return '';
	}
	
}