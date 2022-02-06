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
	protected string $input_type = '';
	
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
	 *
	 */
	protected function generateTagAttributes_Standard() : void
	{
		$field = $this->getField();
		
		if($this->input_type) {
			$this->tag_attributes['type'] = $this->input_type;
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