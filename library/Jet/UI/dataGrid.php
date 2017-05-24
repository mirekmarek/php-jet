<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class UI_dataGrid extends BaseObject
{

	/**
	 * @var string
	 */
	protected static $default_renderer_script = 'dataGrid';
	/**
	 * @var string
	 */
	protected static $default_renderer_script_header = 'dataGrid/header';
	/**
	 * @var string
	 */
	protected static $default_renderer_script_body = 'dataGrid/body';
	/**
	 * @var string
	 */
	protected static $default_renderer_script_paginator = 'dataGrid/paginator';
	/**
	 * @var string
	 */
	protected static $default_renderer_script_footer = 'dataGrid/footer';

	/**
	 * @var string
	 */
	protected static $default_sort_get_parameter = 'sort';
	/**
	 * @var string
	 */
	protected static $default_paginator_get_parameter = 'p';

	/**
	 * @var int
	 */
	protected static $default_items_per_page = 50;

	/**
	 * @var UI_dataGrid_column[]
	 */
	protected $columns = [];

	/**
	 * @var string
	 */
	protected $default_sort;

	/**
	 * @var Data_Paginator
	 */
	protected $paginator;

	/**
	 * @var array
	 */
	protected $data = [];

	/**
	 * @var string
	 */
	protected $sort_by = '';

	/**
	 * @var bool
	 */
	protected $allow_paginator = true;

	/**
	 * @var int
	 */
	protected $paginator_items_per_page;

	/**
	 * @var string
	 */
	protected $session_namespace = '';

	/**
	 * @var string
	 */
	protected $renderer_script;
	/**
	 * @var string
	 */
	protected $renderer_script_header;
	/**
	 * @var string
	 */
	protected $renderer_script_body;
	/**
	 * @var string
	 */
	protected $renderer_script_paginator;
	/**
	 * @var string
	 */
	protected $renderer_script_footer;

	/**
	 * @var callable
	 */
	protected $page_no_catcher;

	/**
	 * @var callable
	 */
	protected $page_url_creator;


	/**
	 * @var callable
	 */
	protected $sort_catcher;

	/**
	 * @var callable
	 */
	protected $sort_url_creator;


	/**
	 * @return string
	 */
	public static function getDefaultSortGetParameter()
	{
		return static::$default_sort_get_parameter;
	}

	/**
	 * @param string $default_sort_get_parameter
	 */
	public static function setDefaultSortGetParameter( $default_sort_get_parameter )
	{
		static::$default_sort_get_parameter = $default_sort_get_parameter;
	}

	/**
	 * @return string
	 */
	public static function getDefaultPaginatorGetParameter()
	{
		return static::$default_paginator_get_parameter;
	}

	/**
	 * @param string $default_paginator_get_parameter
	 */
	public static function setDefaultPaginatorGetParameter( $default_paginator_get_parameter )
	{
		static::$default_paginator_get_parameter = $default_paginator_get_parameter;
	}

	/**
	 * @return int
	 */
	public static function getDefaultItemsPerPage()
	{
		return static::$default_items_per_page;
	}

	/**
	 * @param int $default_items_per_page
	 */
	public static function setDefaultItemsPerPage( $default_items_per_page )
	{
		static::$default_items_per_page = $default_items_per_page;
	}

	/**
	 * @return string
	 */
	public static function getDefaultRendererScript()
	{
		return static::$default_renderer_script;
	}

	/**
	 * @param string $default_renderer_script
	 */
	public static function setDefaultRendererScript( $default_renderer_script )
	{
		static::$default_renderer_script = $default_renderer_script;
	}

	/**
	 * @return string
	 */
	public static function getDefaultRendererScriptHeader()
	{
		return static::$default_renderer_script_header;
	}

	/**
	 * @param string $default_renderer_script_header
	 */
	public static function setDefaultRendererScriptHeader( $default_renderer_script_header )
	{
		static::$default_renderer_script_header = $default_renderer_script_header;
	}

	/**
	 * @return string
	 */
	public static function getDefaultRendererScriptBody()
	{
		return static::$default_renderer_script_body;
	}

	/**
	 * @param string $default_renderer_script_body
	 */
	public static function setDefaultRendererScriptBody( $default_renderer_script_body )
	{
		static::$default_renderer_script_body = $default_renderer_script_body;
	}

	/**
	 * @return string
	 */
	public static function getDefaultRendererScriptPaginator()
	{
		return static::$default_renderer_script_paginator;
	}

	/**
	 * @param string $default_renderer_script_paginator
	 */
	public static function setDefaultRendererScriptPaginator( $default_renderer_script_paginator )
	{
		static::$default_renderer_script_paginator = $default_renderer_script_paginator;
	}

	/**
	 * @return string
	 */
	public static function getDefaultRendererScriptFooter()
	{
		return static::$default_renderer_script_footer;
	}

	/**
	 * @param string $default_renderer_script_footer
	 */
	public static function setDefaultRendererScriptFooter( $default_renderer_script_footer )
	{
		static::$default_renderer_script_footer = $default_renderer_script_footer;
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
	 * @param string $name
	 * @param string $title
	 *
	 * @return UI_dataGrid_column
	 */
	public function addColumn( $name, $title )
	{
		$this->columns[$name] = new UI_dataGrid_column( $name, $title );
		$this->columns[$name]->setGrid( $this );

		return $this->columns[$name];
	}

	/**
	 *
	 * @return UI_dataGrid_column[]
	 */
	public function getColumns()
	{
		return $this->columns;
	}

	/**
	 * @return bool
	 */
	public function getAllowPaginator()
	{
		return $this->allow_paginator;
	}

	/**
	 * @param bool $allow_paginator
	 */
	public function setAllowPaginator( $allow_paginator )
	{
		$this->allow_paginator = $allow_paginator;
	}

	/**
	 * @return Data_Paginator|null
	 */
	public function getPaginator()
	{
		return $this->paginator;
	}

	/**
	 *
	 * @param Data_Paginator $paginator
	 */
	public function setPaginator( Data_Paginator $paginator )
	{
		$this->paginator = $paginator;
	}

	/**
	 * @return array
	 */
	public function getData()
	{
		if( $this->paginator ) {

			if( $this->session_namespace ) {
				$session = new Session( $this->session_namespace );
				$session->setValue( 'page_no', $this->paginator->getCurrentPageNo() );
			}

			return $this->paginator->getData();
		}

		return $this->data;
	}

	/**
	 * @param array $data
	 */
	public function setData( $data )
	{

		if( $this->allow_paginator && !$this->paginator ) {
			$this->handlePaginator();
		}

		if(
			( $data instanceof DataModel_Fetch_Data || $data instanceof DataModel_Fetch_Object ) &&
			( $sort = $this->handleSortRequest() )
		) {
			/**
			 * @var DataModel_Fetch_Data $data
			 * @var DataModel_Query      $query
			 */
			$query = $data->getQuery();

			$query->setOrderBy( $sort );
		}

		if( $this->paginator ) {
			if( is_array( $data ) ) {
				/**
				 * @var array $data
				 */
				$this->paginator->setData( $data );
			}

			if(
				$data instanceof DataModel_Fetch_Data ||
				$data instanceof DataModel_Fetch_Object
			) {
				$this->paginator->setDataSource( $data );
			}

		} else {
			$this->data = $data;
		}


	}

	/**
	 *
	 */
	public function handlePaginator()
	{

		if( $this->session_namespace ) {
			$session = new Session( $this->session_namespace );
			$default_page_no = $session->getValue( 'page_no', 1 );
		} else {
			$default_page_no = 1;
		}

		$catcher = $this->getPageNoCatcher();

		$creator = $this->getPageUrlCreator();


		$this->paginator = new Data_Paginator(
			$catcher( $default_page_no ),
			$this->getPaginatorItemsPerPage(),
			$creator
		);

	}

	/**
	 * @return string
	 */
	public function handleSortRequest()
	{

		$sort_options = [];
		foreach( $this->columns as $column ) {
			if( $column->getAllowSort() ) {
				if( !$this->default_sort ) {
					$this->default_sort = $column->getName();
				}

				$sort_options[] = $column->getName();
				$sort_options[] = '-'.$column->getName();
			}
		}

		if( !$sort_options ) {
			return '';
		}

		$session = false;
		if( $this->session_namespace ) {
			$session = new Session( $this->session_namespace );
		}


		if( $session ) {
			$this->sort_by = $session->getValue( 'sort', $this->default_sort );
		} else {
			$this->sort_by = $this->default_sort;
		}

		$catcher = $this->getSortCatcher();


		if( ( $sort = $catcher() ) ) {
			if( in_array( $sort, $sort_options ) ) {
				$this->sort_by = $sort;
			}
		}

		if( $session ) {
			$session->setValue( 'sort', $this->sort_by );
		}

		return $this->sort_by;

	}

	/**
	 * @param string $column_name
	 * @param bool   $desc
	 *
	 * @return string
	 */
	public function getSortURL( $column_name, $desc = false )
	{
		$column = $this->getColumn( $column_name );
		if(
			!$column ||
			!$column->getAllowSort()
		) {
			return '';
		}

		$creator = $this->getSortUrlCreator();

		return $creator( $column_name, $desc );
	}

	/**
	 * @param string $name
	 *
	 * @return UI_dataGrid_column
	 */
	public function getColumn( $name )
	{
		return $this->columns[$name];

	}
	/**
	 * @return int
	 */
	public function getPaginatorItemsPerPage()
	{
		if(!$this->paginator_items_per_page) {
			$this->paginator_items_per_page = static::getDefaultItemsPerPage();
		}

		return $this->paginator_items_per_page;
	}

	/**
	 * @param int $paginator_items_per_page
	 */
	public function setPaginatorItemsPerPage( $paginator_items_per_page )
	{
		$this->paginator_items_per_page = $paginator_items_per_page;
	}

	/**
	 * @return callable
	 */
	public function getPageNoCatcher()
	{
		if(!$this->page_no_catcher) {
			/**
			 * @param $default_page_no
			 *
			 * @return int
			 */
			$this->page_no_catcher = function( $default_page_no ) {
				return Http_Request::GET()->getInt( 'page', $default_page_no );
			};

		}

		return $this->page_no_catcher;
	}

	/**
	 * @param callable $page_no_catcher
	 */
	public function setPageNoCatcher( callable $page_no_catcher )
	{
		$this->page_no_catcher = $page_no_catcher;
	}

	/**
	 * @return callable
	 */
	public function getPageUrlCreator()
	{
		if(!$this->page_url_creator) {
			$this->page_url_creator = function( $page_no ) {
				return Http_Request::getCurrentURI( [ 'page' => $page_no ] );
			};
		}

		return $this->page_url_creator;
	}

	/**
	 * @param callable $page_url_creator
	 */
	public function setPageUrlCreator( callable $page_url_creator )
	{
		$this->page_url_creator = $page_url_creator;
	}

	/**
	 * @return callable
	 */
	public function getSortCatcher()
	{
		if(!$this->sort_catcher) {
			$this->sort_catcher = function() {
				return Http_Request::GET()->getString( 'sort' );
			};
		}

		return $this->sort_catcher;
	}

	/**
	 * @param callable $sort_catcher
	 */
	public function setSortCatcher( callable $sort_catcher )
	{
		$this->sort_catcher = $sort_catcher;
	}

	/**
	 * @return callable
	 */
	public function getSortUrlCreator()
	{

		if(!$this->sort_url_creator) {
			$this->sort_url_creator = function($column_name, $desc) {
				if( $desc ) {
					$column_name = '-'.$column_name;
				}

				return Http_Request::getCurrentURI( [ 'sort' => $column_name ] );
			};
		}


		return $this->sort_url_creator;
	}

	/**
	 * @param callable $sort_url_creator
	 */
	public function setSortUrlCreator( callable $sort_url_creator )
	{
		$this->sort_url_creator = $sort_url_creator;
	}



	/**
	 * @param string $session_name
	 */
	public function setIsPersistent( $session_name )
	{
		$this->session_namespace = $session_name;
	}

	/**
	 * @return string
	 */
	public function getSortBy()
	{
		return $this->sort_by;
	}

	/**
	 * @param string $sort_by
	 */
	public function setSortBy( $sort_by )
	{
		$this->sort_by = $sort_by;
	}


	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString()
	{
		return $this->render();
	}


	/**
	 * @return Mvc_View
	 */
	protected function getView()
	{
		$view = UI::getView();
		$view->setVar( 'grid', $this );

		return $view;
	}

	/**
	 * @return string
	 */
	public function getRendererScript()
	{
		if(!$this->renderer_script) {
			$this->renderer_script = static::getDefaultRendererScript();
		}

		return $this->renderer_script;
	}

	/**
	 * @param string $renderer_script
	 */
	public function setRendererScript( $renderer_script )
	{
		$this->renderer_script = $renderer_script;
	}

	/**
	 * @return string
	 */
	public function getRendererScriptHeader()
	{
		if(!$this->renderer_script_header) {
			$this->renderer_script_header = static::getDefaultRendererScriptHeader();
		}

		return $this->renderer_script_header;
	}

	/**
	 * @param string $renderer_script_header
	 */
	public function setRendererScriptHeader( $renderer_script_header )
	{
		$this->renderer_script_header = $renderer_script_header;
	}

	/**
	 * @return string
	 */
	public function getRendererScriptBody()
	{
		if(!$this->renderer_script_body) {
			$this->renderer_script_body = static::getDefaultRendererScriptBody();
		}

		return $this->renderer_script_body;
	}

	/**
	 * @param string $renderer_script_body
	 */
	public function setRendererScriptBody( $renderer_script_body )
	{
		$this->renderer_script_body = $renderer_script_body;
	}

	/**
	 * @return string
	 */
	public function getRendererScriptPaginator()
	{
		if(!$this->renderer_script_paginator) {
			$this->renderer_script_paginator = static::getDefaultRendererScriptPaginator();
		}

		return $this->renderer_script_paginator;
	}

	/**
	 * @param string $renderer_script_paginator
	 */
	public function setRendererScriptPaginator( $renderer_script_paginator )
	{
		$this->renderer_script_paginator = $renderer_script_paginator;
	}

	/**
	 * @return string
	 */
	public function getRendererScriptFooter()
	{
		if(!$this->renderer_script_footer) {
			$this->renderer_script_footer = static::getDefaultRendererScriptFooter();
		}

		return $this->renderer_script_footer;
	}

	/**
	 * @param string $renderer_script_footer
	 */
	public function setRendererScriptFooter( $renderer_script_footer )
	{
		$this->renderer_script_footer = $renderer_script_footer;
	}


	/**
	 * @return string
	 */
	public function render()
	{
		return $this->getView()->render( $this->getRendererScript() );
	}

	/**
	 *
	 * @return string
	 */
	public function renderHeader()
	{
		return $this->getView()->render( $this->getRendererScriptHeader() );
	}

	/**
	 *
	 * @return string
	 */
	public function renderBody()
	{
		return $this->getView()->render( $this->getRendererScriptBody() );
	}

	/**
	 *
	 * @return string
	 */
	public function renderPaginator()
	{
		if( !$this->paginator ) {
			return '';
		}

		return $this->getView()->render( $this->getRendererScriptPaginator() );
	}

	/**
	 *
	 * @return string
	 */
	public function renderFooter()
	{
		return $this->getView()->render( $this->getRendererScriptFooter() );
	}

}