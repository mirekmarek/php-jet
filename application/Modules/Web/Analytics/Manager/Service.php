<?php
/**
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license EUPL 1.2  https://eupl.eu/1.2/en/
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\WEb\Analytics\Manager;

abstract class Service {
	
	protected bool $enabled = false;
	
	protected function init() : void
	{
	
	}
	
	public function getEnabled(): bool
	{
		return $this->enabled;
	}
	
	public function setEnabled( bool $enabled ): void
	{
		$this->enabled = $enabled;
	}
	
	abstract public function header() : string;
	
	abstract public function documentStart() : string;
	
	abstract public function documentEnd() : string;
	
	abstract public function catchConversionSourceInfo() : void;
	
	/**
	 * @param string $event
	 * @param array<string,mixed> $event_data
	 * @return string
	 */
	abstract public function customEvent( string $event, array $event_data=[] ) : string;
	
}