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

	/**
	 * Adds the accessibility tag attributes shared by every input type:
	 *
	 * - aria-describedby, pointing at the field's help text and/or last error message
	 *   (when present), so a screen reader announces them together with the field
	 * - aria-invalid="true", when the field currently has a validation error
	 *
	 * Must be called at the end of generateTagAttributes_Standard() by every subclass.
	 *
	 * The ids referenced here ('{field_id}-help', '{field_id}-error') are created in
	 * views/form/field/help.phtml and views/form/field/error.phtml - both must agree
	 * on this naming scheme.
	 */
	protected function addAccessibilityAttributes() : void
	{
		$field = $this->getField();

		$described_by = [];

		if( $field->getHelpText() ) {
			$described_by[] = $field->getId().'-help';
		}

		if( $field->getLastErrorMessage() ) {
			$described_by[] = $field->getId().'-error';
			$this->_tag_attributes['aria-invalid'] = 'true';
		}

		if( $described_by ) {
			$this->_tag_attributes['aria-describedby'] = implode( ' ', $described_by );
		}
	}
}