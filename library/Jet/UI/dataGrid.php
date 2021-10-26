<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class UI_dataGrid extends BaseObject
{
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
	protected string $view_script = '';
	/**
	 * @var string
	 */
	protected string $view_script_header = '';
	/**
	 * @var string
	 */
	protected string $view_script_body = '';
	/**
	 * @var string
	 */
	protected string $view_script_paginator = '';

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
	public function getViewScript(): string
	{
		if( !$this->view_script ) {
			$this->view_script = SysConf_Jet_UI_DefaultViews::get('data-grid');
		}

		return $this->view_script;
	}

	/**
	 * @param string $view_script
	 *
	 * @return $this
	 */
	public function setViewScript( string $view_script ): static
	{
		$this->view_script = $view_script;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getViewScriptHeader(): string
	{
		if( !$this->view_script_header ) {
			$this->view_script_header = SysConf_Jet_UI_DefaultViews::get('data-grid', 'header');
		}

		return $this->view_script_header;
	}

	/**
	 * @param string $view_script_header
	 *
	 * @return $this
	 */
	public function setViewScriptHeader( string $view_script_header ): static
	{
		$this->view_script_header = $view_script_header;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getViewScriptBody(): string
	{
		if( !$this->view_script_body ) {
			$this->view_script_body = SysConf_Jet_UI_DefaultViews::get('data-grid', 'body');
		}

		return $this->view_script_body;
	}

	/**
	 * @param string $view_script_body
	 *
	 * @return $this
	 */
	public function setViewScriptBody( string $view_script_body ): static
	{
		$this->view_script_body = $view_script_body;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getViewScriptPaginator(): string
	{
		if( !$this->view_script_paginator ) {
			$this->view_script_paginator = SysConf_Jet_UI_DefaultViews::get('data-grid', 'paginator');
		}

		return $this->view_script_paginator;
	}

	/**
	 * @param string $view_script_paginator
	 *
	 * @return $this
	 */
	public function setViewScriptPaginator( string $view_script_paginator ): static
	{
		$this->view_script_paginator = $view_script_paginator;

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
		return $this->getView()->render( $this->getViewScript() );
	}

	/**
	 *
	 * @return string
	 */
	public function renderHeader(): string
	{
		return $this->getView()->render( $this->getViewScriptHeader() );
	}

	/**
	 *
	 * @return string
	 */
	public function renderBody(): string
	{
		return $this->getView()->render( $this->getViewScriptBody() );
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

		return $this->getView()->render( $this->getViewScriptPaginator() );
	}

}