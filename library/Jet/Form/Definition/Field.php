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
class Form_Definition_Field extends BaseObject
{
	
	/**
	 * @var object
	 */
	protected object $context_object;
	
	/**
	 * @var string
	 */
	protected string $property_name;
	
	/**
	 * @var mixed
	 */
	protected mixed $property;
	
	/**
	 * @var ?callable
	 */
	protected $creator = null;
	
	/**
	 *
	 * @var string|bool
	 */
	protected string|bool $type = '';
	
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
	 * @var array
	 */
	protected array $help_data = [];

	
	/**
	 *
	 * @var array
	 */
	protected array $error_messages = [];
	
	/**
	 * @var string
	 */
	protected string $default_value_getter_name = '';
	
	/**
	 * @var string
	 */
	protected string $setter_name = '';
	
	/**
	 * @var array
	 */
	protected array $other_options = [];
	
	/**
	 * @var Form_Definition_FieldOption[]
	 */
	protected array $other_options_definition = [];
	
	/**
	 * @param object $context_object
	 * @param string $property_name
	 * @param mixed &$property
	 * @param array $definition_data
	 */
	public function __construct( object $context_object, string $property_name, mixed &$property, array $definition_data )
	{
		$this->context_object = $context_object;
		$this->property_name = $property_name;
		$this->property = &$property;
		
		foreach($definition_data as $key=>$value) {
			if(property_exists($this, $key)) {
				$this->{$key} = $value;
			} else {
				$this->other_options[$key] = $value;
			}
		}
		
		if(!$this->type) {
			throw new Form_Definition_Exception('Form definition '.get_class($context_object).'::'.$property_name.' - field type is not specified');
		}
		
		$class = Factory_Form::getFieldClassName( $this->type );
		/**
		 * @var Form_Field $class
		 */
		$options = $class::getFieldOptionsDefinition();
		
		foreach($this->other_options as $option=>$value) {
			if(!isset($options[$option])) {
				throw new Form_Definition_Exception('Form definition '.get_class($context_object).'::'.$property_name.' - unknown option \''.$option.'\'');
			}
			
			$this->other_options_definition[$option] = $options[$option];
		}
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
	 * @return object
	 */
	public function getContextObject(): object
	{
		return $this->context_object;
	}
	
	/**
	 * @return string
	 */
	public function getPropertyName(): string
	{
		return $this->property_name;
	}
	
	/**
	 * @return string
	 */
	public function getFieldName() : string
	{
		return $this->property_name;
	}
	
	
	
	
	/**
	 * @return string|bool
	 */
	public function getType(): string|bool
	{
		return $this->type;
	}
	
	
	/**
	 * @param string|bool $type
	 */
	public function setType( string|bool $type ): void
	{
		$this->type = $type;
	}
	
	/**
	 * @return ?callable
	 */
	public function getCreator(): ?callable
	{
		$creator = $this->creator;
		
		if(is_array($creator) && $creator[0]==='this') {
			$creator[0] = $this->context_object;
		}
		
		return $creator;
	}
	
	/**
	 * @param null|callable|array $creator
	 */
	public function setCreator( null|callable|array $creator ): void
	{
		if(
			is_array($creator) &&
			is_object($creator[0]) &&
			get_class($creator[0])==get_class($this->context_object)
		) {
			$creator[0] = 'this';
		}
		
		$this->creator = $creator;
	}
	
	/**
	 * @return string
	 */
	public function getDefaultValueGetterName(): string
	{
		return $this->default_value_getter_name;
	}
	
	/**
	 * @param bool $get_defined
	 * @return string
	 */
	public function getSetterName( bool $get_defined=false ): string
	{
		if($get_defined) {
			return $this->setter_name;
		}
		
		if($this->setter_name) {
			return $this->setter_name;
		}
		
		if($this->context_object instanceof BaseObject) {
			$setter_method_name = $this->context_object->objectSetterMethodName( $this->getPropertyName() );
			if(method_exists($this->context_object, $setter_method_name)) {
				return $setter_method_name;
			}
		}
		
		return '';
	}
	
	/**
	 * @param string $setter_name
	 */
	public function setSetterName( string $setter_name ): void
	{
		$this->setter_name = $setter_name;
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
	 * @return array
	 */
	public function getHelpData(): array
	{
		return $this->help_data;
	}
	
	/**
	 * @param array $help_data
	 */
	public function setHelpData( array $help_data ): void
	{
		$this->help_data = $help_data;
	}
	
	
	
	/**
	 * @return array
	 */
	public function getErrorMessages(): array
	{
		return $this->error_messages;
	}
	
	/**
	 * @param array $messages
	 *
	 */
	public function setErrorMessages( array $messages ): void
	{
		$this->error_messages = $messages;
	}
	
	/**
	 *
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
		
		if( ($setter_method_name = $this->getSetterName()) ) {
			$field->setFieldValueCatcher(function( $value ) use ($setter_method_name) {
				$this->context_object->{$setter_method_name}( $value );
			});
		} else {
			$field->setFieldValueCatcher(function( $value ) {
				$this->property = $value;
			});
			
		}
		
		if(($creator=$this->getCreator())) {
			$field = $creator( $field );
		}
		
		$field->setIsRequired( $this->getIsRequired() );
		
		
		if(($default_value_getter = $this->getDefaultValueGetterName())) {
			$default_value = $this->context_object->{$default_value_getter}();
		} else {
			$default_value = $this->property;
		}
		
		$field->setDefaultValue( $default_value );
		
		$form_fields[] = $field;
	}
	
}