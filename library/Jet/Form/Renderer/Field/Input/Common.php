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
class Form_Renderer_Field_Input_Common extends Form_Renderer_Field_Input
{
	
	/**
	 * @var string
	 */
	protected string $input_type;
	
	
	/**
	 * @param string $input_type
	 */
	public function setInputType( string $input_type ): void
	{
		$this->input_type = $input_type;
	}
	
	
	/**
	 *
	 */
	protected function generateTagAttributes_Standard() : void
	{
		$field = $this->getField();
		
		$this->_tag_attributes['type'] = $this->input_type;
		$this->_tag_attributes['name'] = $field->getTagNameValue();
		$this->_tag_attributes['id'] = $field->getId();
		$this->_tag_attributes['value'] = $field->getValue();
		
		
		if( $field->getPlaceholder() ) {
			$this->_tag_attributes['placeholder'] = Data_Text::htmlSpecialChars( $field->getPlaceholder() );
		}
		if( $field->getIsReadonly() ) {
			$this->_tag_attributes['readonly'] = 'readonly';
		}
		if( $field->getIsRequired() ) {
			$this->_tag_attributes['required'] = 'required';
		}
		if(
			$field instanceof Form_Field_Part_RegExp_Interface &&
			$field->getValidationRegexp()
		) {
			$this->_tag_attributes['pattern'] = $field->getValidationRegexp();
		}
		
	}
	
}