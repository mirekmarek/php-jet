<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
trait DataListing_Traits_Exports
{
	protected int $export_limit = -1;
	
	/**
	 * @var DataListing_Export[]
	 */
	protected array $exports = [];
	
	/**
	 * @return DataListing_Export[]
	 */
	public function getExports() : array
	{
		return $this->exports;
	}
	
	public function addExport( DataListing_Export $export ) : void
	{
		$this->exports[$export->getKey()] = $export;
		$export->setListing( $this );
	}
	
	public function exportExists( string $key ) : bool
	{
		return isset($this->exports[$key]);
	}
	
	public function export( string $key ) : DataListing_Export
	{
		return $this->exports[$key];
	}
	
	public function getExportTypes() : array
	{
		$res = [];
		
		foreach($this->exports as $export) {
			$res[$export->getKey()] = $export->getTitle();
		}
		
		return $res;
	}
	
	public function getExportLimit(): int
	{
		return $this->export_limit;
	}
	
	public function setExportLimit( int $export_limit ): void
	{
		$this->export_limit = $export_limit;
	}

}