<?php
/**
 * @copyright Benjamin Jeavons
 * @author Benjamin Jeavons
 * @author Miroslav Marek <mirek.marek@web-jet.cz> - PHP8 refactoring
 */

namespace ZxcvbnPhp;

class Tester_Dictionary_Result extends Tester_Result
{
	
	public ?string $dictionary_name = null;
	public ?float $rank = null;
	public ?string $matched_word = null;
	
	public function __construct( string $password, int $begin, int $end, string $token, array $params = [] )
	{
		parent::__construct( $password, $begin, $end, $token );
		$this->pattern = 'dictionary';
		
		$this->dictionary_name = $params['dictionary_name'] ?? null;
		$this->matched_word = $params['matched_word'] ?? null;
		$this->rank = $params['rank'] ?? null;
	}
	
	public function getEntropy() : float
	{
		return $this->log( $this->rank ) + $this->uppercaseEntropy();
	}
	
	protected function uppercaseEntropy() : float
	{
		$token = $this->token;
		// Return if token is all lowercase.
		if( $token === strtolower( $token ) ) {
			return 0;
		}
		
		$start_upper = '/^[A-Z][^A-Z]+$/';
		$end_upper = '/^[^A-Z]+[A-Z]$/';
		$all_upper = '/^[A-Z]+$/';
		
		foreach( [
			         $start_upper,
			         $end_upper,
			         $all_upper
		         ] as $regex ) {
			if( preg_match( $regex, $token ) ) {
				return 1;
			}
		}

		$u_len = 0;
		$l_len = 0;
		
		foreach( str_split( $token ) as $x ) {
			$ord = ord( $x );
			
			if( $this->isUpper( $ord ) ) {
				$u_len += 1;
			}
			if( $this->isLower( $ord ) ) {
				$l_len += 1;
			}
		}
		
		$possibilities = 0;
		foreach( range( 0, min( $u_len, $l_len ) + 1 ) as $i ) {
			$possibilities += $this->binom( $u_len + $l_len, $i );
		}
		
		return $this->log( $possibilities );
	}
}