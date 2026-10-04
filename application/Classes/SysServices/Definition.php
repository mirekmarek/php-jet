<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Closure;
use Jet\Application_Module;
use Jet\Data_DateTime;
use Jet\Locale;
use Jet\MVC;
use Jet\MVC_Base_LocalizedData_Interface;

class SysServices_Definition {
	
	protected Application_Module $module;
	
	protected string $code = '';
	protected string $name = '';
	protected string $description = '';
	
	protected string $service_code = '';
	protected ?Closure $service = null;

	protected bool $requires_base_designation = false;
	
	/**
	 * @var array<MVC_Base_LocalizedData_Interface>|null
	 */
	protected ?array $allowed_bases = null;
	

	protected bool $is_periodically_triggered_service = true;
	
	
	public function __construct( Application_Module $module, string $name, string $description, string $service_code, Closure $service )
	{
		$this->module = $module;
		$this->code = $module->getModuleManifest()->getName().':'.$service_code;
		
		$this->name = $name;
		$this->description = $description;
		
		$this->service_code = $service_code;
		$this->service = $service;
	}
	
	
	public function getRequiresBaseDesignation(): bool
	{
		return $this->requires_base_designation;
	}
	
	public function setRequiresBaseDesignation( bool $requires_base_designation ): void
	{
		$this->requires_base_designation = $requires_base_designation;
	}
	
	
	/**
	 * @return MVC_Base_LocalizedData_Interface[]
	 */
	public function getAllowedBases(): array
	{
		if(!$this->requires_base_designation) {
			return [];
		}
		
		if( $this->allowed_bases===null ) {
			$this->allowed_bases = [];
			
			foreach(MVC::getBases() as $base) {
				foreach($base->getLocales() as $locale) {
					/** @var Locale $locale */
					$b = $base->getLocalizedData( $locale );
					
					$this->allowed_bases[] = $b;
				}
			}
		}
		
		$bases = [];
		
		foreach($this->allowed_bases as $base) {
			$bases[$base->getBase()->getId().':'.$base->getLocale()] = $base;
		}
		
		return $bases;
	}
	
	
	
	/**
	 * @param MVC_Base_LocalizedData_Interface[] $allowed_bases
	 * @return void
	 */
	public function setAllowedBases( array $allowed_bases ): void
	{
		$this->allowed_bases = $allowed_bases;
	}
	
	public function isAllowedForBase( ?MVC_Base_LocalizedData_Interface $base=null ): bool
	{
		if(!$base) {
			if(!$this->getRequiresBaseDesignation()) {
				return true;
			} else {
				return false;
			}
		}
		
		foreach( $this->getAllowedBases() as $a_b) {
			if(
				$a_b->getBase()->getId() === $base->getBase()->getId() &&
				$a_b->getLocale()->toString()==$base->getLocale()->toString()
			) {
				return true;
			}
		}
		
		return false;
	}
	
	
	public function getModule(): Application_Module
	{
		return $this->module;
	}

	public function getCode(): string
	{
		return $this->code;
	}
	
	public function getName(): string
	{
		return $this->name;
	}
	
	public function getDescription(): string
	{
		return $this->description;
	}
	
	public function getServiceCode(): string
	{
		return $this->service_code;
	}
	
	
	public function isActive(): bool
	{
		return SysServices::getManager()->serviceIsActive( $this );
	}
	
	public function getIsPeriodicallyTriggeredService(): bool
	{
		return $this->is_periodically_triggered_service;
	}
	
	public function setIsPeriodicallyTriggeredService( bool $is_periodically_triggered_service ): void
	{
		$this->is_periodically_triggered_service = $is_periodically_triggered_service;
	}
	
	
	
	public function getURL( ?MVC_Base_LocalizedData_Interface $base = null ) : string
	{
		return SysServices::getManager()->getSysServiceURL( $this, $base );
	}
	
	
	
	public function perform( ?MVC_Base_LocalizedData_Interface $base = null ) : void
	{
		$this->service->call( $this->module, $base );
	}
	
	
	public function planOutage( ?Data_DateTime $from_date_time, ?Data_DateTime $till_date_time ) : void
	{
		SysServices::getManager()->planServiceOutage( $this, $from_date_time, $till_date_time );
	}
	
	public function cancelPlannedOutage( int $plan_id ) : void
	{
		SysServices::getManager()->cancelPlannedOutage( $this, $plan_id );
	}
	
	public function shutdown() : void
	{
		SysServices::getManager()->shutdownService( $this );
	}
	
	
	public function start() : void
	{
		SysServices::getManager()->startService( $this );
	}
	
}