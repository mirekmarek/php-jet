<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use ReflectionClass;


trait Form_Definition_Trait
{
	
	/**
	 * @return Form_Definition_SubForm[]|Form_Definition_SubForms[]|Form_Definition_Field[]
	 * @throws Form_Definition_Exception
	 * @noinspection PhpDocSignatureInspection
	 */
	public function getFormFieldsDefinition() : array
	{
		
		$reflection = new ReflectionClass( $this );
		
		$fields_definition_data = Attributes::getClassPropertyDefinition( $reflection, Form_Definition::class );
		
		$fields_definition = [];
		foreach( $fields_definition_data as $property_name => $definition_data ) {
			if(!empty($definition_data['is_sub_form'])) {
				$definition = new Form_Definition_SubForm( $this, $property_name, $this->{$property_name}, $definition_data );
				
				$fields_definition[$definition->getPropertyName()] = $definition;
				continue;
			}
			
			if(!empty($definition_data['is_sub_forms'])) {
				$definition = new Form_Definition_SubForms( $this, $property_name, $this->{$property_name}, $definition_data );
				
				$fields_definition[$definition->getPropertyName()] = $definition;
				continue;
			}
			
			
			$definition = new Form_Definition_Field( $this, $property_name, $this->{$property_name}, $definition_data );
			
			$fields_definition[$definition->getPropertyName()] = $definition;
		}
		
		return $fields_definition;
	}
	
	/**
	 * @param string $form_name
	 *
	 * @return Form
	 *
	 * @throws Form_Definition_Exception
	 */
	public function createForm( string $form_name ): Form
	{
		$form_fields = [];
		
		foreach( $this->getFormFieldsDefinition() as $property_name => $definition ) {

			if($definition instanceof Form_Definition_SubForm) {
				$definition->createFormFields( '/'.$property_name, $form_fields );
				
				continue;
			}
			
			if($definition instanceof Form_Definition_SubForms) {
				$definition->createFormFields( '/'.$property_name, $form_fields );
				
				continue;
			}
			
			if($definition instanceof Form_Definition_Field) {
				$definition->createFormField( $form_fields );
			}
		}
		
		return new Form( $form_name, $form_fields );
		
	}
}