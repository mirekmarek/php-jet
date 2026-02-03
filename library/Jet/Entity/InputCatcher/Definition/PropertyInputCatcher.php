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
class Entity_InputCatcher_Definition_PropertyInputCatcher extends Entity_InputCatcher_Definition
{
	
	protected string $setter_method_name = '';
	
	
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
	 * @param string $option
	 * @param mixed|string $default_value
	 *
	 * @return mixed
	 */
	public function getOtherOption( string $option, mixed $default_value='' ) : mixed
	{
		return $this->other_options[$option]??$default_value;
	}
	
	public function getType(): string|false
	{
		return $this->type;
	}
	
	public function setType( string|false $type ): void
	{
		$this->type = $type;
	}
	
	
	public function createInputCatcher(): Entity_InputCatcher_PropertyInputCatcher
	{
		
		$input_catcher = Factory_InputCatcher::getInputCatcherInstance(
			type: $this->getType(),
			name: $this->getPropertyName(),
			default_value: null
		);
		


		foreach($this->other_options as $option=>$value) {
			$definition = $this->other_options_definition[$option];
			$setter = $definition->getSetter();
			
			$input_catcher->{$setter}($value);
		}
		
		if(($creator=$this->getCreator())) {
			/**
			 * @var Entity_InputCatcher_PropertyInputCatcher $input_catcher
			 */
			$input_catcher = $creator( $input_catcher );
		}
		
		return new Entity_InputCatcher_PropertyInputCatcher(
			$this->context_object,
			$this->property_name,
			$this->setter_method_name,
			$input_catcher
		);
	}
	
}