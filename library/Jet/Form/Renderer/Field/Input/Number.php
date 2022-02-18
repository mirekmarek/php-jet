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
class Form_Renderer_Field_Input_Number extends Form_Renderer_Field_Input
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
		
		/**
		 * @var Form_Field|Form_Field_Part_NumberRangeFloat_Interface|Form_Field_Part_NumberRangeInt_Interface $field
		 */
		
		if( $field->getPlaceholder() ) {
			$this->_tag_attributes['placeholder'] = Data_Text::htmlSpecialChars( $field->getPlaceholder() );
		}
		if( $field->getIsReadonly() ) {
			$this->_tag_attributes['readonly'] = 'readonly';
		}
		if( $field->getIsRequired() ) {
			$this->_tag_attributes['required'] = 'required';
		}
		
		if( $field->getMinValue() !== null ) {
			$this->_tag_attributes['min'] = $field->getMinValue();
		}
		
		if( $field->getMaxValue() !== null ) {
			$this->_tag_attributes['max'] = $field->getMaxValue();
		}
		
		if($field->getStep()!==null) {
			$this->_tag_attributes['step'] = $field->getStep();
		}
		
		if(
			$field instanceof Form_Field_Part_NumberRangeFloat_Interface &&
			$field->getPlaces()!==null
		) {
			$this->_tag_attributes['places'] = $field->getPlaces();
		}
		
	}
	
}