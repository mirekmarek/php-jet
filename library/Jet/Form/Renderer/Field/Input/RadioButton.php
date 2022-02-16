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
class Form_Renderer_Field_Input_RadioButton extends Form_Renderer_Field_Input
{
	
	protected int $curr_option_index = 0;
	protected string $curr_option_key;
	protected bool $curr_option_is_selected;
	
	public function setCurrentOption( string $option_key, Form_Field_Select_Option $option ) : void
	{
		$this->curr_option_index++;
		$this->curr_option_key = $option_key;
		$this->curr_option_is_selected = $this->field->optionIsSelected($option_key);
	}
	
	/**
	 *
	 */
	protected function generateTagAttributes_Standard() : void
	{
		$field = $this->getField();
		
		$this->_tag_attributes['type'] = 'radio';
		$this->_tag_attributes['name'] = $field->getTagNameValue();
		$this->_tag_attributes['id'] = $field->getId().'__'.$this->curr_option_index;
		$this->_tag_attributes['value'] = Data_Text::htmlSpecialChars($this->curr_option_key);
		if($this->curr_option_is_selected) {
			$this->_tag_attributes['checked'] = 'checked';
		}
		
		
		if($field->getIsReadonly()) {
			$this->_tag_attributes['disabled'] = 'disabled';
		}
		
		if( $field->getIsRequired() ) {
			$this->_tag_attributes['required'] = 'required';
		}
	}
	
	/**
	 * @return string
	 */
	public function getFieldId() : string
	{
		return $this->_tag_attributes['id'];
	}
	
}