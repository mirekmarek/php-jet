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
interface Form_Field_Select_Option_Interface
{

	/**
	 * @param string $css_style
	 */
	public function setSelectOptionCssStyle( string $css_style ): void;

	/**
	 * @return string
	 */
	public function getSelectOptionCssStyle(): string;


	/**
	 * @param string $css_class
	 */
	public function setSelectOptionCssClass( string $css_class ): void;

	/**
	 * @return string
	 */
	public function getSelectOptionCssClass(): string;
	
	/**
	 * @return string
	 */
	public function getSelectOptionCss(): string;
	
	/**
	 * @return string
	 */
	public function __toString(): string;
	
}