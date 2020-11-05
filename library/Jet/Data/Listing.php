<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;


abstract class Data_Listing extends BaseObject {

	/**
	 * @var string
	 */
	protected static $pagination_page_no_get_param = 'p';

	/**
	 * @var string
	 */
	protected static $pagination_items_per_page_param = 'items_per_page';

	/**
	 * @var int
	 */
	protected static $pagination_max_items_per_page = 500;

	/**
	 * @var int
	 */
	protected static $pagination_default_items_per_page = 50;

	/**
	 * @var string
	 */
	protected static $sort_get_param = 'sort';


	/**
	 * @var array
	 */
	protected $get_param_values = [];

	/**
	 * @var Form
	 */
	protected $filter_form;


	/**
	 * @var int
	 */
	protected $pagination_page_no = 1;

	/**
	 * @var int
	 */
	protected $pagination_items_per_page = 0;

	/**
	 * @var string
	 */
	protected $default_sort = '';

	/**
	 * @var string
	 */
	protected $sort = '';

	/**
	 * @var array
	 */
	protected $grid_columns = [];

	/**
	 * @var array
	 */
	protected $grid_columns_schema = [];

	/**
	 * @var UI_DataGrid
	 */
	protected $grid;

	/**
	 * @var array
	 */
	protected $filter_where;

	/**
	 *
	 */
	public function __construct()
	{
	}

	/**
	 * @return string
	 */
	public static function getPaginationPageNoGetParam()
	{
		return static::$pagination_page_no_get_param;
	}

	/**
	 * @param string $pagination_page_no_get_param
	 */
	public static function setPaginationPageNoGetParam( $pagination_page_no_get_param )
	{
		static::$pagination_page_no_get_param = $pagination_page_no_get_param;
	}

	/**
	 * @return string
	 */
	public static function getPaginationItemsPerPageParam()
	{
		return static::$pagination_items_per_page_param;
	}

	/**
	 * @param string $pagination_items_per_page_param
	 */
	public static function setPaginationItemsPerPageParam( $pagination_items_per_page_param )
	{
		static::$pagination_items_per_page_param = $pagination_items_per_page_param;
	}

	/**
	 * @return int
	 */
	public static function getPaginationMaxItemsPerPage()
	{
		return static::$pagination_max_items_per_page;
	}

	/**
	 * @param int $pagination_max_items_per_page
	 */
	public static function setPaginationMaxItemsPerPage( $pagination_max_items_per_page )
	{
		static::$pagination_max_items_per_page = $pagination_max_items_per_page;
	}

	/**
	 * @return int
	 */
	public static function getPaginationDefaultItemsPerPage()
	{
		return static::$pagination_default_items_per_page;
	}

	/**
	 * @param int $pagination_default_items_per_page
	 */
	public static function setPaginationDefaultItemsPerPage( $pagination_default_items_per_page )
	{
		static::$pagination_default_items_per_page = $pagination_default_items_per_page;
	}

	/**
	 * @return string
	 */
	public static function getSortGetParam()
	{
		return static::$sort_get_param;
	}

	/**
	 * @param string $sort_get_param
	 */
	public static function setSortGetParam( $sort_get_param )
	{
		static::$sort_get_param = $sort_get_param;
	}

	/**
	 * @return string
	 */
	public function getDefaultSort()
	{
		return $this->default_sort;
	}

	/**
	 * @param string $default_sort
	 */
	public function setDefaultSort( $default_sort )
	{
		$this->default_sort = $default_sort;
	}



	/**
	 *
	 */
	public function handle()
	{
		$this->filter_catchGetParams();
		$this->pagination_catchGetParams();
		$this->sort_catchGetParams();

		$this->filter_catchForm();

	}

	/**
	 * @return array
	 */
	public function getGridColumns()
	{
		return $this->grid_columns;
	}

	/**
	 * @param array $grid_columns_schema
	 */
	public function setGridColumnsSchema( array $grid_columns_schema )
	{
		$this->grid_columns_schema = $grid_columns_schema;
	}

	/**
	 * @return array
	 */
	public function getGridColumnsSchema()
	{
		if(!$this->grid_columns_schema) {
			$this->grid_columns_schema = array_keys($this->getGridColumns());
		}

		return $this->grid_columns_schema;
	}


	/**
	 * @param string $p
	 * @param mixed $v
	 */
	protected function setGetParam( $p, $v )
	{
		$this->get_param_values[$p] = $v;
	}

	/**
	 * @param string $p
	 */
	protected function unsetGetParam( $p )
	{
		unset( $this->get_param_values[$p]);
	}


	/**
	 * @param array|null $get_params
	 *
	 * @return string
	 */
	protected function getURI( array $get_params=null )
	{
		if($get_params===null) {
			$get_params = $this->get_param_values;
		}

		foreach( $get_params as $k=>$v ) {
			if(!$v) {
				unset( $get_params[$k] );
			}
		}

		return '?'.http_build_query( $get_params );
	}


	/**
	 * @return Form
	 */
	public function filter_getForm()
	{
		if(!$this->filter_form) {
			$this->filter_form = new Form('filter_form', []);
			$this->filter_form->setAction($this->getURI());

			foreach( $this->filters as $filter ) {
				$this->{"filter_{$filter}_getForm"}( $this->filter_form );
			}
		}

		return $this->filter_form;
	}

	/**
	 *
	 */
	public function filter_catchForm()
	{
		$form = $this->filter_getForm();

		if(
			$form->catchInput() &&
			$form->validate()
		) {
			foreach( $this->filters as $filter ) {
				$this->{"filter_{$filter}_catchForm"}( $form );
			}

			$this->pagination_setPageNo(1);

			Http_Headers::movedTemporary( $this->getURI() );
		}

	}


	/**
	 *
	 */
	public function filter_catchGetParams()
	{
		foreach( $this->filters as $filter ) {
			$this->{"filter_{$filter}_catchGetParams"}();
		}
	}

	/**
	 *
	 */
	protected function filter_setupWhere()
	{
		foreach( $this->filters as $filter ) {
			$this->{"filter_{$filter}_setupWhere"}();
		}

	}


	/**
	 * @param array $where
	 */
	public function filter_addWhere( array $where )
	{
		if($this->filter_where) {
			$this->filter_where[] = 'AND';
		}

		$this->filter_where[] = $where;
	}

	/**
	 * @return array
	 */
	public function filter_getWhere()
	{
		if($this->filter_where===null) {
			$this->filter_where = [];

			foreach( $this->filters as $filter ) {
				$this->{"filter_{$filter}_getWhere"}( $this->filter_form );
			}

		}
		return $this->filter_where;
	}


	/**
	 * @param int $page_no
	 */
	public function pagination_setPageNo( $page_no )
	{
		$page_no = (int)$page_no;

		$this->pagination_page_no = $page_no;
		$this->setGetParam( static::$pagination_page_no_get_param, $page_no );
	}

	/**
	 * @param int $items_per_page
	 */
	public function pagination_setItemsPerPage( $items_per_page )
	{
		$items_per_page = (int)$items_per_page;

		if($items_per_page>static::$pagination_max_items_per_page) {
			$items_per_page = static::$pagination_max_items_per_page;
		}

		$this->pagination_items_per_page = $items_per_page;
		$this->setGetParam( static::$pagination_items_per_page_param, $items_per_page );
	}


	/**
	 *
	 */
	protected function pagination_catchGetParams()
	{
		$GET = Http_Request::GET();

		$param = static::$pagination_page_no_get_param;
		if($GET->exists($param)) {
			$this->pagination_setPageNo( $GET->getInt($param) );
		}

		$param = static::$pagination_items_per_page_param;
		if($GET->exists($param)) {
			$this->pagination_setItemsPerPage( $GET->getInt($param) );
		}
	}

	/**
	 * @return int
	 */
	protected function pagination_getPageNo()
	{
		return $this->pagination_page_no;
	}

	/**
	 * @return int
	 */
	protected function pagination_getItemsPerPage()
	{
		if(!$this->pagination_items_per_page) {
			return static::$pagination_default_items_per_page;
		}

		return $this->pagination_items_per_page;
	}

	/**
	 * @param $sort_by
	 */
	public function sort_setSort( $sort_by )
	{
		$sort_column = $sort_by;

		if($sort_column[0]=='-' || $sort_column[0]=='+') {
			$sort_column = substr($sort_column, 1);
		}

		$grid_columns = $this->getGridColumns();

		if(
			!isset($grid_columns[$sort_column]) ||
			!empty($grid_columns[$sort_column]['disallow_sort'])
		) {
			return;
		}

		$this->sort = $sort_by;
		$this->setGetParam( static::$sort_get_param, $sort_by );
	}

	/**
	 *
	 */
	protected function sort_catchGetParams()
	{
		$GET = Http_Request::GET();

		$param = static::$sort_get_param;
		if($GET->exists($param)) {
			$this->sort_setSort( $GET->getString($param) );
		}
	}

	/**
	 * @return string
	 */
	protected function getSortBy()
	{
		return $this->sort?:$this->default_sort;
	}


	/**
	 * @return callable
	 */
	protected function getGrid_getPaginatorURLCreator()
	{
		return function( $page_no ) {
			$params = $this->get_param_values;
			$params[static::$pagination_page_no_get_param] = (int)$page_no;

			return Http_Request::currentURI($params);
		};
	}

	/**
	 * @return callable
	 */
	protected function getGrid_getSortURLCreator()
	{
		return function( $column_name, $desc ) {
			$params = $this->get_param_values;
			$params[static::$sort_get_param] = ($desc?'-':'').$column_name;

			return Http_Request::currentURI($params);
		};
	}

	/**
	 * @return Data_Paginator
	 */
	protected function getGrid_createPaginator()
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
	protected function getGrid_prepareList()
	{
		$list = $this->getList();

		$list->getQuery()->setWhere( $this->filter_getWhere() );
		$list->getQuery()->setOrderBy( $this->getSortBy() );

		return $list;
	}

	/**
	 *
	 */
	protected function getGrid_createColumns()
	{
		foreach( $this->getGridColumnsSchema() as $column_id ) {
			$column_definition = $this->getGridColumns()[$column_id];

			$column = $this->grid->addColumn( $column_id, Tr::_($column_definition['title']) );
			if(!empty($column_definition['disallow_sort'])) {
				$column->setAllowSort( false );
			}
		}

	}

	/**
	 * @return UI_DataGrid
	 */
	public function getGrid()
	{

		if(!$this->grid) {
			$this->grid = new UI_dataGrid();;

			$this->getGrid_createColumns();

			$this->grid->setPaginator( $this->getGrid_createPaginator() );
			$this->grid->setSortUrlCreator( $this->getGrid_getSortURLCreator() );
			$this->grid->setSortBy( $this->getSortBy() );
			$this->grid->setData( $this->getGrid_prepareList() );

		}

		return $this->grid;
	}

	/**
	 * @return DataModel_Fetch_Instances
	 */
	abstract protected function getList();

}