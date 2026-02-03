<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use ZxcvbnPhp\Zxcvbn;

class Validator_Password extends Validator
{
	protected static string $type = self::TYPE_PASSWORD;
	
	public const ERROR_CODE_CHECK_NOT_MATCH = 'check_not_match';
	public const ERROR_CODE_WEAK_PASSWORD = 'weak_password';
	
	#[Entity_Validator_Definition_ValidatorOption(
		type: Entity_Validator_Definition_ValidatorOption::TYPE_FLOAT,
		label: 'Minimal password check score',
		getter: 'getMinimalScore',
		setter: 'setMinimalScore',
	)]
	protected float $minimal_score = 3.0;
	
	public function getMinimalScore(): float
	{
		return $this->minimal_score;
	}
	
	public function setMinimalScore( float $minimal_score ): void
	{
		$this->minimal_score = $minimal_score;
	}
	
	
	
	public function validate_value( mixed $value ): bool
	{
		
		$score = Zxcvbn::passwordStrength( $value??'' )->getScore();
		
		if( $score < $this->getMinimalScore() ) {
			$this->setError(
				self::ERROR_CODE_WEAK_PASSWORD,
				[
					'score' => $score
				]
			);
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
		
		$codes[] = static::ERROR_CODE_WEAK_PASSWORD;
		
		return $codes;
	}
	
}