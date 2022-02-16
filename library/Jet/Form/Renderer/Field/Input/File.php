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
class Form_Renderer_Field_Input_File extends Form_Renderer_Field_Input
{
	
	/**
	 *
	 */
	protected function generateTagAttributes_Standard() : void
	{
		$field = $this->getField();
		
		$this->_tag_attributes['type'] = 'file';
		$this->_tag_attributes['name'] = $field->getTagNameValue();
		$this->_tag_attributes['id'] = $field->getId();
		
		/**
		 * @var Form_Field_Part_File_Interface $field
		 */
		
		
		if( $field->getIsReadonly() ) {
			$this->_tag_attributes['readonly'] = 'readonly';
		}
		if( $field->getIsRequired() ) {
			$this->_tag_attributes['required'] = 'required';
		}
		
		
		if( $field->getAllowMultipleUpload() ) {
			$this->_tag_attributes['multiple'] = 'multiple';
		}
		if( $field->getAllowedMimeTypes() ) {
			$this->_tag_attributes['accept'] = implode( '|', $field->getAllowedMimeTypes() );
		}
	}
	
}