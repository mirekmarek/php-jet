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
trait Entity_InputCatcher_Trait
{
	
	/**
	 * @return Entity_InputCatcher_Definition_PropertyInputCatcher[]
	 */
	public function getPropertyInputCatchersDefinition() : array
	{
		
		$reflection = new ReflectionClass( $this );
		
		$input_catchers_definition_data = Attributes::getClassPropertyDefinition( $reflection, Entity_InputCatcher_Definition::class );
		
		$input_catcher_definitions = [];
		foreach( $input_catchers_definition_data as $property_name => $definition_data ) {
			if(!empty($definition_data['is_sub_input_catcher'])) {
				$definition = new Entity_InputCatcher_Definition_SubEntity_InputCatcher( $this, $property_name, $definition_data );
				
				$input_catcher_definitions[$definition->getPropertyName()] = $definition;
				continue;
			}
			
			if(!empty($definition_data['is_sub_input_catchers'])) {
				$definition = new Entity_InputCatcher_Definition_SubEntity_InputCatchers( $this, $property_name, $definition_data );
				
				$input_catcher_definitions[$definition->getPropertyName()] = $definition;
				continue;
			}
			
			
			$definition = new Entity_InputCatcher_Definition_PropertyInputCatcher( $this, $property_name, $definition_data );
			
			$input_catcher_definitions[$definition->getPropertyName()] = $definition;
		}
		
		return $input_catcher_definitions;
	}
	
	
	public function catchInput( array|Data_Array $input ) : void
	{
		if(!is_object($input)) {
			$input = new Data_Array( $input );
		}
		
		foreach( $this->getPropertyInputCatchersDefinition() as $property_name => $definition ) {
			
			if($definition instanceof Entity_InputCatcher_Definition_SubEntity_InputCatcher) {
				
				$factory_method_name = $definition->getFactoryMethodName();
				$this->{$factory_method_name}();
				
				/**
				 * @var ?Entity_InputCatcher_Interface $property
				 */
				$property = $this->{$property_name};
				
				$property->catchInput( $input->getRaw('/'.$property_name) );
				
				
				continue;
			}
			
			if($definition instanceof Entity_InputCatcher_Definition_SubEntity_InputCatchers) {
				/**
				 * @var ?Entity_InputCatcher_Interface $property
				 */
				
				$s_inputs = $input->getRaw('/'.$property_name);
				$factory_method_name = $definition->getFactoryMethodName();
				
				$this->{$factory_method_name}( array_keys($s_inputs) );
				
				
				foreach($s_inputs as $key=>$s_input) {
					if(isset($this->{$property_name}[$key])) {
						$this->{$property_name}[$key]->catchInput( $s_input );
					}
				}
				
				continue;
			}
			
			if($definition instanceof Entity_InputCatcher_Definition_PropertyInputCatcher) {
				$definition->createInputCatcher()->catchInput( $input );
				
			}
		}
		
	}
}

