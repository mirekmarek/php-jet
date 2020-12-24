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
class UI_dataGrid_column extends BaseObject
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
	 * @var string
	 */
	protected string $css_style = '';
	/**
	 * @var string
	 */
	protected string $css_class = '';
	/**
	 * @var ?UI_dataGrid
	 */
	private ?UI_dataGrid $grid = null;

	/**
	 * @param string $name
	 * @param string $title
	 */
	public function __construct( string $name, string $title )
	{
		$this->name = $name;
		$this->title = $title;
	}

	/**
	 * @return UI_dataGrid
	 */
	public function getGrid() : UI_dataGrid
	{
		return $this->grid;
	}

	/**
	 * @param UI_dataGrid $grid
	 */
	public function setGrid( UI_dataGrid $grid ) : void
	{
		$this->grid = $grid;
	}

	/**
	 * @return string
	 */
	public function getName() : string
	{
		return $this->name;
	}


	/**
	 * @return callable
	 */
	public function getRenderer() : callable
	{
		return $this->renderer;
	}

	/**
	 * @param callable $renderer
	 *
	 * @return $this
	 */
	public function setRenderer( callable $renderer ) : static
	{
		$this->renderer = $renderer;

		return $this;

	}


	/**
	 * @return string
	 */
	public function getTitle() : string
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 *
	 * @return $this
	 */
	public function setTitle( string $title ) : static
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getCssClass() : string
	{
		return $this->css_class;
	}

	/**
	 * @param string $css_class
	 *
	 * @return $this
	 */
	public function setCssClass( string $css_class ) : static
	{
		$this->css_class = $css_class;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getCssStyle() : string
	{
		return $this->css_style;
	}

	/**
	 * @param string $css_style
	 *
	 * @return $this
	 */
	public function setCssStyle( string $css_style ) : static
	{
		$this->css_style = $css_style;

		return $this;

	}

	/**
	 * @return bool
	 */
	public function getAllowSort()
	{
		return $this->allow_sort;
	}

	/**
	 * @param bool $allow_order_by
	 *
	 * @return $this
	 */
	public function setAllowSort( bool $allow_order_by ) : static
	{
		$this->allow_sort = (bool)$allow_order_by;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isSortByAsc() : bool
	{
		return $this->getGrid()->getSortBy()==$this->name;
	}

	/**
	 * @return bool
	 */
	public function isSortByDesc() : bool
	{
		return $this->getGrid()->getSortBy()=='-'.$this->name;
	}

}