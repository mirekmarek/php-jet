<?php
/**
 *
 * @copyright Copyright (c) 2011-2022 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use Iterator;

interface Form_Field_Part_Select_Interface
{
	/**
	 * @return Form_Field_Select_Option_Interface[]
	 */
	public function getSelectOptions(): array;
	
	/**
	 * @param array|Iterator $options
	 */
	public function setSelectOptions( array|Iterator $options ): void;
	
	/**
	 * @param string $option_key
	 *
	 * @return bool
	 */
	public function optionIsSelected( string $option_key ) : bool;
	
}