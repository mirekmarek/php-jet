<?php
/**
 * @copyright Benjamin Jeavons
 * @author Benjamin Jeavons
 * @author Miroslav Marek <mirek.marek@web-jet.cz> - PHP8 refactoring
 */

namespace ZxcvbnPhp;


abstract class Tester_Result
{
	public string $password;
	public int $begin;
	public int $end;
	public string $token;
	public string $pattern;
	public ?string $entropy;
	public ?string $cardinality;
	
	public function __construct( string $password, int $begin, int $end, string $token )
	{
		$this->password = $password;
		$this->begin = $begin;
		$this->end = $end;
		$this->token = $token;
		$this->entropy = null;
		$this->cardinality = null;
	}
	
	
	public function getCardinality() : int
	{
		if( !is_null( $this->cardinality ) ) {
			return $this->cardinality;
		}
		$lower = $upper = $digits = $symbols = $unicode = 0;
		
		// Use token instead of password to support bruteforce matches on sub-string
		// of password.
		$chars = str_split( $this->token );
		foreach( $chars as $char ) {
			$ord = ord( $char );
			
			if( $this->isDigit( $ord ) ) {
				$digits = 10;
			} elseif( $this->isUpper( $ord ) ) {
				$upper = 26;
			} elseif( $this->isLower( $ord ) ) {
				$lower = 26;
			} elseif( $this->isSymbol( $ord ) ) {
				$symbols = 33;
			} else {
				$unicode = 100;
			}
		}
		$this->cardinality = $lower + $digits + $upper + $symbols + $unicode;
		return $this->cardinality;
	}
	
	protected function isDigit( float $ord ) : bool
	{
		return $ord >= 0x30 && $ord <= 0x39;
	}
	
	protected function isUpper( float $ord ) : bool
	{
		return $ord >= 0x41 && $ord <= 0x5a;
	}
	
	protected function isLower( float $ord ) : bool
	{
		return $ord >= 0x61 && $ord <= 0x7a;
	}
	
	protected function isSymbol( float $ord ) : bool
	{
		return $ord <= 0x7f;
	}
	
	protected function log( float $number ) : float
	{
		return log( $number, 2 );
	}
	
	/**
	 * Calculate binomial coefficient (n choose k).
	 *
	 * http://www.php.net/manual/en/ref.math.php#57895
	 */
	protected function binom( int|float $n, int|float $k ) : int
	{
		$j = $res = 1;
		
		if( $k < 0 || $k > $n ) {
			return 0;
		}
		if( ($n - $k) < $k ) {
			$k = $n - $k;
		}
		while( $j <= $k ) {
			$res *= $n--;
			$res /= $j++;
		}
		
		return $res;
	}
}