<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Form_Renderer_Field_Input extends Form_Renderer_Single
{
	
	/**
	 * @var string
	 */
	protected string $field_type = '';
	
	/**
	 *
	 * @param Form_Field $field
	 */
	public function __construct( Form_Field $field )
	{
		$this->field = $field;
		$this->view_script = $field->getInputViewScript();
		
		$this->setWidth( $field->getForm()->getDefaultFieldWidth() );
		
	}
	
	
	/**
	 * @return string
	 */
	public function getFieldType(): string
	{
		return $this->field_type;
	}
	
	/**
	 * @param string $field_type
	 */
	public function setFieldType( string $field_type ): void
	{
		$this->field_type = $field_type;
	}
	
	
	/**
	 *
	 */
	protected function generateTagAttributes_Standard() : void
	{
		$field = $this->getField();
		
		if($this->field_type) {
			$this->tag_attributes['type'] = $this->field_type;
		}
		
		$this->tag_attributes['name'] = $field->getTagNameValue();
		$this->tag_attributes['id'] = $field->getId();
		$this->tag_attributes['value'] = $field->getValue();
		
		if( $field->getPlaceholder() ) {
			$this->tag_attributes['placeholder'] = Data_Text::htmlSpecialChars( $field->getPlaceholder() );
		}
		if( $field->getIsReadonly() ) {
			$this->tag_attributes['readonly'] = 'readonly';
		}
		if( $field->getIsRequired() ) {
			$this->tag_attributes['required'] = 'required';
		}
		if( $field->getValidationRegexp() ) {
			$this->tag_attributes['pattern'] = $field->getValidationRegexp();
		}

	}
	
}