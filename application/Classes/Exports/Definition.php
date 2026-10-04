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


class Exports_Definition {
	
	protected Application_Module $module;
	
	protected string $code = '';
	protected string $name = '';
	protected string $description = '';
	
	protected string $export_code = '';
	protected ?Closure $export = null;
	
	protected bool $requires_base_designation = false;
	
	/**
	 * @var array<MVC_Base_LocalizedData_Interface>|null
	 */
	protected ?array $allowed_bases = null;
	
	
	public function __construct( Application_Module $module, string $name, string $description, string $export_code, Closure $export )
	{
		$this->module = $module;
		$this->code = $module->getModuleManifest()->getName().':'.$export_code;
		
		$this->name = $name;
		$this->description = $description;
		
		$this->export_code = $export_code;
		$this->export = $export;
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
				if($base->getIsSecret()) {
					continue;
				}
				
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
	
	public function getExportCode(): string
	{
		return $this->export_code;
	}
	
	
	public function isActive(): bool
	{
		return Exports::getManager()->exportIsActive( $this );
	}
	
	public function planOutage( ?Data_DateTime $from_date_time, ?Data_DateTime $till_date_time ) : void
	{
		Exports::getManager()->planExportOutage( $this, $from_date_time, $till_date_time );
	}
	
	public function cancelPlannedOutage( int $plan_id ) : void
	{
		Exports::getManager()->cancelPlannedOutage( $this, $plan_id );
	}
	
	public function shutdown() : void
	{
		Exports::getManager()->shutdownExport( $this );
	}
	
	
	public function start() : void
	{
		Exports::getManager()->startExport( $this );
	}
	
	public function getURL( ?MVC_Base_LocalizedData_Interface $base = null ) : string
	{
		return Exports::getManager()->getExportURL( $this, $base );
	}
	
	public function perform( ?MVC_Base_LocalizedData_Interface $base=null ) : void
	{
		$this->export->call( $this->module, $base );
	}
	
	
}