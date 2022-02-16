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
abstract class Data_Listing extends BaseObject
{
	/**
	 * @var array
	 */
	protected array $get_param_values = [];

	/**
	 * @var ?Form
	 */
	protected ?Form $filter_form = null;


	/**
	 * @var int
	 */
	protected int $pagination_page_no = 1;

	/**
	 * @var int
	 */
	protected int $pagination_items_per_page = 0;

	/**
	 * @var string
	 */
	protected string $default_sort = '';

	/**
	 * @var array
	 */
	protected array $grid_columns = [];

	/**
	 * @var string
	 */
	protected string $sort = '';


	/**
	 * @var ?UI_dataGrid
	 */
	protected ?UI_dataGrid $grid = null;

	/**
	 * @var ?array
	 */
	protected ?array $filter_where = null;

	/**
	 * @var Data_Listing_Filter[]
	 */
	protected array $filters = [];

	/**
	 *
	 */
	public function __construct()
	{
		$this->initFilters();
	}

	/**
	 *
	 */
	abstract protected function initFilters() : void;

	/**
	 * @return string
	 */
	public function getDefaultSort(): string
	{
		return $this->default_sort;
	}

	/**
	 * @param string $default_sort
	 */
	public function setDefaultSort( string $default_sort ): void
	{
		$this->default_sort = $default_sort;
	}


	/**
	 *
	 */
	public function handle(): void
	{
		$this->catchGetParams();
		$this->pagination_catchGetParams();
		$this->sort_catchGetParams();

		$this->catchFilterForm();

	}

	/**
	 * @param array $grid_columns
	 */
	public function setGridColumns( array $grid_columns ): void
	{
		$this->grid_columns = $grid_columns;
	}

	/**
	 * @return array
	 */
	public function getGridColumns(): array
	{
		return $this->grid_columns;
	}



	/**
	 * @param string $parameter
	 * @param mixed $value
	 */
	public function setGetParam( string $parameter, mixed $value ): void
	{
		$this->get_param_values[$parameter] = $value;
	}

	/**
	 * @param string $parameter
	 */
	public function unsetGetParam( string $parameter ): void
	{
		unset( $this->get_param_values[$parameter] );
	}


	/**
	 *
	 * @return string
	 */
	public function getURI(): string
	{
		$get_params = $this->get_param_values;

		foreach( $get_params as $k => $v ) {
			if( !$v ) {
				unset( $get_params[$k] );
			}
		}

		return '?' . http_build_query( $get_params );
	}


	/**
	 * @return Form
	 */
	public function getFilterForm(): Form
	{
		if( !$this->filter_form ) {
			$this->filter_form = new Form( 'filter_form', [] );
			$this->filter_form->setAction( $this->getURI() );

			foreach( $this->filters as $filter ) {
				$filter->generateFormFields( $this->filter_form );
			}
		}

		return $this->filter_form;
	}

	/**
	 *
	 */
	protected function catchFilterForm(): void
	{
		$form = $this->getFilterForm();

		if(
			$form->catchInput() &&
			$form->validate()
		) {
			foreach( $this->filters as $filter ) {
				$filter->catchForm( $form );
			}

			$this->pagination_setPageNo( 1 );

			Http_Headers::movedTemporary( $this->getURI() );
		}

	}


	/**
	 *
	 */
	protected function catchGetParams(): void
	{
		foreach( $this->filters as $filter ) {
			$filter->catchGetParams();
		}
	}


	/**
	 * @param array $where
	 */
	public function addWhere( array $where ): void
	{
		if( $this->filter_where ) {
			$this->filter_where[] = 'AND';
		}

		$this->filter_where[] = $where;
	}

	/**
	 * @return array
	 */
	public function getWhere(): array
	{
		if( $this->filter_where === null ) {
			$this->filter_where = [];

			foreach( $this->filters as $filter ) {
				$filter->generateWhere();
			}

		}
		return $this->filter_where;
	}


	/**
	 * @param int $page_no
	 */
	protected function pagination_setPageNo( int $page_no ): void
	{
		$this->pagination_page_no = $page_no;
		$this->setGetParam( SysConf_Jet_Data_Listing::getPaginationPageNoGetParam(), $page_no );
	}

	/**
	 * @param int $items_per_page
	 */
	protected function pagination_setItemsPerPage( int $items_per_page ): void
	{

		if( $items_per_page > SysConf_Jet_Data_Listing::getPaginationMaxItemsPerPage() ) {
			$items_per_page = SysConf_Jet_Data_Listing::getPaginationMaxItemsPerPage();
		}

		$this->pagination_items_per_page = $items_per_page;
		$this->setGetParam( SysConf_Jet_Data_Listing::getPaginationItemsPerPageParam(), $items_per_page );
	}


	/**
	 *
	 */
	protected function pagination_catchGetParams(): void
	{
		$GET = Http_Request::GET();

		$param = SysConf_Jet_Data_Listing::getPaginationPageNoGetParam();
		if( $GET->exists( $param ) ) {
			$this->pagination_setPageNo( $GET->getInt( $param ) );
		}

		$param = SysConf_Jet_Data_Listing::getPaginationItemsPerPageParam();
		if( $GET->exists( $param ) ) {
			$this->pagination_setItemsPerPage( $GET->getInt( $param ) );
		}
	}

	/**
	 * @return int
	 */
	protected function pagination_getPageNo(): int
	{
		return $this->pagination_page_no;
	}

	/**
	 * @return int
	 */
	protected function pagination_getItemsPerPage(): int
	{
		if( !$this->pagination_items_per_page ) {
			return SysConf_Jet_Data_Listing::getPaginationDefaultItemsPerPage();
		}

		return $this->pagination_items_per_page;
	}

	/**
	 * @param string $sort_by
	 */
	protected function sort_setSort( string $sort_by ): void
	{
		$sort_column = $sort_by;

		if( $sort_column[0] == '-' || $sort_column[0] == '+' ) {
			$sort_column = substr( $sort_column, 1 );
		}

		$grid_columns = $this->getGridColumns();

		if(
			!isset( $grid_columns[$sort_column] ) ||
			!empty( $grid_columns[$sort_column]['disallow_sort'] )
		) {
			return;
		}

		$this->sort = $sort_by;
		$this->setGetParam( SysConf_Jet_Data_Listing::getSortGetParam(), $sort_by );
	}

	/**
	 *
	 */
	protected function sort_catchGetParams(): void
	{
		$GET = Http_Request::GET();

		$param = SysConf_Jet_Data_Listing::getSortGetParam();
		if( $GET->exists( $param ) ) {
			$this->sort_setSort( $GET->getString( $param ) );
		}
	}

	/**
	 * @return string
	 */
	protected function sort_getSortBy(): string
	{
		return $this->sort ? : $this->default_sort;
	}


	/**
	 * @return callable
	 */
	protected function getGrid_getPaginatorURLCreator(): callable
	{
		return function( $page_no ) {
			$params = $this->get_param_values;
			$params[SysConf_Jet_Data_Listing::getPaginationPageNoGetParam()] = (int)$page_no;

			return Http_Request::currentURI( $params );
		};
	}

	/**
	 * @return callable
	 */
	protected function getGrid_getSortURLCreator(): callable
	{
		return function( $column_name, $desc ) {
			$params = $this->get_param_values;
			$params[SysConf_Jet_Data_Listing::getSortGetParam()] = ($desc ? '-' : '') . $column_name;

			return Http_Request::currentURI( $params );
		};
	}

	/**
	 * @return Data_Paginator
	 */
	protected function getGrid_createPaginator(): Data_Paginator
	{
		return new Data_Paginator(
			$this->pagination_getPageNo(),
			$this->pagination_getItemsPerPage(),
			$this->getGrid_getPaginatorURLCreator()
		);
	}

	/**
	 * @return DataModel_Fetch_Instances
	 */
	protected function getGrid_prepareList(): DataModel_Fetch_Instances
	{
		$list = $this->getList();

		$list->getQuery()->setWhere( $this->getWhere() );
		$list->getQuery()->setOrderBy( $this->sort_getSortBy() );

		return $list;
	}

	/**
	 *
	 */
	protected function getGrid_createColumns(): void
	{
		foreach( $this->getGridColumns() as $column_id=>$column_definition ) {

			$column = $this->grid->addColumn(
				$column_id,
				Tr::_( $column_definition['title'] )
			);

			if( !empty( $column_definition['disallow_sort'] ) ) {
				$column->setAllowSort( false );
			}
		}

	}

	/**
	 * @return UI_dataGrid
	 */
	public function getGrid(): UI_dataGrid
	{

		if( !$this->grid ) {
			$this->grid = new UI_dataGrid();

			$this->getGrid_createColumns();

			$this->grid->setPaginator( $this->getGrid_createPaginator() );
			$this->grid->setSortUrlCreator( $this->getGrid_getSortURLCreator() );
			$this->grid->setSortBy( $this->sort_getSortBy() );
			$this->grid->setData( $this->getGrid_prepareList() );

		}

		return $this->grid;
	}

	/**
	 * @return DataModel_Fetch_Instances
	 */
	abstract protected function getList(): DataModel_Fetch_Instances;

}