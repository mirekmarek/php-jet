<?php
/**
 * @copyright Benjamin Jeavons
 * @author Benjamin Jeavons
 * @author Miroslav Marek <mirek.marek@web-jet.cz> - PHP8 refactoring
 */

namespace ZxcvbnPhp;

class Tester_Bruteforce_Result extends Tester_Result
{
	public function __construct( string $password, int $begin, int $end, string $token, ?string $cardinality = null )
	{
		parent::__construct( $password, $begin, $end, $token );
		$this->pattern = 'bruteforce';
		$this->cardinality = $cardinality;
	}
	
	public function getEntropy() : float
	{
		if( $this->entropy===null ) {
			$this->entropy = $this->log( pow( $this->getCardinality(), strlen( $this->token ) ) );
		}
		return $this->entropy;
	}
}