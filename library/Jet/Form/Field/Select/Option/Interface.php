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
interface Form_Field_Select_Option_Interface
{

	/**
	 * @param string $css_style
	 */
	public function setSelectOptionCssStyle( string $css_style ) : void;

	/**
	 * @return string
	 */
	public function getSelectOptionCssStyle() : string;


	/**
	 * @param string $css_class
	 */
	public function setSelectOptionCssClass( string $css_class ) :void;

	/**
	 * @return string
	 */
	public function getSelectOptionCssClass() : string;

}