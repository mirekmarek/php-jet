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
	 * @return array
	 */
	public function getRequiredErrorCodes(): array
	{
		$codes = [];
		
		if($this->is_required) {
			$codes[] = Form_Field::ERROR_CODE_EMPTY;
		}
		
		if( $this->validation_regexp ) {
			$codes[] = Form_Field::ERROR_CODE_INVALID_FORMAT;
		}
		
		return $codes;
	}
	
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
	/**
	 *
	 * @return bool
	 */
	public function validate(): bool
	{
		if(!$this->validate_required()) {
			return false;
		}
		
		
		if(
			$this->validation_regexp &&
			$this->_value!==''
		) {
			
			if( $this->validation_regexp[0] != '/' ) {
				$res = preg_match( '/' . $this->validation_regexp . '/', $this->_value );
			} else {
				$res = preg_match( $this->validation_regexp, $this->_value );
			}
			
			if(!$res) {
				$this->setError( Form_Field::ERROR_CODE_INVALID_FORMAT );
				return false;
			}
		}
		
		
		
		if(!$this->validate_validator()) {
			return false;
		}
		
		$this->setIsValid();
		return true;
	}
}