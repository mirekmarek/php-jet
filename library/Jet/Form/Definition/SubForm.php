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
class Form_Definition_SubForm extends Form_Definition
{
	protected bool|string $type = 'sub_form';
	protected bool $is_sub_form = true;
	
	/**
	 * @param object $context_object
	 * @param string $property_name
	 * @param array $definition_data
	 */
	public function __construct( object $context_object, string $property_name, array $definition_data )
	{
		$this->init( $context_object, $property_name, $definition_data );
	}
	
	/**
	 * @param string|bool $type
	 */
	public function setType( string|bool $type ): void
	{
	}
	
	
	/**
	 *
	 */
	public function createFormFields( string $parent_name, array &$form_fields  ): void
	{
		$property_value = $this->getDefaultValue();
		if(!$property_value) {
			return;
		}
		
		if(!($property_value instanceof Form_Definition_Interface)) {
			throw new Form_Definition_Exception('Form definition '.get_class($this->context_object).'::'.$this->property_name.' - is not sub form creator (interface Form_Definition_Interface is not implemented)');
		}
		
		$sub_form = $property_value->createForm('');
		
		$sub_fields = [];
		foreach($sub_form->getFields() as $field) {
			$name = $parent_name;
			
			if( $field->getName()[0]!='/' ) {
				$name .= '/';
			}
			
			$field->setName( $name.$field->getName() );
			$sub_fields[] = $field;
		}
		
		$creator = $this->getCreator();
		if($creator) {
			$sub_fields = $creator( $sub_fields );
		}
		
		foreach($sub_fields as $field) {
			$form_fields[] = $field;
		}
		
		
	}
}