<?php
/**
 * @copyright Benjamin Jeavons
 * @author Benjamin Jeavons
 * @author Miroslav Marek <mirek.marek@web-jet.cz> - PHP8 refactoring
 */

namespace ZxcvbnPhp;

class Tester_Digit_Result extends Tester_Result
{
	public function __construct( string $password, int $begin, int $end, string $token )
	{
		parent::__construct( $password, $begin, $end, $token );
		$this->pattern = 'digit';
	}
	
	public function getEntropy() : float
	{
		if( is_null( $this->entropy ) ) {
			$this->entropy = $this->log( pow( 10, strlen( $this->token ) ) );
		}
		return $this->entropy;
	}
}