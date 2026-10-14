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
abstract class Form_Renderer_Field_Input extends Form_Renderer_Single
{
	
	/**
	 * @var Form_Field
	 */
	protected Form_Field $field;
	
	
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
	 * @return array<string,int>|null
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
	
	public function getAriaDescribedBy_a11y(): ?string
	{
		$ids = [];
		if ($this->getField()->getLastErrorCode()) {
			$ids[] = $this->field->error()->getId();
		}
		if ($this->getField()->getHelpText()) {
			$ids[] = $this->field->help()->getId();
		}
		return $ids ? implode(' ', $ids) : null;
	}
	
	/**
	 *
	 */
	protected function generateTagAttributes_a11y() : void
	{
		if( $this->field->getAriaLabel() ) {
			$this->_tag_attributes['aria-label'] = $this->field->getAriaLabel();
		} else{
			if( $this->field->getAriaLabelledBy() ) {
				$this->_tag_attributes['aria-labelledby'] = $this->field->getAriaLabelledBy();
			}
		}
		
		$this->_tag_attributes['aria-required'] = $this->field->getIsRequired() ? 'true' : 'false';
		
		if( $this->field->getLastErrorCode() ) {
			$this->_tag_attributes['aria-invalid'] = 'true';
		}
		
		$described_by = $this->getAriaDescribedBy_a11y();
		if( $described_by ) {
			$this->_tag_attributes['aria-describedby'] = $described_by;
		}
	}
	
}