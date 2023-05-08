<?php
/**
 *
 * @copyright %<COPYRIGHT>%
 * @license  %<LICENSE>%
 * @author  %<AUTHOR>%
 */
namespace %<NAMESPACE>%;

use %<DATA_MODEL_CLASS_NAME>% as %<DATA_MODEL_CLASS_ALIAS>%;

use Jet\DataModel_Fetch_Instances;
use Jet\MVC_View;
use Jet\DataListing;

/**
 *
 */
class Listing extends DataListing
{
	
	protected Controller_Main $controller;
	protected MVC_View $column_view;
	protected MVC_View $filter_view;
	
	
	public function __construct( Controller_Main $controller, MVC_View $column_view, MVC_View $filter_view )
	{
		$column_view->setController( $controller );
		$filter_view->setController( $controller );
		
		$this->column_view = $column_view;
		$this->filter_view = $filter_view;
		
		$this->addColumn( new Listing_Column_Edit() );
		$this->addColumn( new Listing_Column_ID() );
		$this->addColumn( new Listing_Column_ItemName() );
		
		
		$this->addFilter( new Listing_Filter_Search() );
		
	}
	
	
	protected function getItemList(): DataModel_Fetch_Instances
	{
		return %<DATA_MODEL_CLASS_ALIAS>%::getList();
	}
	
	protected function getIdList(): array
	{
		return [];
	}
	
	public function getFilterView(): MVC_View
	{
		return $this->filter_view;
	}
	
	public function getColumnView(): MVC_View
	{
		return $this->column_view;
	}
	
	public function itemGetter( int|string $id ): mixed
	{
		return %<DATA_MODEL_CLASS_ALIAS>%::get( $id );
	}

}