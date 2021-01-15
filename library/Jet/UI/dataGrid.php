<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	protected static string $default_renderer_script = 'dataGrid';
	/**
	 * @var string
	 */
	protected static string $default_renderer_script_header = 'dataGrid/header';
	/**
	 * @var string
	 */
	protected static string $default_renderer_script_body = 'dataGrid/body';
	/**
	 * @var string
	 */
	protected static string $default_renderer_script_paginator = 'dataGrid/paginator';


	/**
	 * @var UI_dataGrid_column[]
	 */
	protected array $columns = [];

	/**
	 * @var ?Data_Paginator
	 */
	protected ?Data_Paginator $paginator = null;

	/**
	 * @var array
	 */
	protected array $data = [];

	/**
	 * @var string
	 */
	protected string $sort_by = '';


	/**
	 * @var string
	 */
	protected string $renderer_script = '';
	/**
	 * @var string
	 */
	protected string $renderer_script_header = '';
	/**
	 * @var string
	 */
	protected string $renderer_script_body = '';
	/**
	 * @var string
	 */
	protected string $renderer_script_paginator = '';

	/**
	 * @var callable
	 */
	protected $sort_url_creator;

	/**
	 * @var string
	 */
	protected string $custom_header = '';

	/**
	 * @var string
	 */
	protected string $custom_footer = '';


	/**
	 * @return string
	 */
	public static function getDefaultRendererScript(): string
	{
		return static::$default_renderer_script;
	}

	/**
	 * @param string $default_renderer_script
	 */
	public static function setDefaultRendererScript( string $default_renderer_script ): void
	{
		static::$default_renderer_script = $default_renderer_script;
	}

	/**
	 * @return string
	 */
	public static function getDefaultRendererScriptHeader(): string
	{
		return static::$default_renderer_script_header;
	}

	/**
	 * @param string $default_renderer_script_header
	 */
	public static function setDefaultRendererScriptHeader( string $default_renderer_script_header ): void
	{
		static::$default_renderer_script_header = $default_renderer_script_header;
	}

	/**
	 * @return string
	 */
	public static function getDefaultRendererScriptBody(): string
	{
		return static::$default_renderer_script_body;
	}

	/**
	 * @param string $default_renderer_script_body
	 */
	public static function setDefaultRendererScriptBody( string $default_renderer_script_body ): void
	{
		static::$default_renderer_script_body = $default_renderer_script_body;
	}

	/**
	 * @return string
	 */
	public static function getDefaultRendererScriptPaginator(): string
	{
		return static::$default_renderer_script_paginator;
	}

	/**
	 * @param string $default_renderer_script_paginator
	 */
	public static function setDefaultRendererScriptPaginator( string $default_renderer_script_paginator ): void
	{
		static::$default_renderer_script_paginator = $default_renderer_script_paginator;
	}

	/**
	 * @param string $name
	 * @param string $title
	 *
	 * @return UI_dataGrid_column
	 */
	public function addColumn( string $name, string $title ): UI_dataGrid_column
	{
		$this->columns[$name] = new UI_dataGrid_column( $name, $title );
		$this->columns[$name]->setGrid( $this );

		return $this->columns[$name];
	}

	/**
	 *
	 * @return UI_dataGrid_column[]
	 */
	public function getColumns(): array
	{
		return $this->columns;
	}


	/**
	 * @return Data_Paginator|null
	 */
	public function getPaginator(): Data_Paginator|null
	{
		return $this->paginator;
	}

	/**
	 *
	 * @param Data_Paginator $paginator
	 *
	 * @return $this
	 */
	public function setPaginator( Data_Paginator $paginator ): static
	{
		$this->paginator = $paginator;

		return $this;
	}

	/**
	 * @return iterable
	 */
	public function getData(): iterable
	{
		if( $this->paginator ) {
			return $this->paginator->getData();
		}

		return $this->data;
	}

	/**
	 * @param array|DataModel_Fetch_Instances $data
	 */
	public function setData( DataModel_Fetch_Instances|array $data ): void
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
	 * @param bool $desc
	 *
	 * @return string
	 */
	public function getSortURL( string $column_name, bool $desc = false ): string
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
	public function getColumn( string $name ): UI_dataGrid_column
	{
		return $this->columns[$name];

	}


	/**
	 * @return callable
	 */
	public function getSortUrlCreator(): callable
	{
		return $this->sort_url_creator;
	}

	/**
	 * @param callable $sort_url_creator
	 *
	 * @return $this
	 */
	public function setSortUrlCreator( callable $sort_url_creator ): static
	{
		$this->sort_url_creator = $sort_url_creator;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getSortBy(): string
	{
		return $this->sort_by;
	}

	/**
	 * @param string $sort_by
	 *
	 * @return $this
	 */
	public function setSortBy( string $sort_by ): static
	{
		$this->sort_by = $sort_by;

		return $this;
	}


	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		return $this->render();
	}


	/**
	 * @return Mvc_View
	 */
	protected function getView(): Mvc_View
	{
		$view = UI::getView();
		$view->setVar( 'grid', $this );

		return $view;
	}

	/**
	 * @return string
	 */
	public function getRendererScript(): string
	{
		if( !$this->renderer_script ) {
			$this->renderer_script = static::getDefaultRendererScript();
		}

		return $this->renderer_script;
	}

	/**
	 * @param string $renderer_script
	 *
	 * @return $this
	 */
	public function setRendererScript( string $renderer_script ): static
	{
		$this->renderer_script = $renderer_script;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getRendererScriptHeader(): string
	{
		if( !$this->renderer_script_header ) {
			$this->renderer_script_header = static::getDefaultRendererScriptHeader();
		}

		return $this->renderer_script_header;
	}

	/**
	 * @param string $renderer_script_header
	 *
	 * @return $this
	 */
	public function setRendererScriptHeader( string $renderer_script_header ): static
	{
		$this->renderer_script_header = $renderer_script_header;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getRendererScriptBody(): string
	{
		if( !$this->renderer_script_body ) {
			$this->renderer_script_body = static::getDefaultRendererScriptBody();
		}

		return $this->renderer_script_body;
	}

	/**
	 * @param string $renderer_script_body
	 *
	 * @return $this
	 */
	public function setRendererScriptBody( string $renderer_script_body ): static
	{
		$this->renderer_script_body = $renderer_script_body;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getRendererScriptPaginator(): string
	{
		if( !$this->renderer_script_paginator ) {
			$this->renderer_script_paginator = static::getDefaultRendererScriptPaginator();
		}

		return $this->renderer_script_paginator;
	}

	/**
	 * @param string $renderer_script_paginator
	 *
	 * @return $this
	 */
	public function setRendererScriptPaginator( string $renderer_script_paginator ): static
	{
		$this->renderer_script_paginator = $renderer_script_paginator;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getCustomHeader(): string
	{
		return $this->custom_header;
	}

	/**
	 * @param string $custom_header
	 *
	 * @return $this
	 */
	public function setCustomHeader( string $custom_header ): static
	{
		$this->custom_header = $custom_header;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getCustomFooter(): string
	{
		return $this->custom_footer;
	}

	/**
	 * @param string $custom_footer
	 *
	 * @return $this
	 */
	public function setCustomFooter( string $custom_footer ): static
	{
		$this->custom_footer = $custom_footer;

		return $this;
	}


	/**
	 * @return string
	 */
	public function render(): string
	{
		return $this->getView()->render( $this->getRendererScript() );
	}

	/**
	 *
	 * @return string
	 */
	public function renderHeader(): string
	{
		return $this->getView()->render( $this->getRendererScriptHeader() );
	}

	/**
	 *
	 * @return string
	 */
	public function renderBody(): string
	{
		return $this->getView()->render( $this->getRendererScriptBody() );
	}

	/**
	 *
	 * @return string
	 */
	public function renderPaginator(): string
	{
		if( !$this->paginator ) {
			return '';
		}

		return $this->getView()->render( $this->getRendererScriptPaginator() );
	}

}