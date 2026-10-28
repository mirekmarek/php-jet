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
class Form_Renderer_Field_Label extends Form_Renderer_Single
{
	
	/**
	 * @var Form_Field
	 */
	protected Form_Field $field;
	
	/**
	 * @var bool
	 */
	protected bool $disable_attribute_for = false;
	
	/**
	 * @param Form_Field $field
	 */
	public function __construct( Form_Field $field )
	{
		$this->field = $field;
		$this->view_script = SysConf_Jet_Form_DefaultViews::get($field->getType(), 'label');
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
			return $this->field->getForm()->renderer()->getDefaultLabelWidth();
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
	
	public function getDisableAttributeFor(): bool
	{
		return $this->disable_attribute_for;
	}
	
	public function setDisableAttributeFor( bool $disable_attribute_for ): void
	{
		$this->disable_attribute_for = $disable_attribute_for;
	}
	
	
	
	/**
	 *
	 */
	protected function generateTagAttributes_Standard() : void
	{
		if(!$this->disable_attribute_for) {
			$this->_tag_attributes['for'] = $this->field->getId();
		} else {
			$this->_tag_attributes['aria-hidden'] = 'true';
		}
	}
	
}