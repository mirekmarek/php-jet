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
class Entity_Validator_PropertyValidator extends BaseObject
{
	protected object $object;
	protected string $property_name;
	protected string $property_path;
	protected Validator $validator;
	protected bool $is_required;
	
	public function __construct( object $object, string $property_name, Validator $validator, bool $is_required )
	{
		$this->object = $object;
		$this->property_name = $property_name;
		$this->property_path = $property_name;
		$this->is_required = $is_required;
		$this->validator = $validator;
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
	
	public function getValidator(): Validator
	{
		return $this->validator;
	}
	
	public function setValidator( Validator $validator ): void
	{
		$this->validator = $validator;
	}
	
	public function getLastErrorCode(): string
	{
		return $this->getValidator()->getLastErrorCode();
	}
	
	public function getLastErrorMessage(): string
	{
		return $this->getValidator()->getLastErrorMessage();
	}
	
	/**
	 * @return array<string,mixed>
	 */
	public function getLastErrorData(): array
	{
		return $this->getValidator()->getLastErrorData();
	}
	
	
	/**
	 * @return array<Validator_ValidationError>
	 */
	public function getAllErrors() : array
	{
		return $this->getValidator()->getAllErrors();
	}
	
	public function validateValue( mixed $value ) : bool
	{
		return $this->getValidator()->validate( $value );
	}
	
	public function validate() : bool
	{
		$r = new ReflectionObject( $this->object );
		$p = $r->getProperty( $this->getPropertyName() );
		
		$value = '';
		if(PHP_VERSION_ID >= 80400) {
			/** @phpstan-ignore-next-line */
			$value = $p->getRawValue( $this->object );
		} else {
			if(PHP_VERSION_ID<80100) {
				$p->setAccessible(true);
			}
			$value = $p->getValue( $this->object );
		}
		
		return $this->validateValue( $value );
	}
}