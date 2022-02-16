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
class Form_Definition_FieldOption extends BaseObject
{
	const TYPE_STRING = 'string';
	const TYPE_INT = 'int';
	const TYPE_FLOAT = 'float';
	const TYPE_BOOL = 'bool';
	const TYPE_CALLABLE = 'callable';
	const TYPE_ARRAY = 'array';
	const TYPE_ASSOC_ARRAY = 'assoc_array';

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
	 * @param string $field_class
	 * @param string $name
	 * @param array $definition
	 */
	public function setup( string $field_class, string $name, array $definition ) {
		$this->name = $name;
		
		foreach($definition as $key=>$val) {
			if(!property_exists($this, $key)) {
				throw new Form_Definition_Exception('Field option definition: '.$field_class.'::'.$name.' - unknown field option definition property \''.$key.'\'');
			}
			$this->{$key} = $val;
		}
		
		if(!$this->type) {
			throw new Form_Definition_Exception('Field option definition: '.$field_class.'::'.$name.' - type is not specified');
		}
		if(!$this->label) {
			throw new Form_Definition_Exception('Field option definition: '.$field_class.'::'.$name.' - label is not specified');
		}
		if(!$this->getter) {
			throw new Form_Definition_Exception('Field option definition: '.$field_class.'::'.$name.' - getter is not specified');
		}
		if(!$this->setter) {
			throw new Form_Definition_Exception('Field option definition: '.$field_class.'::'.$name.' - setter is not specified');
		}
		
	}
	
	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}
	
	/**
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
	}
	
	/**
	 * @return string
	 */
	public function getLabel(): string
	{
		return $this->label;
	}
	
	/**
	 * @return string
	 */
	public function getGetter(): string
	{
		return $this->getter;
	}
	
	/**
	 * @return string
	 */
	public function getSetter(): string
	{
		return $this->setter;
	}
	
}