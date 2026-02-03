<?php
/**
 * @copyright Benjamin Jeavons
 * @author Benjamin Jeavons
 * @author Miroslav Marek <mirek.marek@web-jet.cz> - PHP8 refactoring
 */

namespace ZxcvbnPhp;

class Scorer
{
	
	public const SINGLE_GUESS = 0.010; // Lower bound assumption of time to hash based on bcrypt/scrypt/PBKDF2.
	public const NUM_ATTACKERS = 100; // Assumed number of cores guessing in parallel.
	
	public function score( $entropy ) : float
	{
		$seconds = (0.5 * pow( 2, $entropy )) * (Scorer::SINGLE_GUESS / Scorer::NUM_ATTACKERS);
		
		if( $seconds < pow( 10, 2 ) ) {
			return 0;
		}
		if( $seconds < pow( 10, 4 ) ) {
			return 1;
		}
		if( $seconds < pow( 10, 6 ) ) {
			return 2;
		}
		if( $seconds < pow( 10, 8 ) ) {
			return 3;
		}
		return 4;
	}
}