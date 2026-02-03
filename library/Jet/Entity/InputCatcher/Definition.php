<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use Attribute;
use ReflectionObject;


#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Entity_InputCatcher_Definition extends BaseObject
{
	protected string|false $type = '';
	
	protected bool $is_sub_input_catcher = false;
	protected bool $is_sub_input_catchers = false;
	
	protected object $context_object;
	protected string $property_name;
	
	/**
	 * @var ?callable
	 */
	protected $creator = null;
	
	
	/**
	 * @var array<string,mixed>
	 */
	protected array $other_options = [];
	
	
	/**
	 * @var array<string,Entity_InputCatcher_Definition_InputCatcherOption>
	 */
	protected array $other_options_definition = [];
	
	
	/**
	 * @param mixed ...$attributes
	 */
	public function __construct( ...$attributes )
	{
	}
	
	/**
	 * @param object $context_object
	 * @param string $property_name
	 * @param array<string,mixed> $definition_data
	 * @return void
	 */
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
		
		if($this->is_sub_input_catchers || $this->is_sub_input_catcher) {
			return;
		}
		
		$class = Factory_InputCatcher::getInputCatcherClassName( $this->type );
		/**
		 * @var InputCatcher $class
		 */
		$option_definition = $class::getInputCatcherOptionsDefinition();
		
		foreach($this->other_options as $option=>$value) {
			if(!isset($option_definition[$option])) {
				throw new Entity_InputCatcher_Definition_Exception('InputCatcher definition '.get_class($context_object).'::'.$property_name.' - unknown option \''.$option.'\'');
			}
			
			$this->other_options_definition[$option] = $option_definition[$option];
		}
		
		if(!$this->type) {
			throw new Entity_InputCatcher_Definition_Exception('InputCatcher definition '.get_class($context_object).'::'.$property_name.' - field type is not specified');
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
	
	public function getType(): string|false
	{
		return $this->type;
	}
	

	public function setType( string|false $type ): void
	{
		$this->type = $type;
	}
	
	public function getContextObject(): object
	{
		return $this->context_object;
	}
	
	public function getPropertyName(): string
	{
		return $this->property_name;
	}
	
	
	
	public function getCreator(): ?callable
	{
		$creator = $this->creator;
		
		if(is_array($creator) && $creator[0]==='this') {
			$creator[0] = $this->context_object;
		}
		
		return $creator;
	}
	
	/**
	 * @param callable|array<string>|null $creator
	 * @return void
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
	
	public function getPropertyValue() : mixed
	{
		$r = new ReflectionObject( $this->context_object );
		$p = $r->getProperty( $this->getPropertyName() );
		
		$value = '';
		if(PHP_VERSION_ID >= 80400) {
			/** @phpstan-ignore-next-line */
			$value = $p->getRawValue( $this->context_object );
		} else {
			if(PHP_VERSION_ID<80100) {
				$p->setAccessible(true);
			}
			$value = $p->getValue( $this->context_object );
		}
		
		return $value;
	}
	
}