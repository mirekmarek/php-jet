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
 * @category JetApplicationModule
 * @package JetApplicationModule\JetExample\UIElements
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
			 * @var Jet\DataModel_Query $query
			 */
			$query = $data->getQuery();

			$query->setOrderBy( $sort );
		}

		if($this->paginator) {
			if(is_array($data)) {
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

		$view->setVar( 'images_uri', $this->module_instance->getPublicURI().'images/' );

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
	 *
	 */
	public function handlePaginator() {
		//TODO: konfigurovatelne
		//TODO: persistentni
		$this->paginator = new Jet\Data_Paginator(
			Jet\Http_Request::GET()->getInt('p', 1),
			10,
			'?p='.Jet\Data_Paginator::URL_PAGE_NO_KEY
		);

	}

	/**
	 * @return string
	 */
	public function handleSortRequest() {
		//TODO: konfigurovatelne
		//TODO: persistentni
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

		$this->sort_by = $sort_options[0];

		if( $sort = Jet\Http_Request::GET()->getString('sort') ) {
			if( in_array($sort, $sort_options) ) {
				$this->sort_by = $sort;
			}
		}

		return $this->sort_by;

	}


}