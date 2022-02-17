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
class Form_Definition_SubForms extends BaseObject
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
			if($key=='is_sub_forms') {
				continue;
			}
			
			if(property_exists($this, $key)) {
				$this->{$key} = $value;
			} else {
				throw new Form_Definition_Exception('Form definition '.get_class($context_object).'::'.$property_name.' - unknown option \''.$key.'\'');
			}
		}
		
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
	 *
	 */
	public function createFormFields( string $parent_name, array &$form_fields ): void
	{
		if(!$this->property) {
			return;
		}
		
		if(!is_iterable($this->property)) {
			throw new Form_Definition_Exception('Form definition '.get_class($this->context_object).'::'.$this->property_name.' - is not iterable');
		}
		

		$sub_fields = [];
		foreach($this->property as $key=>$sub) {
			if(!($sub instanceof Form_Definition_Interface)) {
				throw new Form_Definition_Exception('Form definition '.get_class($this->context_object).'::'.$this->property_name.'['.$key.'] - is not sub form creator (interface Form_Definition_Interface is not implemented)');
			}
			
			$sub_form = $sub->createForm('');
			
			foreach($sub_form->getFields() as $field) {
				$name = $parent_name.'/'.$key;
				if( $field->getName()[0]!='/' ) {
					$name .= '/';
				}
				
				$field->setName( $name.$field->getName() );
				$sub_fields[] = $field;
			}
			
		}
		
		$creator = $this->getCreator();
		if($creator) {
			$sub_fields = $creator( $sub_fields );
		}
		
		foreach($sub_fields as $field) {
			$form_fields[] = $field;
		}
		
	}
	
}