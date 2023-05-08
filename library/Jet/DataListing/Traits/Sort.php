<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use Jet\SysConf\Jet\SysConf_Jet_DataListing;

/**
 *
 */
trait DataListing_Traits_Sort
{
	protected string $default_sort = '';
	
	protected string $sort = '';
	
	public function getDefaultSort(): string
	{
		return $this->default_sort;
	}
	
	public function setDefaultSort( string $default_sort ): void
	{
		$this->default_sort = $default_sort;
	}
	
	protected function setSort( string $sort_by ): void
	{
		$sort_column = $sort_by;
		
		if( $sort_column[0] == '-' || $sort_column[0] == '+' ) {
			$sort_column = substr( $sort_column, 1 );
		}
		
		$columns = $this->getColumns();
		
		if(
			!isset( $columns[$sort_column] ) ||
			!empty( $columns[$sort_column]->getDisallowSort() )
		) {
			return;
		}
		
		$this->sort = $sort_by;
		$this->setParam( SysConf_Jet_DataListing::getSortGetParam(), $sort_by );
	}
	
	protected function catchSortParams(): void
	{
		$GET = Http_Request::GET();
		
		$param = SysConf_Jet_DataListing::getSortGetParam();
		if( $GET->exists( $param ) ) {
			$this->setSort( $GET->getString( $param ) );
		}
	}
	
	protected function getGridSortBy(): string
	{
		return $this->sort ? : $this->default_sort;
	}
	
	public function getQueryOrderBy(): string|array
	{
		$order_by = $this->getGridSortBy();
		
		if( $order_by ) {
			$desc = false;
			if(
				isset( $order_by[0] ) &&
				(
					$order_by[0] == '-' ||
					$order_by[0] == '+'
				)
			) {
				$desc = $order_by[0] == '-';
				$order_by = substr( $order_by, 1 );
			}
			
			if( isset( $this->columns[$order_by] ) ) {
				if( $desc ) {
					return $this->columns[$order_by]->getOrderByDesc();
				} else {
					return $this->columns[$order_by]->getOrderByAsc();
				}
			}
		}
		
		return '';
	}
	
}