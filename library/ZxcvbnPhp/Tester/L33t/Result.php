<?php
/**
 * @copyright Benjamin Jeavons
 * @author Benjamin Jeavons
 * @author Miroslav Marek <mirek.marek@web-jet.cz> - PHP8 refactoring
 */

namespace ZxcvbnPhp;

class Tester_L33t_Result extends Tester_Dictionary_Result
{
	public ?array $sub = null;
	public ?string $sub_display = null;
	
	
	public function __construct( string $password, int $begin, int $end, string $token, array $params = [] )
	{
		parent::__construct( $password, $begin, $end, $token, $params );
		
		$this->sub = $params['sub'] ?? null;
		$this->sub_display = $params['sub_display'] ?? null;
	}
	
	public function getEntropy() : float
	{
		return parent::getEntropy() + $this->l33tEntropy();
	}
	
	protected function l33tEntropy() : float
	{
		$possibilities = 0;
		foreach( $this->sub as $subbed => $unsubbed ) {
			$sLen = 0;
			$uLen = 0;
			// Count occurrences of substituted and unsubstituted characters in the token.
			foreach( str_split( $this->token ) as $char ) {
				if( $char === (string)$subbed ) {
					$sLen++;
				}
				if( $char === (string)$unsubbed ) {
					$uLen++;
				}
			}
			foreach( range( 0, min( $uLen, $sLen ) ) as $i ) {
				$possibilities += $this->binom( $uLen + $sLen, $i );
			}
		}
		
		if( $possibilities <= 1 ) {
			return 1;
		}
		return $this->log( $possibilities );
	}
	
	protected static function translate( string $string, array $map ) : string
	{
		$out = '';
		foreach( range( 0, strlen( $string ) - 1 ) as $i ) {
			$out .= !empty( $map[$i] ) ? $map[$i] : $string[$i];
		}
		return $out;
	}
	
}
