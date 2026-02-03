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
class Entity_Validator_Definition_SubEntity_Validators extends Entity_Validator_Definition
{
	protected false|string $type = 'sub_validators';
	protected bool $is_sub_validators = true;
	
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
	 * @param array<string,Entity_Validator_PropertyValidator> $validators
	 * @return void
	 */
	public function createValidators( string $parent_name, array &$validators ): void
	{
		$property_value = $this->getPropertyValue();
		if(!$property_value) {
			return;
		}
		
		if(!is_iterable($property_value)) {
			throw new Entity_Validator_Definition_Exception('Validator definition '.get_class($this->context_object).'::'.$this->property_name.' - is not iterable');
		}
		
		
		$sub_validators = [];
		foreach($property_value as $key=>$sub) {
			if(!($sub instanceof Entity_Validator_Interface)) {
				throw new Entity_Validator_Definition_Exception('Validator definition '.get_class($this->context_object).'::'.$this->property_name.'['.$key.'] - is not sub validator creator (interface Validator_Definition_Interface is not implemented)');
			}
			
			$sub_validator = $sub->createValidator();
			
			foreach($sub_validator->getValidators() as $validator) {
				$name = $parent_name.'/'.$key;
				if( $validator->getPropertyPath()[0]!='/' ) {
					$name .= '/';
				}
				
				$validator->setPropertyPath( $name.$validator->getPropertyPath() );
				$sub_validators[] = $validator;
			}
			
		}
		
		$creator = $this->getCreator();
		if($creator) {
			$sub_validators = $creator( $sub_validators );
		}
		
		foreach($sub_validators as $validator) {
			/**
			 * @phpstan-ignore parameterByRef.type
			 */
			$validators[$validator->getPropertyPath()] = $validator;
		}
		
	}
	
}