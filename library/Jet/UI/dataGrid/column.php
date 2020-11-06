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
	protected $name = '';
	/**
	 * @var string
	 */
	protected $title = '';
	/**
	 * @var callable
	 */
	protected $renderer;
	/**
	 * @var bool
	 */
	protected $allow_sort = true;
	/**
	 * @var string
	 */
	protected $css_style = '';
	/**
	 * @var string
	 */
	protected $css_class = '';
	/**
	 * @var UI_dataGrid
	 */
	private $grid;

	/**
	 * @param string $name
	 * @param string $title
	 */
	public function __construct( $name, $title )
	{
		$this->name = $name;
		$this->title = $title;
	}

	/**
	 * @return UI_dataGrid
	 */
	public function getGrid()
	{
		return $this->grid;
	}

	/**
	 * @param UI_dataGrid $grid
	 */
	public function setGrid( UI_dataGrid $grid )
	{
		$this->grid = $grid;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @return callable
	 */
	public function getRenderer()
	{
		return $this->renderer;
	}

	/**
	 * @param callable $renderer
	 *
	 * @return $this
	 */
	public function setRenderer( callable $renderer )
	{
		$this->renderer = $renderer;

		return $this;

	}


	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 *
	 * @return $this
	 */
	public function setTitle( $title )
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getCssClass()
	{
		return $this->css_class;
	}

	/**
	 * @param string $css_class
	 *
	 * @return $this
	 */
	public function setCssClass( $css_class )
	{
		$this->css_class = $css_class;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getCssStyle()
	{
		return $this->css_style;
	}

	/**
	 * @param string $css_style
	 *
	 * @return $this
	 */
	public function setCssStyle( $css_style )
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
	public function setAllowSort( $allow_order_by )
	{
		$this->allow_sort = (bool)$allow_order_by;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isSortByAsc()
	{
		return $this->getGrid()->getSortBy()==$this->name;
	}

	/**
	 * @return bool
	 */
	public function isSortByDesc()
	{
		return $this->getGrid()->getSortBy()=='-'.$this->name;
	}

}