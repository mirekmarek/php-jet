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
class Form_Renderer_Field_Container extends Form_Renderer_Pair
{
	
	/**
	 * @param Form_Field $field
	 */
	public function __construct( Form_Field $field )
	{
		$this->field = $field;
		$this->view_script_start = $field->getInputContainerStartViewScript();
		$this->view_script_end = $field->getInputContainerEndViewScript();
		
		$this->setWidth( $field->getForm()->getDefaultFieldWidth() );
		
	}
	
	/**
	 *
	 */
	public function generateTagAttributes_Standard() : void
	{
	}
	
}