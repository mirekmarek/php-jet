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
class Entity_Validator extends BaseObject
{
	protected object $object;
	
	/**
	 * @var array<string,Entity_Validator_PropertyValidator>
	 */
	protected array $validators = [];
	
	/**
	 * @param object $object
	 * @param array<string,Entity_Validator_PropertyValidator> $validators
	 */
	public function __construct( object $object, array $validators )
	{
		$this->object = $object;
		$this->validators = $validators;
	}
	
	
	/**
	 * @return array<string,Entity_Validator_PropertyValidator>
	 */
	public function getValidators(): array
	{
		return $this->validators;
	}
	
	public function validate() : bool
	{
		$is_valid = true;
		
		foreach($this->getValidators() as $validator) {
			if(!$validator->validate()) {
				$is_valid = false;
			}
		}
		
		return $is_valid;
	}
	
	/**
	 * @return array<string,array<Validator_ValidationError>>
	 */
	public function getErrors() : array
	{
		$errors = [];
		
		foreach($this->getValidators() as $validator) {
			$errors[$validator->getPropertyPath()] = $validator->getAllErrors();
		}
		
		return $errors;
	}
}