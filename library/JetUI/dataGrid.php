<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetUI;
use Jet\Mvc_View;
use Jet\BaseObject;
use Jet\Data_Paginator;
use Jet\DataModel_Fetch_Data_Abstract;
use Jet\DataModel_Fetch_Object_Abstract;
use Jet\DataModel_Query;
use Jet\Session;
use Jet\Http_Request;

class dataGrid extends BaseObject {
	const SORT_GET_PARAMETER = 'sort';
	const PAGINATOR_GET_PARAMETER = 'p';
	const DEFAULT_PAGINATOR_ITEMS_PER_PAGE = 20;

	/**
	 * @var dataGrid_column[]
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
	 * @var string
	 */
	protected $sort_get_parameter = dataGrid::SORT_GET_PARAMETER;
	/**
	 * @var string
	 */
	protected $paginator_get_parameter = dataGrid::PAGINATOR_GET_PARAMETER;

	/**
	 * @var int
	 */
	protected $paginator_items_per_page = dataGrid::DEFAULT_PAGINATOR_ITEMS_PER_PAGE;

	/**
	 * @var string
	 */
	protected $root_URI = '';

	/**
	 * @var string
	 */
	protected $session_name = '';


	/**
	 *
	 */
	public function __construct() {
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
	public function setDefaultSort($default_sort)
	{
		$this->default_sort = $default_sort;
	}



	/**
	 * @param string $name
	 * @param string $title
	 *
	 * @return dataGrid_column
	 */
	public function addColumn( $name, $title ) {
		$this->columns[$name] = new dataGrid_column( $name, $title );
		$this->columns[$name]->setGrid( $this );

		return $this->columns[$name];
	}

	/**
	 * @param string $name
	 *
	 * @return dataGrid_column
	 */
	public function getColumn( $name ) {
		return $this->columns[$name];

	}

	/**
	 *
	 * @return dataGrid_column[]
	 */
	public function getColumns() {
		return $this->columns;
	}

	/**
	 * @param boolean $allow_paginator
	 */
	public function setAllowPaginator($allow_paginator) {
		$this->allow_paginator = $allow_paginator;
	}

	/**
	 * @return boolean
	 */
	public function getAllowPaginator() {
		return $this->allow_paginator;
	}



	/**
	 *
	 * @param Data_Paginator $paginator
	 */
	public function setPaginator( Data_Paginator $paginator) {
		$this->paginator = $paginator;
	}

	/**
	 * @return Data_Paginator|null
	 */
	public function getPaginator() {
		return $this->paginator;
	}

	/**
	 * @param array $data
	 */
	public function setData( $data ) {

		if(
			$this->allow_paginator &&
			!$this->paginator
		) {
			$this->handlePaginator();
		}

		if(
			(
				$data instanceof DataModel_Fetch_Data_Abstract ||
				$data instanceof DataModel_Fetch_Object_Abstract
			) &&
			($sort = $this->handleSortRequest())
		) {
			/**
			 * @var DataModel_Fetch_Data_Abstract $data
			 * @var DataModel_Query $query
			 */
			$query = $data->getQuery();

			$query->setOrderBy( $sort );
		}

		if($this->paginator) {
			if(is_array($data)) {
				/**
				 * @var array $data
				 */
				$this->paginator->setData( $data );
			}

			if(
				$data instanceof DataModel_Fetch_Data_Abstract ||
				$data instanceof DataModel_Fetch_Object_Abstract
			){
				$this->paginator->setDataSource( $data );
			}

		} else {
			$this->data = $data;
		}


	}

	/**
	 * @return array
	 */
	public function getData() {
		if($this->paginator) {

			if($this->session_name) {
				$session = new Session( $this->session_name );
				$session->setValue('page_no', $this->paginator->getCurrentPageNo());
			}

			return $this->paginator->getData();
		}

		return $this->data;
	}



	/**
	 * @return string
	 */
	public function render() {

		return
			$this->renderHeader()
			.$this->renderBody()
			.$this->renderPaginator()
			.$this->renderFooter();

	}

	/**
	 * @return Mvc_View
	 */
	protected function getView() {
		$view = new Mvc_View( JET_APPLICATION_PATH.'views/' );
		$view->setVar( 'grid', $this );
		$view->setVar( 'images_uri', JET_PUBLIC_URI.'images/' );

		return $view;
	}

	/**
	 *
	 * @return string
	 */
	public function renderHeader() {
		$view = $this->getView();

		return $view->render('dataGrid/header');

	}

	/**
	 *
	 * @return string
	 */
	public function renderBody() {
		$view = $this->getView();

		return $view->render('dataGrid/body');
	}

	/**
	 *
	 * @return string
	 */
	public function renderFooter() {
		$view = $this->getView();

		return $view->render('dataGrid/footer');
	}

	/**
	 *
	 * @return string
	 */
	public function renderPaginator() {
		if(!$this->paginator) {
			return '';
		}

		$view = $this->getView();

		return $view->render('dataGrid/paginator');
	}


	/**
	 * @param string $sort_get_parameter
	 */
	public function setSortGetParameter($sort_get_parameter) {
		$this->sort_get_parameter = $sort_get_parameter;
	}

	/**
	 * @return string
	 */
	public function getSortGetParameter() {
		return $this->sort_get_parameter;
	}

	/**
	 * @param string $column_name
	 * @param bool $desc
	 *
	 * @return string
	 */
	public function getSortURL( $column_name, $desc=false ) {
		$column = $this->getColumn($column_name);
		if(
			!$column ||
			!$column->getAllowSort())
		{
			return '';
		}

		if($desc) {
			$column_name = '-'.$column_name;
		}

		return Http_Request::getCurrentURI(['sort'=>$column_name]);
	}

	/**
	 * @param $paginator_get_parameter
	 */
	public function setPaginatorGetParameter($paginator_get_parameter) {
		$this->paginator_get_parameter = $paginator_get_parameter;
	}

	/**
	 * @return string
	 */
	public function getPaginatorGetParameter() {
		return $this->paginator_get_parameter;
	}

	/**
	 * @param $root_URI
	 */
	public function setRootURI($root_URI) {
		$this->root_URI = $root_URI;
	}

	/**
	 * @return string
	 */
	public function getRootURI() {
		return $this->root_URI;
	}

	/**
	 * @param int $paginator_items_per_page
	 */
	public function setPaginatorItemsPerPage($paginator_items_per_page) {
		$this->paginator_items_per_page = $paginator_items_per_page;
	}

	/**
	 * @return int
	 */
	public function getPaginatorItemsPerPage() {
		return $this->paginator_items_per_page;
	}

	/**
	 * @param string $session_name
	 */
	public function setIsPersistent( $session_name ) {
		$this->session_name = $session_name;
	}

	/**
	 *
	 */
	public function handlePaginator() {

		if($this->session_name) {
			$session = new Session( $this->session_name );
			$default_page_no = $session->getValue('page_no', 1);
		} else {
			$default_page_no = 1;

		}

		$URL_template = Http_Request::getCurrentURI([$this->paginator_get_parameter=>'PAGE_NO']);
		$URL_template = str_replace('PAGE_NO', Data_Paginator::URL_PAGE_NO_KEY, $URL_template);


		$this->paginator = new Data_Paginator(
			Http_Request::GET()->getInt($this->paginator_get_parameter, $default_page_no),
			$this->paginator_items_per_page,
			$URL_template
		);

	}


	/**
	 * @return string
	 */
	public function handleSortRequest() {

		$sort_options = [];
		foreach( $this->columns as $column ) {
			if($column->getAllowSort()) {
				if(!$this->default_sort) {
					$this->default_sort = $column->getName();
				}

				$sort_options[] = $column->getName();
				$sort_options[] = '-'.$column->getName();
			}
		}

		if(!$sort_options) {
			return '';
		}

		$session = false;
		if($this->session_name) {
			$session = new Session( $this->session_name );
		}


		if($session) {
			$this->sort_by = $session->getValue('sort', $this->default_sort);
		} else {
			$this->sort_by = $this->default_sort;
		}

		if( ($sort = Http_Request::GET()->getString($this->sort_get_parameter)) ) {
			if( in_array($sort, $sort_options) ) {
				$this->sort_by = $sort;
			}
		}

		if($session) {
			$session->setValue('sort', $this->sort_by);
		}

		return $this->sort_by;

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
	public function setSortBy($sort_by)
	{
		$this->sort_by = $sort_by;
	}


}