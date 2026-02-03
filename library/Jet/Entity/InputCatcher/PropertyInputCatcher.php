<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use ReflectionObject;

/**
 *
 */
class Entity_InputCatcher_PropertyInputCatcher extends BaseObject
{
	protected object $object;
	protected string $property_name;
	protected string $property_path;
	protected string $setter_method_name = '';
	protected InputCatcher $input_catcher;
	
	public function __construct( object $object, string $property_name, string $setter_method_name, InputCatcher $input_catcher )
	{
		$this->object = $object;
		$this->property_name = $property_name;
		$this->property_path = $property_name;
		$this->input_catcher = $input_catcher;
		$this->setter_method_name = $setter_method_name;
	}
	
	public function getObject(): object
	{
		return $this->object;
	}
	
	public function setObject( object $object ): void
	{
		$this->object = $object;
	}
	
	public function getPropertyName(): string
	{
		return $this->property_name;
	}
	
	public function getPropertyPath(): string
	{
		return $this->property_path;
	}
	
	public function setPropertyPath( string $property_path ): void
	{
		$this->property_path = $property_path;
	}
	
	public function getInputCatcher(): InputCatcher
	{
		return $this->input_catcher;
	}
	
	public function setInputCatcher( InputCatcher $input_catcher ): void
	{
		$this->input_catcher = $input_catcher;
	}
	
	public function getSetterMethodName(): string
	{
		return $this->setter_method_name;
	}
	
	public function setSetterMethodName( string $setter_method_name ): void
	{
		$this->setter_method_name = $setter_method_name;
	}
	
	
	
	public function catchInput( Data_Array $data ) : void
	{
		$catcher = $this->getInputCatcher();
		$catcher->setName( $this->getPropertyPath() );
		
		$catcher->catchInput( $data );
		$value = $catcher->getValue();
		
		if($this->setter_method_name) {
			$this->object->{$this->setter_method_name}( $value );
		} else {
			$r = new ReflectionObject( $this->object );
			$p = $r->getProperty( $this->getPropertyName() );
			
			if(PHP_VERSION_ID >= 80400) {
				/** @phpstan-ignore-next-line */
				$p->setRawValue( $this->object, $value );
			} else {
				if(PHP_VERSION_ID<80100) {
					$p->setAccessible(true);
				}
				$p->setValue( $this->object, $value );
			}
		}
		
	}
	

}