<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use DateTime;


class Validator_Time extends Validator
{
	public const ERROR_CODE_INVALID_FORMAT = 'invalid_format';
	
	protected static string $type = self::TYPE_TIME;
	
	protected static array $formats = [
		'H:i'   => 5,
		'H:i:s' => 8,
	];
	
	
	/**
	 * @var array<string,string>
	 */
	protected array $error_messages = [
		self::ERROR_CODE_EMPTY        => 'Missing value',
		self::ERROR_CODE_INVALID_FORMAT => 'Invalid value',
	];
	
	public function validate_value( mixed $value ) : bool
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
		
		$check = DateTime::createFromFormat( 'Y-m-d '.$format, '2011-01-01 ' . $value );
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