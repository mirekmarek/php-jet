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
class UI_dataGrid extends UI_Renderer_Single
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
	 * @var callable
	 */
	protected $sort_url_creator;
	
	
	/**
	 * @var UI_dataGrid_header
	 */
	protected UI_dataGrid_header $header;
	
	/**
	 * @var UI_dataGrid_body
	 */
	protected UI_dataGrid_body $body;
	
	/**
	 * @var UI_dataGrid_footer
	 */
	protected UI_dataGrid_footer $footer;
	
	
	public function __construct()
	{
		$this->view_script = SysConf_Jet_UI_DefaultViews::get('data-grid');
		$this->header = new UI_dataGrid_header( $this );
		$this->body = new UI_dataGrid_body( $this );
		$this->footer = new UI_dataGrid_footer( $this );
	}


	/**
	 * @param string $name
	 * @param string $title
	 *
	 * @return UI_dataGrid_column
	 */
	public function addColumn( string $name, string $title ): UI_dataGrid_column
	{
		$this->columns[$name] = new UI_dataGrid_column( $this, $name, $title );

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
		if(!$this->hasColumn($column_name)) {
			return '';
		}
		
		$column = $this->getColumn( $column_name );
		if(
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
	 * @return bool
	 */
	public function hasColumn( string $name ) : bool
	{
		return isset($this->columns[$name]);
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


	public function header() : UI_dataGrid_header
	{
		return $this->header;
	}
	
	public function body() : UI_dataGrid_body
	{
		return $this->body;
	}
	
	public function footer() : UI_dataGrid_footer
	{
		return $this->footer;
	}
}