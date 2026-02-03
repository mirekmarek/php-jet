<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use ReflectionClass;

abstract class InputCatcher extends BaseObject
{
	public const TYPE_STRING = 'string';
	public const TYPE_STRINGS = 'strings';
	public const TYPE_STRING_RAW = 'string_raw';
	public const TYPE_FLOAT = 'float';
	public const TYPE_FLOATS = 'floats';
	public const TYPE_INT = 'int';
	public const TYPE_INTS = 'ints';
	public const TYPE_DATE = 'date';
	public const TYPE_DATE_TIME = 'date_time';
	public const TYPE_BOOL = 'bool';
	public const TYPE_FILE = 'file';
	
	protected string $_type = '';
	
	protected string $name;
	protected mixed $default_value;
	
	protected mixed $value_raw = null;
	protected mixed $value = null;
	protected bool $value_exists_in_the_input = false;
	
	
	/**
	 * @var Entity_InputCatcher_Definition_InputCatcherOption[][]
	 */
	protected static array $input_catcher_options_definition = [];
	
	
	public function __construct( string $name, mixed $default_value )
	{
		$this->name = $name;
		$this->default_value = $default_value;
		$this->value_raw = $default_value;
		$this->value = $default_value;
		$this->checkValue();
	}
	
	
	public function catchInput( Data_Array $data ): void
	{
		$this->value = null;
		$this->value_exists_in_the_input = $data->exists( $this->name );
		
		if( $this->value_exists_in_the_input ) {
			$this->value_raw = $data->getRaw( $this->name );
			$this->value = $this->value_raw;
			
		} else {
			$this->value_raw = $this->getDefaultValue();
			$this->value = $this->getDefaultValue();
		}
		$this->checkValue();
	}
	
	abstract protected function checkValue() : void;
	
	public function setName( string $name ): void
	{
		$this->name = $name;
	}
	
	public function getName(): string
	{
		return $this->name;
	}
	
	public function valueExistsInTheInput(): bool
	{
		return $this->value_exists_in_the_input;
	}
	
	
	public function getDefaultValue(): mixed
	{
		return $this->default_value;
	}
	
	public function setDefaultValue( mixed $default_value ): void
	{
		$this->default_value = $default_value;
		$this->checkValue();
	}
	
	public function getValue(): mixed
	{
		return $this->value;
	}
	
	public function setValue( mixed $value ): void
	{
		$this->value_raw = $value;
		$this->value = $value;
		$this->checkValue();
	}
	
	public function getValueRaw(): mixed
	{
		return $this->value_raw;
	}
	
	/**
	 * @return array<string,Entity_InputCatcher_Definition_InputCatcherOption>
	 * @throws Entity_InputCatcher_Definition_Exception
	 */
	public static function getInputCatcherOptionsDefinition() : array
	{
		$class = static::class;
		
		if(!array_key_exists($class, static::$input_catcher_options_definition)) {
			$properties = Attributes::getClassPropertyDefinition( new ReflectionClass($class), Entity_InputCatcher_Definition_InputCatcherOption::class );
			static::$input_catcher_options_definition[$class] = [];
			
			foreach($properties as $option_name=>$def_data) {
				static::$input_catcher_options_definition[$class][$option_name] = new Entity_InputCatcher_Definition_InputCatcherOption();
				static::$input_catcher_options_definition[$class][$option_name]->setup($class, $option_name, $def_data);
				
			}
			
			
		}
		return static::$input_catcher_options_definition[$class];
	}
	
}