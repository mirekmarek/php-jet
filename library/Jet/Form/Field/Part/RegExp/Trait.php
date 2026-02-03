<?php
/**
 *
 * @copyright Copyright (c) 2011-2022 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
trait Form_Field_Part_RegExp_Trait
{
	
	#[Form_Definition_FieldOption(
		type: Form_Definition_FieldOption::TYPE_STRING,
		label: 'Validation regular expression',
		getter: 'getValidationRegexp',
		setter: 'setValidationRegexp',
	)]
	protected string $validation_regexp = '';
	
	/**
	 *
	 * @param bool $raw
	 *
	 * @return string
	 */
	public function getValidationRegexp( bool $raw = false ): string
	{
		if( $raw ) {
			return $this->validation_regexp;
		}
		
		$regexp = $this->validation_regexp;
		
		if(
			isset( $regexp[0] ) &&
			$regexp[0] == '/'
		) {
			$regexp = substr( $regexp, 1 );
			$regexp = substr( $regexp, 0, strrpos( $regexp, '/' ) );
		}
		
		return $regexp;
	}
	
	/**
	 *
	 * @param string $validation_regexp
	 */
	public function setValidationRegexp( string $validation_regexp ): void
	{
		$this->validation_regexp = $validation_regexp;
	}
	
	public function getValidator() : Validator
	{
		if(!$this->validator) {
			$this->validator = $this->validatorFactory();
		}
		
		/**
		 * @var Validator_RegExp $validator
		 */
		$validator = $this->validator;
		if(method_exists($validator, 'setValidationRegexp')) {
			$validator->setValidationRegexp( $this->getValidationRegexp() );
		}
		
		return $validator;
	}
}