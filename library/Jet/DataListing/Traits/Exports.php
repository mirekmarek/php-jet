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
	//TODO: sysconf
	protected int $export_limit = 500;
	
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
	
	public function exportExists( string $type ) : bool
	{
		return isset($this->exports[$type]);
	}
	
	public function export( string $type ) : void
	{
		$this->exports[$type]->export();
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