<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use DateTime;

class Validator_DateTime extends Validator
{

	protected static string $type = self::TYPE_DATE_TIME;
	
	public const ERROR_CODE_INVALID_FORMAT = 'invalid_format';
	
	protected array $error_messages = [
		self::ERROR_CODE_EMPTY        => 'Missing value',
		self::ERROR_CODE_INVALID_FORMAT => 'Invalid value',
	];
	
	protected static array $formats = [
		'Y-m-d\TH:i'   => 16,
		'Y-m-d\TH:i:s' => 19,
		'Y-m-d H:i'    => 16,
		'Y-m-d H:i:s'  => 19,
	];
	
	
	public function validate_value( mixed $value ): bool
	{
		if(!$value) {
			return true;
		}
		
		foreach(static::$formats as $format=>$max_len) {
			$res = $this->_validate_value( $value, $format, $max_len );
			if($res===true) {
				return true;
			}
		}
		
		$this->setError( self::ERROR_CODE_INVALID_FORMAT );
		return false;
	}
	
	protected function _validate_value( mixed $value, string $format, int $str_len ) : ?bool
	{
		if(strlen($value)!=$str_len) {
			return null;
		}
		
		$check = DateTime::createFromFormat( $format, $value );
		if(!$check) {
			return false;
		}
		
		if($check->format($format)!=$value) {
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
		$codes[] = static::ERROR_CODE_INVALID_FORMAT;
		
		return $codes;
	}

}