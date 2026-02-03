<?php
/**
 * @copyright Benjamin Jeavons
 * @author Benjamin Jeavons
 * @author Miroslav Marek <mirek.marek@web-jet.cz> - PHP8 refactoring
 */

namespace ZxcvbnPhp;


class Tester_Repeat_Result extends Tester_Result
{
	public ?string $repeated_char = null;
	
	
	public function __construct( string $password, int $begin, int $end, string $token, string $char )
	{
		parent::__construct( $password, $begin, $end, $token );
		$this->pattern = 'repeat';
		$this->repeated_char = $char;
	}
	
	public function getEntropy() : float
	{
		if( is_null( $this->entropy ) ) {
			$this->entropy = $this->log( $this->getCardinality() * strlen( $this->token ) );
		}
		return $this->entropy;
	}
	
}