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
class Form_Renderer_Field_Input_Textarea extends Form_Renderer_Field_Input
{
	/**
	 *
	 */
	protected function generateTagAttributes_Standard() : void
	{
		$field = $this->getField();
		
		
		$this->_tag_attributes['name'] = $field->getTagNameValue();
		$this->_tag_attributes['id'] = $field->getId();
		
		if( $field->getPlaceholder() ) {
			$this->_tag_attributes['placeholder'] = Data_Text::htmlSpecialChars( $field->getPlaceholder() );
		}
		if( $field->getIsReadonly() ) {
			$this->_tag_attributes['readonly'] = 'readonly';
		}
		if( $field->getIsRequired() ) {
			$this->_tag_attributes['required'] = 'required';
		}
	}
	
	
}