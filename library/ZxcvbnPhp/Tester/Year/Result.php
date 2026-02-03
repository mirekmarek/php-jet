<?php
/**
 * @copyright Benjamin Jeavons
 * @author Benjamin Jeavons
 * @author Miroslav Marek <mirek.marek@web-jet.cz> - PHP8 refactoring
 */

namespace ZxcvbnPhp;


class Tester_Year_Result extends Tester_Result
{
	
	public const NUM_YEARS = 229;
	
	public function __construct( string $password, int $begin, int $end, string $token )
	{
		parent::__construct( $password, $begin, $end, $token );
		$this->pattern = 'year';
	}
	
	public function getEntropy() : float
	{
		if( is_null( $this->entropy ) ) {
			$this->entropy = $this->log( self::NUM_YEARS );
		}
		return $this->entropy;
	}
}