<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

class Validator_Option extends Validator
{
	public const ERROR_CODE_INVALID_VALUE = 'invalid_value';
	
	
	protected static string $type = self::TYPE_OPTION;
	
	protected array $error_messages = [
		self::ERROR_CODE_EMPTY        => 'Missing value',
		self::ERROR_CODE_INVALID_VALUE => 'Invalid value',
	];
	
	/**
	 * @var array<string|int|float>
	 */
	protected array $valid_options = [];
	
	/**
	 * @return array<string|int|float>
	 */
	public function getValidOptions(): array
	{
		return $this->valid_options;
	}
	
	/**
	 * @param array<string|int|float> $valid_options
	 * @return void
	 */
	public function setValidOptions( array $valid_options ): void
	{
		$this->valid_options = $valid_options;
	}
	
	
	public function validate_required( mixed $value ): bool
	{
		if(
			$value === '' &&
			in_array('', $this->getValidOptions())
		) {
			$this->setError( self::ERROR_CODE_EMPTY );
			
			return false;
		}
		
		return true;
	}
	
	public function validate_value( mixed $value ): bool
	{
		if( !in_array($value, $this->getValidOptions()) ) {
			$this->setError( self::ERROR_CODE_INVALID_VALUE );
			
			return false;
		}
		
		return true;
	}
	
	
	public function getErrorCodeScope(): array
	{
		$codes = [];
		
		if( $this->is_required ) {
			$codes[] = static::ERROR_CODE_EMPTY;
		}
		$codes[] = static::ERROR_CODE_INVALID_VALUE;
		
		return $codes;
	}
	
}