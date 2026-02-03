<?php
/**
 * @copyright Benjamin Jeavons
 * @author Benjamin Jeavons
 * @author Miroslav Marek <mirek.marek@web-jet.cz> - PHP8 refactoring
 */

namespace ZxcvbnPhp;

class Tester_Sequence_Result extends Tester_Result
{
	public ?string $sequence_name = null;
	public ?int $sequence_space = null;
	public ?bool $ascending = null;
	
	
	public function __construct( string $password, int $begin, int $end, string $token, array $params = [] )
	{
		parent::__construct( $password, $begin, $end, $token );
		$this->pattern = 'sequence';

		$this->sequence_name = $params['sequence_name'] ?? null;
		$this->sequence_space = $params['sequence_space'] ?? null;
		$this->ascending = $params['ascending'] ?? null;

	}
	
	public function getEntropy() : float
	{
		$char = $this->token[0];
		if( $char === 'a' || $char === '1' ) {
			$entropy = 1;
		} else {
			$ord = ord( $char );
			
			if( $this->isDigit( $ord ) ) {
				$entropy = $this->log( 10 );
			} elseif( $this->isLower( $ord ) ) {
				$entropy = $this->log( 26 );
			} else {
				$entropy = $this->log( 26 ) + 1;
			}
		}
		
		if( empty( $this->ascending ) ) {
			$entropy += 1;
		}
		
		return $entropy + $this->log( strlen( $this->token ) );
	}
	
}