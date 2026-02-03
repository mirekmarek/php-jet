<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

class Validator_Options extends Validator implements Validator_Part_Options_Interface
{
	use Validator_Part_Options_Trait;
	
	protected static string $type = self::TYPE_OPTIONS;
	
	protected array $error_messages = [
		self::ERROR_CODE_EMPTY        => 'Missing value',
		self::ERROR_CODE_INVALID_VALUE => 'Invalid value',
	];
	
	
	
	protected function validate_required( mixed $value ): bool
	{
		if( !$value ) {
			$this->setError( self::ERROR_CODE_EMPTY );
			
			return false;
		}
		
		return true;
	}
	
	public function validate_value( mixed $value ) : bool
	{
		$options = $this->getValidOptions();
		
		foreach( $value as $item ) {
			if( !in_array($item, $options) ) {
				$this->setError( self::ERROR_CODE_INVALID_VALUE );
				
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
		$codes[] = static::ERROR_CODE_INVALID_VALUE;
		
		return $codes;
	}
	
}