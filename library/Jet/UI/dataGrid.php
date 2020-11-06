<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	 * @var UI_dataGrid_column[]
	 */
	protected $columns = [];

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
	 * @var callable
	 */
	protected $sort_url_creator;

	/**
	 * @var string
	 */
	protected $custom_header;

	/**
	 * @var string
	 */
	protected $custom_footer;


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
	 * @return Data_Paginator|null
	 */
	public function getPaginator()
	{
		return $this->paginator;
	}

	/**
	 *
	 * @param Data_Paginator $paginator
	 *
	 * @return $this
	 */
	public function setPaginator( Data_Paginator $paginator )
	{
		$this->paginator = $paginator;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getData()
	{
		if( $this->paginator ) {
			return $this->paginator->getData();
		}

		return $this->data;
	}

	/**
	 * @param array|DataModel_Fetch_Instances $data
	 */
	public function setData( $data )
	{
		if( $this->paginator ) {
			if( is_array( $data ) ) {
				$this->paginator->setData( $data );
			}

			if( $data instanceof DataModel_Fetch_Instances ) {
				$this->paginator->setDataSource( $data );
			}

		} else {
			$this->data = $data;
		}
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
	 * @return callable
	 */
	public function getSortUrlCreator()
	{
		return $this->sort_url_creator;
	}

	/**
	 * @param callable $sort_url_creator
	 *
	 * @return $this
	 */
	public function setSortUrlCreator( callable $sort_url_creator )
	{
		$this->sort_url_creator = $sort_url_creator;

		return $this;
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
	 *
	 * @return $this
	 */
	public function setSortBy( $sort_by )
	{
		$this->sort_by = $sort_by;

		return $this;
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
	 *
	 * @return $this
	 */
	public function setRendererScript( $renderer_script )
	{
		$this->renderer_script = $renderer_script;

		return $this;
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
	 *
	 * @return $this
	 */
	public function setRendererScriptHeader( $renderer_script_header )
	{
		$this->renderer_script_header = $renderer_script_header;

		return $this;
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
	 *
	 * @return $this
	 */
	public function setRendererScriptBody( $renderer_script_body )
	{
		$this->renderer_script_body = $renderer_script_body;

		return $this;
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
	 *
	 * @return $this
	 */
	public function setRendererScriptPaginator( $renderer_script_paginator )
	{
		$this->renderer_script_paginator = $renderer_script_paginator;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getCustomHeader()
	{
		return $this->custom_header;
	}

	/**
	 * @param string $custom_header
	 *
	 * @return $this
	 */
	public function setCustomHeader( $custom_header )
	{
		$this->custom_header = $custom_header;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getCustomFooter()
	{
		return $this->custom_footer;
	}

	/**
	 * @param string $custom_footer
	 *
	 * @return $this
	 */
	public function setCustomFooter( $custom_footer )
	{
		$this->custom_footer = $custom_footer;

		return $this;
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

}