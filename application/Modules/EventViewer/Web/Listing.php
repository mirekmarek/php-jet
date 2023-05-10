<?php
/**
 *
 * @copyright 
 * @license  
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\Admin\EventViewer\Web;

use Jet\DataListing;
use Jet\DataModel_Fetch_Instances;
use Jet\MVC_View;
use JetApplication\Logger_Web_Event as Event;


/**
 *
 */
class Listing extends DataListing {
	
	protected Controller_Main $controller;
	protected MVC_View $column_view;
	protected MVC_View $filter_view;
	
	public function __construct( Controller_Main $controller, MVC_View $column_view, MVC_View $filter_view )
	{
		$column_view->setController( $controller );
		$filter_view->setController( $controller );
		
		$this->column_view = $column_view;
		$this->filter_view = $filter_view;
		
		$this->addColumn( new Listing_Column_ID() );
		$this->addColumn( new Listing_Column_DateTime() );
		$this->addColumn( new Listing_Column_EventClass() );
		$this->addColumn( new Listing_Column_Event() );
		$this->addColumn( new Listing_Column_EventMessage() );
		$this->addColumn( new Listing_Column_ContextObjectId() );
		$this->addColumn( new Listing_Column_ContextObjectName() );
		$this->addColumn( new Listing_Column_UserId() );
		$this->addColumn( new Listing_Column_UserName() );
		
		$this->setDefaultSort( '-id' );
		
		$this->addFilter( new Listing_Filter_Search() );
		$this->addFilter( new Listing_Filter_EventClass() );
		$this->addFilter( new Listing_Filter_Event() );
		$this->addFilter( new Listing_Filter_DateTime() );
		$this->addFilter( new Listing_Filter_User() );
		$this->addFilter( new Listing_Filter_ContextObject() );
		
		$this->addExport( new Listing_Export_CSV() );
	}
	
	
	protected function getItemList(): DataModel_Fetch_Instances
	{
		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return Event::getList();
	}
	
	protected function getIdList(): array
	{
		$ids = Event::fetchIDs( $this->getFilterWhere() );
		$ids->getQuery()->setOrderBy( $this->getQueryOrderBy() );
		
		return $ids->toArray();
	}
	
	public function itemGetter( int|string $id ): mixed
	{
		return Event::get( $id );
	}
	
	public function getFilterView(): MVC_View
	{
		return $this->filter_view;
	}
	
	public function getColumnView(): MVC_View
	{
		return $this->column_view;
	}
	
	public function getItemURI( int $item_id ) : string
	{
		$this->setParam('id', $item_id );
		
		$URI = $this->getURI();
		
		$this->unsetParam('id');
		
		return $URI;
	}
	
}