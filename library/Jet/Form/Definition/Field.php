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
class Form_Definition_Field extends Form_Definition
{
	
	/**
	 * @var bool
	 */
	protected bool $is_required = false;
	
	/**
	 *
	 * @var string
	 */
	protected string $label = '';
	
	/**
	 *
	 * @var string
	 */
	protected string $help_text = '';
	
	/**
	 *
	 * @var array<string,mixed>
	 */
	protected array $help_data = [];
	
	/**
	 *
	 * @var array<string,string>
	 */
	protected array $error_messages = [];
	
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
	
	
	/**
	 * @return string|false
	 */
	public function getType(): string|false
	{
		return $this->type;
	}
	
	
	/**
	 * @param string|false $type
	 */
	public function setType( string|false $type ): void
	{
		$this->type = $type;
	}
	
	/**
	 * @return bool
	 */
	public function getIsRequired(): bool
	{
		return $this->is_required;
	}
	
	
	/**
	 * @return string
	 */
	public function getLabel(): string
	{
		return $this->label;
	}
	
	/**
	 * @param string $label
	 */
	public function setLabel( string $label ): void
	{
		$this->label = $label;
	}
	
	/**
	 * @return string
	 */
	public function getHelpText(): string
	{
		return $this->help_text;
	}
	
	/**
	 * @param string $help_text
	 */
	public function setHelpText( string $help_text ): void
	{
		$this->help_text = $help_text;
	}
	
	/**
	 * @return array<string,mixed>
	 */
	public function getHelpData(): array
	{
		return $this->help_data;
	}
	
	/**
	 * @param array<string,mixed> $help_data
	 */
	public function setHelpData( array $help_data ): void
	{
		$this->help_data = $help_data;
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
	 * @param array<Form_Field> &$form_fields
	 * @return void
	 */
	public function createFormField( array &$form_fields ): void
	{
		
		$field = Factory_Form::getFieldInstance(
			type: $this->getType(),
			name: $this->getFieldName(),
			label: $this->getLabel()
		);
		
		$field->setErrorMessages( $this->getErrorMessages() );


		foreach($this->other_options as $option=>$value) {
			$definition = $this->other_options_definition[$option];
			$setter = $definition->getSetter();
			
			$field->{$setter}($value);
		}
		
		$field->setFieldValueCatcher($this->createCatcher());
		
		if(($creator=$this->getCreator())) {
			/**
			 * @var Form_Field $field
			 */
			$field = $creator( $field );
		}
		
		$field->setIsRequired( $this->getIsRequired() );
		$field->setDefaultValue( $this->getDefaultValue() );
		
		$form_fields[] = $field;
	}
	
}