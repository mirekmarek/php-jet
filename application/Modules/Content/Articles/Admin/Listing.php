<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Content\Articles\Admin;

use JetApplication\Content_Article;
use Jet\MVC_View;
use Jet\DataListing;
use Jet\DataModel_Fetch_Instances;

/**
 *
 */
class Listing extends DataListing
{
	protected MVC_View $column_view;
	protected MVC_View $filter_view;
	
	
	public function __construct( MVC_View $column_view, MVC_View $filter_view )
	{
		$this->column_view = $column_view;
		$this->filter_view = $filter_view;
		
		$this->addColumn( new Listing_Column_Edit() );
		$this->addColumn( new Listing_Column_Title() );
		$this->addColumn( new Listing_Column_DateTime() );
		
		
		$this->addFilter( new Listing_Filter_Search() );
		
	}
	
	
	protected function getItemList(): DataModel_Fetch_Instances
	{
		return Content_Article::getList();
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
		return Content_Article::get( $id );
	}
}