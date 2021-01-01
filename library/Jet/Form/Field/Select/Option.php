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
class Form_Field_Select_Option extends BaseObject
{

	/**
	 * @var string
	 */
	protected string $option = '';

	/**
	 * @var string
	 */
	protected string $class = '';

	/**
	 * @var string
	 */
	protected string $style = '';

	/**
	 *
	 * @param string $option
	 */
	public function __construct( string $option )
	{
		$this->option = $option;
	}


	/**
	 * @param string $css_style
	 */
	public function setSelectOptionCssStyle( string $css_style )
	{
		$this->style = $css_style;
	}

	/**
	 * @return string
	 */
	public function getSelectOptionCssStyle() : string
	{
		return $this->style;
	}


	/**
	 * @param string $css_class
	 */
	public function setSelectOptionCssClass( string $css_class )
	{
		$this->class = $css_class;
	}

	/**
	 * @return string
	 */
	public function getSelectOptionCssClass() : string
	{
		return $this->class;
	}

	/**
	 * @return string
	 */
	public function __toString() : string
	{
		return $this->option;
	}

}