<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	protected $option = '';

	/**
	 * @var string
	 */
	protected $class = '';

	/**
	 * @var string
	 */
	protected $style = '';

	/**
	 *
	 * @param string $option
	 */
	public function __construct( $option )
	{
		$this->option = $option;
	}


	/**
	 * @param string $css_style
	 */
	public function setSelectOptionCssStyle( $css_style )
	{
		$this->style = $css_style;
	}

	/**
	 * @return string
	 */
	public function getSelectOptionCssStyle()
	{
		return $this->style;
	}


	/**
	 * @param string $css_class
	 */
	public function setSelectOptionCssClass( $css_class )
	{
		$this->class = $css_class;
	}

	/**
	 * @return string
	 */
	public function getSelectOptionCssClass()
	{
		return $this->class;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->option;
	}

}