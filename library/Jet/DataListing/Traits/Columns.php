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
trait DataListing_Traits_Columns
{
	/**
	 * @var DataListing_Column[]
	 */
	protected array $columns = [];
	
	
	/**
	 * @return DataListing_Column[]
	 */
	public function getColumns() : array
	{
		return $this->columns;
	}
	
	public function addColumn( DataListing_Column $column ) : void
	{
		$this->columns[$column->getKey()] = $column;
		$column->setListing( $this );
		$column->setIndex( count($this->columns) );
	}
	
	public function columnExists( string $column_key ) : bool
	{
		return isset( $this->columns[$column_key] );
	}
	
	
	public function column( string $column_key ) : DataListing_Column
	{
		return $this->columns[$column_key];
	}
	
	
	/**
	 * @return DataListing_Column[]
	 */
	public function getVisibleColumns(): array
	{
		$columns = [];
		foreach( $this->getColumns() as $col ) {
			if($col->getIsVisible()) {
				$columns[] = $col;
			}
		}
		
		uasort($columns, function( DataListing_Column $a, DataListing_Column $b ) : int {
			return $a->getIndex() <=> $b->getIndex();
		});
		
		return $columns;
	}
	
	/**
	 * @return DataListing_Column[]
	 */
	public function getNotVisibleColumns(): array
	{
		$columns = [];
		foreach( $this->getColumns() as $col ) {
			if(!$col->getIsVisible()) {
				$columns[] = $col;
			}
		}
		
		uasort($columns, function( DataListing_Column $a, DataListing_Column $b ) : int {
			return $a->getIndex() <=> $b->getIndex();
		});
		
		return $columns;
	}
}