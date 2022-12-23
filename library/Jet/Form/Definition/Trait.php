<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
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
	 * @param array $only_fields
	 * @param array $exclude_fields
	 * @return Form
	 *
	 * @throws Form_Definition_Exception
	 */
	public function createForm( string $form_name, array $only_fields=[], array $exclude_fields=[]  ): Form
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
		
		$filter = function( Form_Field $field, array $by ) : bool
		{
			$name = $field->getName();
			if($name[0]!='/') {
				return in_array($name, $by) || in_array('*', $by);
			}
			
			$name = explode('/', $name);
			
			foreach($by as $filter_item) {
				
				if($filter_item[0]!='/') {
					continue;
				}
				
				$pass = true;

				$filter_item = explode('/', $filter_item);
				
				foreach($filter_item as $i=>$filter_item_part) {
					if(!isset($name[$i])) {
						$pass = false;
						break;
					}
					
					if(
						$filter_item_part!='*' &&
						$name[$i]!=$filter_item_part
					) {
						$pass = false;
						break;
					}
				}
				
				if($pass) {
					return true;
				}
			}
			
			return false;
		};
		
		if($only_fields) {
			foreach( $form_fields as $i => $field ) {
				if( !$filter($field, $only_fields) ) {
					unset($form_fields[$i]);
				}
			}
		}
		
		if($exclude_fields) {
			foreach( $form_fields as $i => $field ) {
				if( $filter($field, $exclude_fields) ) {
					unset($form_fields[$i]);
				}
			}
		}
		
		
		return new Form( $form_name, $form_fields );
		
	}
}