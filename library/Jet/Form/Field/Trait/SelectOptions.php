<?php
/**
 *
 * @copyright Copyright (c) 2011-2022 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use Iterator;

/**
 *
 * Options for Select, MultiSelect, RadioButtons and so on ...
 *
 */
trait Form_Field_Trait_SelectOptions
{
	
	/**
	 * @var array
	 */
	protected array $select_options = [];
	
	/**
	 * @return Form_Field_Select_Option_Interface[]
	 */
	public function getSelectOptions(): array
	{
		return $this->select_options;
	}
	
	/**
	 * @param array|Iterator $options
	 */
	public function setSelectOptions( array|Iterator $options ): void
	{
		$_o = $options;
		$options = [];
		
		foreach( $_o as $k => $v ) {
			if(!is_object($v)) {
				$v = new Form_Field_Select_Option($v);
			} else {
				if(!($v instanceof Form_Field_Select_Option_Interface)) {
					$v = new Form_Field_Select_Option((string)$v);
				}
			}
			
			$options[$k] = $v;
		}
		
		$this->select_options = $options;
	}
	
}