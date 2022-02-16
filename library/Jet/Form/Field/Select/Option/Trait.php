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
trait Form_Field_Select_Option_Trait
{
	
	/**
	 * @var string
	 */
	protected string $__select_option_css_class = '';
	
	/**
	 * @var string
	 */
	protected string $__select_option_css_style = '';
	
	
	/**
	 * @param string $css_style
	 */
	public function setSelectOptionCssStyle( string $css_style ) : void
	{
		$this->__select_option_css_style = $css_style;
	}
	
	/**
	 * @return string
	 */
	public function getSelectOptionCssStyle(): string
	{
		return $this->__select_option_css_style;
	}
	
	
	/**
	 * @param string $css_class
	 */
	public function setSelectOptionCssClass( string $css_class ) : void
	{
		$this->__select_option_css_class = $css_class;
	}
	
	/**
	 * @return string
	 */
	public function getSelectOptionCssClass(): string
	{
		return $this->__select_option_css_class;
	}
	
	
	/**
	 * @return string
	 */
	public function getSelectOptionCss(): string
	{
		$css = '';
		
		if( ($class = $this->getSelectOptionCssClass()) ) {
			$css .= ' class="' . $class . '"';
		}
		if( ($style = $this->getSelectOptionCssStyle()) ) {
			$css .= ' style="' . $style . '"';
		}
		
		return $css;
	}
	
}