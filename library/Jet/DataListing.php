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
abstract class DataListing extends BaseObject
{
	use DataListing_Traits_Params;
	use DataListing_Traits_Columns;
	use DataListing_Traits_Operations;
	use DataListing_Traits_Filters;
	use DataListing_Traits_Exports;
	use DataListing_Traits_GridCreator;
	use DataListing_Traits_Sort;
	use DataListing_Traits_Pagination;
	use DataListing_Traits_PrevNext;
	
	/**
	 * @var array<string|int>|null
	 */
	protected ?array $all_ids = null;
	
	abstract protected function getItemList(): DataModel_Fetch_Instances;
	
	/**
	 * @return array<string|int>
	 */
	abstract protected function getIdList() : array;
	
	abstract public function getFilterView() : MVC_View;
	
	abstract public function getColumnView() : MVC_View;
	
	abstract public function itemGetter( int|string $id ) : mixed;
	
	public function handle(): void
	{
		$this->catchFilterParams();
		$this->catchPaginationParams();
		$this->catchSortParams();
		$this->catchFilterForm();
	}
	
	
	public function getList(): DataModel_Fetch_Instances
	{
		$list = $this->getItemList();
		
		$list->getQuery()->setWhere( $this->getFilterWhere() );
		
		if( ($order_by = $this->getQueryOrderBy()) ) {
			$list->getQuery()->setOrderBy( $order_by );
		}
		
		return $list;
	}
	
	
	/**
	 * @return array<string|int>
	 */
	public function getAllIds(): array
	{
		if( $this->all_ids === null ) {
			$this->all_ids = $this->getIdList();
		}
		
		return $this->all_ids;
	}
	

}