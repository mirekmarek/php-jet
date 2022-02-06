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
	 * @var Form_Field
	 */
	protected Form_Field $field;
	
	/**
	 * @var string
	 */
	protected string $input_type = '';
	
	
	/**
	 * @var bool
	 */
	protected bool $has_value_attribute = true;
	
	
	/**
	 *
	 * @param Form_Field $field
	 */
	public function __construct( Form_Field $field )
	{
		$this->field = $field;
		$this->view_script = SysConf_Jet_Form_DefaultViews::get($field->getType(), 'input');
	}
	
	/**
	 * @return Form_Field
	 */
	public function getField(): Form_Field
	{
		return $this->field;
	}
	
	/**
	 * @return array|null
	 */
	public function getWidth(): array|null
	{
		if(!$this->width) {
			return $this->field->getForm()->renderer()->getDefaultFieldWidth();
		}
		
		return $this->width;
	}
	
	/**
	 * @return string
	 */
	public function getViewDir(): string
	{
		if(!$this->view_dir) {
			return $this->field->renderer()->getViewDir();
		}
		
		return $this->view_dir;
	}
	
	
	/**
	 * @return string
	 */
	public function getInputType(): string
	{
		return $this->input_type;
	}
	
	/**
	 * @param string $input_type
	 */
	public function setInputType( string $input_type ): void
	{
		$this->input_type = $input_type;
	}
	
	/**
	 * @return bool
	 */
	public function hasValueAttribute(): bool
	{
		return $this->has_value_attribute;
	}
	
	/**
	 * @param bool $has_value_attribute
	 */
	public function setHasValueAttribute( bool $has_value_attribute ): void
	{
		$this->has_value_attribute = $has_value_attribute;
	}
	
	
	
	
	/**
	 *
	 */
	protected function generateTagAttributes_Standard() : void
	{
		$field = $this->getField();
		
		if($this->input_type) {
			$this->_tag_attributes['type'] = $this->input_type;
		}
		
		$this->_tag_attributes['name'] = $field->getTagNameValue();
		$this->_tag_attributes['id'] = $field->getId();
		
		if($this->hasValueAttribute()) {
			$this->_tag_attributes['value'] = $field->getValue();
		}
		
		if( $field->getPlaceholder() ) {
			$this->_tag_attributes['placeholder'] = Data_Text::htmlSpecialChars( $field->getPlaceholder() );
		}
		if( $field->getIsReadonly() ) {
			$this->_tag_attributes['readonly'] = 'readonly';
		}
		if( $field->getIsRequired() ) {
			$this->_tag_attributes['required'] = 'required';
		}
		if( $field->getValidationRegexp() ) {
			$this->_tag_attributes['pattern'] = $field->getValidationRegexp();
		}

	}
	
}