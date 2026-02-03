<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use ReflectionClass;


/** @phpstan-ignore trait.unused */
trait Entity_Validator_Trait
{
	
	/**
	 * @return Entity_Validator_Definition_PropertyValidator[]
	 */
	public function getPropertyValidatorsDefinition() : array
	{
		
		$reflection = new ReflectionClass( $this );
		
		$validators_definition_data = Attributes::getClassPropertyDefinition( $reflection, Entity_Validator_Definition::class );
		
		$validator_definitions = [];
		foreach( $validators_definition_data as $property_name => $definition_data ) {
			if(!empty($definition_data['is_sub_validator'])) {
				$definition = new Entity_Validator_Definition_SubEntity_Validator( $this, $property_name, $definition_data );
				
				$validator_definitions[$definition->getPropertyName()] = $definition;
				continue;
			}
			
			if(!empty($definition_data['is_sub_validators'])) {
				$definition = new Entity_Validator_Definition_SubEntity_Validators( $this, $property_name, $definition_data );
				
				$validator_definitions[$definition->getPropertyName()] = $definition;
				continue;
			}
			
			
			$definition = new Entity_Validator_Definition_PropertyValidator( $this, $property_name, $definition_data );
			
			$validator_definitions[$definition->getPropertyName()] = $definition;
		}
		
		return $validator_definitions;
	}
	
	/**
	 * @param array<string> $only_properties
	 * @param array<string> $exclude_properties
	 * @return Entity_Validator
	 */
	public function createValidator( array $only_properties=[], array $exclude_properties=[]  ): Entity_Validator
	{
		$validators = [];
		
		foreach( $this->getPropertyValidatorsDefinition() as $property_name => $definition ) {

			if($definition instanceof Entity_Validator_Definition_SubEntity_Validator) {
				$definition->createValidators( '/'.$property_name, $validators );
				
				continue;
			}
			
			if($definition instanceof Entity_Validator_Definition_SubEntity_Validators) {
				$definition->createValidators( '/'.$property_name, $validators );
				
				continue;
			}
			
			/** @phpstan-ignore instanceof.alwaysTrue */
			if($definition instanceof Entity_Validator_Definition_PropertyValidator) {
				$definition->createValidator( $validators );
			}
		}
		
		$filter = function( Entity_Validator_PropertyValidator $field, array $by ) : bool
		{
			$name = $field->getPropertyPath();
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
		
		if($only_properties) {
			foreach( $validators as $i => $field ) {
				if( !$filter($field, $only_properties) ) {
					unset($validators[$i]);
				}
			}
		}
		
		if($exclude_properties) {
			foreach( $validators as $i => $field ) {
				if( $filter($field, $exclude_properties) ) {
					unset($validators[$i]);
				}
			}
		}
		
		return new Entity_Validator( $this, $validators );
	}
}