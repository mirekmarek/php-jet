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
trait DataListing_Traits_GridCreator
{
	
	protected ?UI_dataGrid $grid = null;
	
	public function getGrid(): UI_dataGrid
	{
		
		if( !$this->grid ) {
			$this->grid = new UI_dataGrid();
			
			$this->createGridColumns();
			
			$this->grid->setPaginator( $this->createPaginator() );
			
			$this->grid->setSortUrlCreator( $this->getSortURLCreator() );
			$this->grid->setSortBy( $this->getGridSortBy() );
			
			$this->grid->setData( $this->getList() );
			
		}
		
		return $this->grid;
	}
	
	protected function createGridColumns(): void
	{
		foreach($this->getVisibleColumns() as $column) {
			
			$grid_column = $this->grid->addColumn(
				$column->getKey(),
				$column->getTitle()
			);
			
			$grid_column->setAllowSort( !$column->getDisallowSort() );
			
			$column->initializer( $grid_column );
			
			$grid_column->setRenderer( function( $item ) use ( $column ) {
				return $column->render( $item );
			} );
			
		}
	}
	
	
}
