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
class Form_Renderer_Field_Label extends Form_Renderer_Single
{
	
	/**
	 * @param Form_Field $field
	 */
	public function __construct( Form_Field $field )
	{
		$this->field = $field;
		$this->view_script = $field->getLabelViewScript();
		
		$this->setWidth( $field->getForm()->getDefaultLabelWidth() );
		
	}
	
	/**
	 *
	 */
	public function generateTagAttributes_Standard() : void
	{
		$this->tag_attributes['for'] = $this->field->getId();
	}
	
}