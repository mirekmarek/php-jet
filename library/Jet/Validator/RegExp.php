<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

class Validator_RegExp extends Validator
{
	public const ERROR_CODE_INVALID_FORMAT = 'invalid_format';
	
	protected static string $type = self::TYPE_REGEXP;
	
	#[Entity_Validator_Definition_ValidatorOption(
		type: Entity_Validator_Definition_ValidatorOption::TYPE_STRING,
		label: 'Validation RegExp',
		getter: 'getValidationRegexp',
		setter: 'setValidationRegexp'
	)]
	protected string $validation_regexp = '';
	
	protected array $error_messages = [
		self::ERROR_CODE_EMPTY        => 'Missing value',
		self::ERROR_CODE_INVALID_FORMAT => 'Invalid value',
	];
	
	
	
	public function getErrorCodeScope(): array
	{
		$codes = [];
		
		if( $this->is_required ) {
			$codes[] = static::ERROR_CODE_EMPTY;
		}
		if( $this->validation_regexp ) {
			$codes[] = static::ERROR_CODE_INVALID_FORMAT;
		}
		
		return $codes;
	}
	
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
	
	public function setValidationRegexp( string $validation_regexp ): void
	{
		$this->validation_regexp = $validation_regexp;
	}
	
	public function validate_value( mixed $value ): bool
	{
		if(
			$this->validation_regexp &&
			$value!==''
		) {
			if( $this->validation_regexp[0] != '/' ) {
				$res = preg_match( '/' . $this->validation_regexp . '/', $value );
			} else {
				$res = preg_match( $this->validation_regexp, $value );
			}
			
			if(!$res) {
				$this->setError( self::ERROR_CODE_INVALID_FORMAT );
				return false;
			}
		}
		
		return true;
	}
	
}