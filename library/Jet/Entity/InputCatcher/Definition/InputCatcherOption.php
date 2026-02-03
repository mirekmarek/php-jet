<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use Attribute;

/**
 *
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Entity_InputCatcher_Definition_InputCatcherOption extends BaseObject
{
	public const TYPE_STRING = 'string';
	public const TYPE_INT = 'int';
	public const TYPE_FLOAT = 'float';
	public const TYPE_BOOL = 'bool';
	public const TYPE_CALLABLE = 'callable';
	public const TYPE_ARRAY = 'array';
	public const TYPE_ASSOC_ARRAY = 'assoc_array';

	protected string $name = '';
	protected string $type = '';
	protected string $label = '';
	protected string $getter = '';
	protected string $setter = '';
	
	
	/**
	 * @param mixed ...$attributes
	 */
	public function __construct( ...$attributes )
	{
	}
	
	/**
	 * @param string $class_name
	 * @param string $property_name
	 * @param array<string,mixed> $definition
	 */
	public function setup( string $class_name, string $property_name, array $definition ) : void
	{
		$this->name = $property_name;
		
		foreach($definition as $key=>$val) {
			if(!property_exists($this, $key)) {
				throw new Entity_InputCatcher_Definition_Exception('InputCatcher option definition: '.$class_name.'::'.$property_name.' - unknown input catcher option definition property \''.$key.'\'');
			}
			$this->{$key} = $val;
		}
		
		if(!$this->type) {
			throw new Entity_InputCatcher_Definition_Exception('InputCatcher option definition: '.$class_name.'::'.$property_name.' - type is not specified');
		}
		
	}
	
	public function getName(): string
	{
		return $this->name;
	}
	
	public function getLabel(): string
	{
		return $this->label;
	}
	
	
	
	public function getType(): string
	{
		return $this->type;
	}
	
	public function getGetter(): string
	{
		return $this->getter;
	}
	
	public function getSetter(): string
	{
		return $this->setter;
	}
	
}