<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Form_Renderer_Field_Input;

class Form_Renderer_Field_Input_MetaTags extends Form_Renderer_Field_Input {
	/**
	 *
	 */
	protected function generateTagAttributes_Standard() : void
	{
		$field = $this->getField();
		
		$this->_tag_attributes['type'] = 'text';
		
		
		if( $field->getIsReadonly() ) {
			$this->_tag_attributes['readonly'] = 'readonly';
		}
	}
	
}