<?php
/**
 * @copyright Benjamin Jeavons
 * @author Benjamin Jeavons
 * @author Miroslav Marek <mirek.marek@web-jet.cz> - PHP8 refactoring
 */

namespace ZxcvbnPhp;

class Tester_Sequence extends Tester
{
	
	public const LOWER = 'abcdefghijklmnopqrstuvwxyz';
	public const UPPER = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	public const DIGITS = '0123456789';
	
	public static function test( string $password, array $user_data = [] ) : array
	{
		$matches = [];
		$passwordLength = strlen( $password );
		
		$sequences = self::LOWER . self::UPPER . self::DIGITS;
		$revSequences = strrev( $sequences );
		
		for( $i = 0; $i < $passwordLength; $i++ ) {
			$pattern = false;
			$j = $i + 2;
			// Check for sequence sizes of 3 or more.
			if( $j < $passwordLength ) {
				$pattern = substr( $password, $i, 3 );
			}
			// Find beginning of pattern and then extract full sequences intersection.
			if( $pattern && ($pos = strpos( $sequences, $pattern )) !== false ) {
				// Match only remaining password characters.
				$remainder = substr( $password, $j + 1 );
				$pattern .= static::intersect( $sequences, $remainder, $pos + 3 );
				$params = [
					'ascending'     => true,
					'sequence_name'  => static::getSequenceName( $pos ),
					'sequence_space' => static::getSequenceSpace( $pos ),
				];
				$matches[] = new Tester_Sequence_Result( $password, $i, $i + strlen( $pattern ) - 1, $pattern, $params );
				// Skip intersecting characters on next loop.
				$i += strlen( $pattern ) - 1;
			} // Search the reverse sequence for pattern.
			elseif( $pattern && ($pos = strpos( $revSequences, $pattern )) !== false ) {
				$remainder = substr( $password, $j + 1 );
				$pattern .= static::intersect( $revSequences, $remainder, $pos + 3 );
				$params = [
					'ascending'     => false,
					'sequence_name'  => static::getSequenceName( $pos ),
					'sequence_space' => static::getSequenceSpace( $pos ),
				];
				$matches[] = new Tester_Sequence_Result( $password, $i, $i + strlen( $pattern ) - 1, $pattern, $params );
				$i += strlen( $pattern ) - 1;
			}
		}
		return $matches;
	}
	
	
	protected static function intersect( string $string, string $subString, int $start ) : string
	{
		$cut = str_split( substr( $string, $start, strlen( $subString ) ) );
		$comp = str_split( $subString );
		foreach( $cut as $i => $c ) {
			if( $comp[$i] === $c ) {
				$intersect[] = $c;
			} else {
				break; // Stop loop since intersection ends.
			}
		}
		if( !empty( $intersect ) ) {
			return implode( '', $intersect );
		}
		return '';
	}
	
	protected static function getSequenceSpace( $pos, bool $reverse = false ) : int
	{
		$name = static::getSequenceName( $pos, $reverse );
		return match ($name) {
			'lower' => strlen( self::LOWER ),
			'upper' => strlen( self::UPPER ),
			'digits' => strlen( self::DIGITS ),
			default => 0,
		};
		
	}
	
	protected static function getSequenceName( int $pos, bool $reverse = false ) : string
	{
		$sequences = self::LOWER . self::UPPER . self::DIGITS;
		$end = strlen( $sequences );
		if( !$reverse && $pos < strlen( self::LOWER ) ) {
			return 'lower';
		}
		
		if( !$reverse && $pos <= $end - strlen( self::DIGITS ) ) {
			return 'upper';
		}
		
		if( !$reverse ) {
			return 'digits';
		}
		
		if( $pos < strlen( self::DIGITS ) ) {
			return 'digits';
		}
		
		if( $pos <= $end - strlen( self::LOWER ) ) {
			return 'upper';
		}
		
		return 'lower';
	}
}