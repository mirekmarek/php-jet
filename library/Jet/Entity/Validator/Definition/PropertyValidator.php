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
class Entity_Validator_Definition_PropertyValidator extends Entity_Validator_Definition
{
	
	/**
	 *
	 * @var array<string,string>
	 */
	protected array $error_messages = [];
	
	protected bool $is_required = false;
	
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
	
	public function getIsRequired(): bool
	{
		return $this->is_required;
	}
	
	public function setIsRequired( bool $is_required ): void
	{
		$this->is_required = $is_required;
	}
	
	
	
	
	/**
	 * @return array<string,string>
	 */
	public function getErrorMessages(): array
	{
		return $this->error_messages;
	}
	
	/**
	 * @param array<string,string> $messages
	 *
	 */
	public function setErrorMessages( array $messages ): void
	{
		$this->error_messages = $messages;
	}
	
	/**
	 * @param array<string|Entity_Validator_PropertyValidator> &$validators
	 * @return void
	 */
	public function createValidator( array &$validators ): void
	{
		
		$validator = Factory_Validator::getValidatorInstance(
			type: $this->getType()
		);
		
		$validator->setErrorMessages( $this->getErrorMessages() );


		foreach($this->other_options as $option=>$value) {
			$definition = $this->other_options_definition[$option];
			$setter = $definition->getSetter();
			
			$validator->{$setter}($value);
		}
		
		if(($creator=$this->getCreator())) {
			/**
			 * @var Entity_Validator_PropertyValidator $validator
			 */
			$validator = $creator( $validator );
		}
		
		$property_validator = new Entity_Validator_PropertyValidator(
			$this->context_object,
			$this->property_name,
			$validator,
			$this->is_required
		);
		
		$validators[$property_validator->getPropertyPath()] = $property_validator;
	}
	
}