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
namespace JetApplicationModule\JetExample\UIElements;
use Jet;

class DataGrid extends Jet\Object {

	/**
	 * @var Main
	 */
	protected $module_instance;

	/**
	 * @var DataGrid_Column[]
	 */
	protected $columns = array();

	/**
	 * @var Jet\Data_Paginator
	 */
	protected $paginator;

	/**
	 * @var array
	 */
	protected $data = array();

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
	protected $sort_get_parameter = 'sort';
	/**
	 * @var string
	 */
	protected $paginator_get_parameter = 'p';

	/**
	 * @var int
	 */
	protected $paginator_items_per_page = 10;

	/**
	 * @var string
	 */
	protected $root_URI = '';

	/**
	 * @var string
	 */
	protected $session_name = '';


	/**
	 * @param Main $module_instance
	 */
	public function __construct( Main $module_instance ) {
		$this->module_instance = $module_instance;
	}

	/**
	 * @param string $name
	 * @param string $title
	 *
	 * @return DataGrid_Column
	 */
	public function addColumn( $name, $title ) {
		$this->columns[$name] = new DataGrid_Column( $name, $title );

		return $this->columns[$name];
	}

	/**
	 * @param string $name
	 *
	 * @return DataGrid_Column
	 */
	public function getColumn( $name ) {
		return $this->columns[$name];

	}

	/**
	 *
	 * @return DataGrid_Column[]
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
	 * @param Jet\Data_Paginator $paginator
	 */
	public function setPaginator( Jet\Data_Paginator $paginator) {
		$this->paginator = $paginator;
	}

	/**
	 * @return Jet\Data_Paginator|null
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
				$data instanceof Jet\DataModel_Fetch_Data_Abstract ||
				$data instanceof Jet\DataModel_Fetch_Object_Abstract
			) &&
			($sort = $this->handleSortRequest())
		) {
			/**
			 * @var Jet\DataModel_Fetch_Data_Abstract $data
			 * @var Jet\DataModel_Query $query
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
				$data instanceof Jet\DataModel_Fetch_Data_Abstract ||
				$data instanceof Jet\DataModel_Fetch_Object_Abstract
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
				$session = new Jet\Session( $this->session_name );
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
	 *
	 * @return string
	 */
	public function renderHeader() {
		$view = $this->module_instance->getViewInstance();
		$view->setVar( 'grid', $this );

		$view->setVar( 'images_uri', $this->module_instance->getModuleManifest()->getPublicURI().'images/' );

		return $view->render('DataGrid/header');

	}

	/**
	 *
	 * @return string
	 */
	public function renderBody() {
		$view = $this->module_instance->getViewInstance();
		$view->setVar( 'grid', $this );

		return $view->render('DataGrid/body');
	}

	/**
	 *
	 * @return string
	 */
	public function renderFooter() {
		$view = $this->module_instance->getViewInstance();
		$view->setVar( 'grid', $this );

		return $view->render('DataGrid/footer');
	}

	/**
	 *
	 * @return string
	 */
	public function renderPaginator() {
		if(!$this->paginator) {
			return '';
		}

		$view = $this->module_instance->getViewInstance();
		$view->setVar( 'grid', $this );

		return $view->render('DataGrid/paginator');
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

		if($this->root_URI) {
			if(strpos($this->root_URI, '?')===false) {
				return $this->root_URI.'?'.$this->sort_get_parameter.'='.rawurlencode($column_name);
			} else {
				return $this->root_URI.'&amp;'.$this->sort_get_parameter.'='.rawurlencode($column_name);
			}
		} else {
			return '?'.$this->sort_get_parameter.'='.rawurlencode($column_name);
		}
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

		if($this->root_URI) {
			if(strpos($this->root_URI, '?')===false) {
				$URL_template = $this->root_URI.'?'.$this->paginator_get_parameter.'='.Jet\Data_Paginator::URL_PAGE_NO_KEY;
			} else {
				$URL_template = $this->root_URI.'&amp;'.$this->paginator_get_parameter.'='.Jet\Data_Paginator::URL_PAGE_NO_KEY;
			}
		} else {
			$URL_template = '?'.$this->paginator_get_parameter.'='.Jet\Data_Paginator::URL_PAGE_NO_KEY;
		}

		if($this->session_name) {
			$session = new Jet\Session( $this->session_name );
			$default_page_no = $session->getValue('page_no', 1);
		} else {
			$default_page_no = 1;

		}



		$this->paginator = new Jet\Data_Paginator(
			Jet\Http_Request::GET()->getInt($this->paginator_get_parameter, $default_page_no),
			$this->paginator_items_per_page,
			$URL_template
		);

	}


	/**
	 * @return string
	 */
	public function handleSortRequest() {

		$sort_options = array();
		foreach( $this->columns as $column ) {
			if($column->getAllowSort()) {
				$sort_options[] = $column->getName();
				$sort_options[] = '-'.$column->getName();
			}
		}

		if(!$sort_options) {
			return '';
		}

		$session = false;
		if($this->session_name) {
			$session = new Jet\Session( $this->session_name );
		}


		if($session) {
			$this->sort_by = $session->getValue('sort', $sort_options[0]);
		} else {
			$this->sort_by = $sort_options[0];
		}

		if( $sort = Jet\Http_Request::GET()->getString($this->sort_get_parameter) ) {
			if( in_array($sort, $sort_options) ) {
				$this->sort_by = $sort;
			}
		}

		if($session) {
			$session->setValue('sort', $this->sort_by);
		}

		return $this->sort_by;

	}


}