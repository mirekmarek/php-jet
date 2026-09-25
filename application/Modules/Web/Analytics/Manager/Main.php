<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Web\Analytics\Manager;

use JetApplication\Application_Service_Web;
use JetApplication\Application_Service_Web_Analytics_Manager;
use JetApplication\Application_Service_Web_Analytics_Service;

use Jet\MVC;

class Main extends Application_Service_Web_Analytics_Manager
{
	
	/**
	 * @var array<string,Application_Service_Web_Analytics_Service>
	 */
	protected ?array $services = null;
	
	
	/**
	 * @return array<string,Application_Service_Web_Analytics_Service>
	 */
	public function getServices() : array
	{
		if($this->services===null) {

			$this->services = Application_Service_Web::getList()->getList( Application_Service_Web_Analytics_Service::class );
			
			foreach($this->services as $service) {
				$service->init( MVC::getPage() );
			}
		}
		
		return $this->services;
	}
	
	public function header() : string
	{
		$res = '';
		foreach($this->getServices() as $service) {
			if($service->canPerform()) {
				$res .= $service->header();
			}
		}
		return $res;
	}
	
	public function documentStart() : string
	{
		$res = '';
		foreach($this->getServices() as $service) {
			if($service->canPerform()) {
				$res .= $service->documentStart();
			}
		}
		return $res;
	}
	
	public function documentEnd() : string
	{
		$res = '';
		foreach($this->getServices() as $service) {
			if($service->canPerform()) {
				$res .= $service->documentEnd();
			}
		}
		return $res;
	}
	
	public function customEvent( string $event, array $event_data=[] ) : string
	{
		$res = '';
		foreach($this->getServices() as $service) {
			if($service->canPerform()) {
				$res .= $service->customEvent( $event, $event_data );
			}
		}
		return $res;
	}
	
	
	public function viewHomePage(): string
	{
		$res = '';
		foreach($this->getServices() as $service) {
			if($service->canPerform()) {
				$res .= $service->viewHomePage();
			}
		}
		return $res;
	}
}