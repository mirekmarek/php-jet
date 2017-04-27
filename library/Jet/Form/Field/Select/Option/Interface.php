<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Interface Form_Field_Select_Option_Interface
 * @package Jet
 */
interface Form_Field_Select_Option_Interface {

	/**
	 * @param string $css_style
	 */
	public function setSelectOptionCssStyle( $css_style );

	/**
	 * @return string
	 */
	public function getSelectOptionCssStyle();


	/**
	 * @param string $css_class
	 */
	public function setSelectOptionCssClass( $css_class );

	/**
	 * @return string
	 */
	public function getSelectOptionCssClass();

}