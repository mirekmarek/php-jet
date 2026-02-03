<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

class Validator_Week extends Validator
{
	public const ERROR_CODE_INVALID_FORMAT = 'invalid_format';
	
	protected static string $type = self::TYPE_WEEK;
	
	/**
	 * @var array<string,string>
	 */
	protected array $error_messages = [
		self::ERROR_CODE_EMPTY        => 'Missing value',
		self::ERROR_CODE_INVALID_FORMAT => 'Invalid value',
	];
	
	public function validate_value( mixed $value ) : bool
	{
		if( $value ) {
			if( !preg_match( '/^[0-9]+-W[0-9]{1,2}$/i', $value ) ) {
				$this->setError( self::ERROR_CODE_INVALID_FORMAT );
				
				return false;
			}
		}
		
		return true;
	}
	
	
	public function getErrorCodeScope(): array
	{
		$codes = [];
		
		if( $this->is_required ) {
			$codes[] = static::ERROR_CODE_EMPTY;
		}
		$codes[] = static::ERROR_CODE_INVALID_FORMAT;
		
		return $codes;
	}
	
}