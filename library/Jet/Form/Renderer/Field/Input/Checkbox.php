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
class Form_Renderer_Field_Input_Checkbox extends Form_Renderer_Field_Input
{
	
	/**
	 *
	 */
	protected function generateTagAttributes_Standard() : void
	{
		$field = $this->getField();
		
		$this->_tag_attributes['type'] = 'checkbox';
		$this->_tag_attributes['name'] = $field->getTagNameValue();
		$this->_tag_attributes['id'] = $field->getId();
		$this->_tag_attributes['value'] = 1;
		
		
		if($field->getIsReadonly()) {
			$this->_tag_attributes['disabled'] = 'disabled';
		}
		
		if($field->getValue()) {
			$this->_tag_attributes['checked'] = 'checked';
		}
		
		if( $field->getIsRequired() ) {
			$this->_tag_attributes['required'] = 'required';
		}
	}
	
}