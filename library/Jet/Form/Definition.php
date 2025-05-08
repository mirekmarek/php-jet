<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use Attribute;
use ReflectionClass;

/**
 *
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Form_Definition extends BaseObject
{
	/**
	 *
	 * @var string|bool
	 */
	protected string|bool $type = '';
	
	protected bool $is_sub_form = false;
	protected bool $is_sub_forms = false;
	
	
	/**
	 * @var object
	 */
	protected object $context_object;
	
	/**
	 * @var string
	 */
	protected string $property_name;
	
	/**
	 * @var ?callable
	 */
	protected $creator = null;
	
	
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
	 * @param mixed ...$attributes
	 */
	public function __construct( ...$attributes )
	{
	}
	
	protected function init( object $context_object, string $property_name, array $definition_data ) : void
	{
		$this->context_object = $context_object;
		$this->property_name = $property_name;
		
		foreach($definition_data as $key=>$value) {
			if(property_exists($this, $key)) {
				$this->{$key} = $value;
			} else {
				$this->other_options[$key] = $value;
			}
		}
		
		if($this->is_sub_forms || $this->is_sub_form) {
			return;
		}
		
		$class = Factory_Form::getFieldClassName( $this->type );
		/**
		 * @var Form_Field $class
		 */
		$option_definition = $class::getFieldOptionsDefinition();
		
		foreach($this->other_options as $option=>$value) {
			if(!isset($option_definition[$option])) {
				throw new Form_Definition_Exception('Form definition '.get_class($context_object).'::'.$property_name.' - unknown option \''.$option.'\'');
			}
			
			$this->other_options_definition[$option] = $option_definition[$option];
		}
		
		if(!$this->type) {
			throw new Form_Definition_Exception('Form definition '.get_class($context_object).'::'.$property_name.' - field type is not specified');
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
	 * @param bool $get_defined
	 * @return string
	 */
	public function getDefaultValueGetterName( bool $get_defined=false ): string
	{
		if($get_defined) {
			return $this->default_value_getter_name;
		}
		
		if($this->default_value_getter_name) {
			return $this->default_value_getter_name;
		}
		
		if($this->context_object instanceof BaseObject) {
			$getter_method_name = $this->context_object->objectGetterMethodName( $this->getPropertyName() );
			if(method_exists($this->context_object, $getter_method_name)) {
				return $getter_method_name;
			}
		}
		
		return '';
		
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
	 * @return mixed
	 */
	protected function getDefaultValue() : mixed
	{
		$ref = new ReflectionClass($this->context_object);
		$property = $ref->getProperty($this->getPropertyName());
		return $property->getRawValue( $this->context_object );
	}
	
	/**
	 * @return callable
	 */
	protected function createCatcher() : callable
	{
		if( ($setter_method_name = $this->getSetterName()) ) {
			return function( $value ) use ($setter_method_name) {
				$this->context_object->{$setter_method_name}( $value );
			};
		} else {
			return function( $value ) {
				$this->context_object->{$this->property_name} = $value;
			};
		}
		
	}
	
}