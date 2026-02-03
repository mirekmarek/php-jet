<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

class Validator_Int extends Validator
{
	public const ERROR_CODE_OUT_OF_RANGE = 'out_of_range';
	
	protected static string $type = self::TYPE_INT;
	
	#[Entity_Validator_Definition_ValidatorOption(
		type: Entity_Validator_Definition_ValidatorOption::TYPE_INT,
		label: 'Minimal value',
		getter: 'getMinValue',
		setter: 'setMinValue',
	)]
	protected ?int $min_value = null;
	
	#[Entity_Validator_Definition_ValidatorOption(
		type: Entity_Validator_Definition_ValidatorOption::TYPE_INT,
		label: 'Maximal value',
		getter: 'getMaxValue',
		setter: 'setMaxValue',
	)]
	protected ?int $max_value = null;
	
	protected array $error_messages = [
		self::ERROR_CODE_EMPTY        => 'Missing value',
		self::ERROR_CODE_OUT_OF_RANGE => 'Out of range. Min: %min%, Max: %max%',
	];
	
	
	public function getMinValue(): ?int
	{
		return $this->min_value;
	}
	
	public function setMinValue( ?int $min ) : void
	{
		$this->min_value = $min;
	}
	
	public function getMaxValue(): ?int
	{
		return $this->max_value;
	}
	
	public function setMaxValue( ?int $max ) : void
	{
		$this->max_value = $max;
	}
	
	
	public function validate_value( mixed $value ) : bool
	{
		if(
			$this->min_value !== null &&
			$value < $this->min_value
		) {
			$this->setError( self::ERROR_CODE_OUT_OF_RANGE );
			
			return false;
		}
		
		if(
			$this->max_value !== null &&
			$value > $this->max_value
		) {
			$this->setError( self::ERROR_CODE_OUT_OF_RANGE );
			
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
		
		if(
			$this->min_value !== null ||
			$this->max_value !== null
		) {
			$codes[] = static::ERROR_CODE_OUT_OF_RANGE;
		}
		
		return $codes;
	}
	
}