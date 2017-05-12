<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

use Jet\BaseObject;

/**
 * Class dataGrid_column
 * @package Jet
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
	protected $display_callback;
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
	 * @return callable
	 */
	public function getDisplayCallback()
	{
		return $this->display_callback;
	}

	/**
	 * @param callable $display_callback
	 */
	public function setDisplayCallback( $display_callback )
	{
		$this->display_callback = $display_callback;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( $name )
	{
		$this->name = $name;
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
	 */
	public function setTitle( $title )
	{
		$this->title = $title;
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
	 */
	public function setCssClass( $css_class )
	{
		$this->css_class = $css_class;
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
	 */
	public function setCssStyle( $css_style )
	{
		$this->css_style = $css_style;
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
	 */
	public function setAllowSort( $allow_order_by )
	{
		$this->allow_sort = (bool)$allow_order_by;
	}

	/**
	 * @return bool
	 */
	public function getSortByAsc()
	{
		return $this->getGrid()->getSortBy()==$this->name;
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
	 * @return bool
	 */
	public function getSortByDesc()
	{
		return $this->getGrid()->getSortBy()=='-'.$this->name;
	}

}