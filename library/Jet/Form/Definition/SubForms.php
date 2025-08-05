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
class Form_Definition_SubForms extends Form_Definition
{
	protected false|string $type = 'sub_forms';
	protected bool $is_sub_forms = true;
	
	/**
	 * @param object $context_object
	 * @param string $property_name
	 * @param array<string,mixed> $definition_data
	 */
	public function __construct( object $context_object, string $property_name, array $definition_data )
	{
		$this->init( $context_object, $property_name, $definition_data );
	}
	
	/**
	 * @param string|false $type
	 */
	public function setType( string|false $type ): void
	{
	}
	
	
	/**
	 * @param string $parent_name
	 * @param array<Form_Field> &$form_fields
	 * @return void
	 */
	public function createFormFields( string $parent_name, array &$form_fields ): void
	{
		$property_value = $this->getDefaultValue();
		if(!$property_value) {
			return;
		}
		
		if(!is_iterable($property_value)) {
			throw new Form_Definition_Exception('Form definition '.get_class($this->context_object).'::'.$this->property_name.' - is not iterable');
		}
		
		
		$sub_fields = [];
		foreach($property_value as $key=>$sub) {
			if(!($sub instanceof Form_Definition_Interface)) {
				throw new Form_Definition_Exception('Form definition '.get_class($this->context_object).'::'.$this->property_name.'['.$key.'] - is not sub form creator (interface Form_Definition_Interface is not implemented)');
			}
			
			$sub_form = $sub->createForm('');
			
			foreach($sub_form->getFields() as $field) {
				$name = $parent_name.'/'.$key;
				if( $field->getName()[0]!='/' ) {
					$name .= '/';
				}
				
				$field->setName( $name.$field->getName() );
				$sub_fields[] = $field;
			}
			
		}
		
		$creator = $this->getCreator();
		if($creator) {
			$sub_fields = $creator( $sub_fields );
		}
		
		/**
		 * @var array<Form_Field> $sub_fields
		 */
		foreach($sub_fields as $field) {
			$form_fields[] = $field;
		}
		
	}
	
}