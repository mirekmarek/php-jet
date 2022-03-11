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
class UI_dataGrid_column extends UI_Renderer_Single
{

	/**
	 * @var string
	 */
	protected string $name = '';
	/**
	 * @var string
	 */
	protected string $title = '';
	
	/**
	 * @var callable
	 */
	protected $renderer;
	
	/**
	 * @var bool
	 */
	protected bool $allow_sort = true;
	
	/**
	 * @var UI_dataGrid
	 */
	private UI_dataGrid $grid;
	
	/**
	 * @param UI_dataGrid $grid
	 * @param string $name
	 * @param string $title
	 */
	public function __construct( UI_dataGrid $grid, string $name, string $title )
	{
		$this->grid = $grid;
		$this->name = $name;
		$this->title = $title;
		$this->view_script = SysConf_Jet_UI_DefaultViews::get('data-grid', 'header/column');
	}

	/**
	 * @return UI_dataGrid
	 */
	public function getGrid(): UI_dataGrid
	{
		return $this->grid;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}


	/**
	 * @return callable
	 */
	public function getRenderer(): callable
	{
		return $this->renderer;
	}

	/**
	 * @param callable $renderer
	 *
	 * @return $this
	 */
	public function setRenderer( callable $renderer ): static
	{
		$this->renderer = $renderer;

		return $this;

	}


	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 *
	 * @return $this
	 */
	public function setTitle( string $title ): static
	{
		$this->title = $title;

		return $this;
	}


	/**
	 * @return bool
	 */
	public function getAllowSort() : bool
	{
		return $this->allow_sort;
	}

	/**
	 * @param bool $allow_order_by
	 *
	 * @return $this
	 */
	public function setAllowSort( bool $allow_order_by ): static
	{
		$this->allow_sort = $allow_order_by;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isSortByAsc(): bool
	{
		return $this->getGrid()->getSortBy() == $this->name;
	}

	/**
	 * @return bool
	 */
	public function isSortByDesc(): bool
	{
		return $this->getGrid()->getSortBy() == '-' . $this->name;
	}

}