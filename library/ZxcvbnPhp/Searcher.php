<?php
/**
 * @copyright Benjamin Jeavons
 * @author Benjamin Jeavons
 * @author Miroslav Marek <mirek.marek@web-jet.cz> - PHP8 refactoring
 */

namespace ZxcvbnPhp;

class Searcher
{
	/**
	 * @var array<Tester_Result>
	 */
	protected array $matchSequence;
	
	/**
	 * @param string $password
	 * @param array<Tester_Result> $matches
	 *
	 * @return float
	 */
	public function getMinimumEntropy( string $password, array $matches ) : float
	{
		$password_length = strlen( $password );
		$entropy_stack = [];
		// for the optimal sequence of matches up to k, holds the final match (match.end == k).
		// null means the sequence ends without a brute-force character.
		$backpointers = [];
		$bruteforce_match = new Tester_Bruteforce_Result( $password, 0, $password_length - 1, $password );
		$char_entropy = log( $bruteforce_match->getCardinality(), 2 );
		
		foreach( range( 0, $password_length - 1 ) as $k ) {
			// starting scenario to try and beat: adding a brute-force character to the minimum entropy sequence at k-1.
			$entropy_stack[$k] = $this->prevValue( $entropy_stack, $k ) + $char_entropy;
			$backpointers[$k] = null;
			foreach( $matches as $match ) {
				if( !isset( $match->begin ) || $match->end != $k ) {
					continue;
				}
				
				// See if entropy prior to match + entropy of this match is less than
				// the current minimum top of the stack.
				$candidateEntropy = $this->prevValue( $entropy_stack, $match->begin ) + $match->getEntropy();
				if( $candidateEntropy <= $entropy_stack[$k] ) {
					$entropy_stack[$k] = $candidateEntropy;
					$backpointers[$k] = $match;
				}
			}
		}
		
		// Walk backwards and decode the best sequence
		$matchSequence = [];
		$k = $password_length - 1;
		while( $k >= 0 ) {
			$match = $backpointers[$k];
			
			if( $match ) {
				$matchSequence[] = $match;
				
				$k = $match->begin - 1;
			} else {
				$k -= 1;
			}
		}
		$matchSequence = array_reverse( $matchSequence );
		
		$s = 0;
		$matchSequenceCopy = [];
		// Handle substrings that weren't matched as bruteforce match.
		foreach( $matchSequence as $match ) {
			if( $match->begin - $s > 0 ) {
				$matchSequenceCopy[] = $this->makeBruteforceMatch( $password, $s, $match->begin - 1, $bruteforce_match->getCardinality() );
			}
			
			$s = $match->end + 1;
			$matchSequenceCopy[] = $match;
		}
		
		if( $s < $password_length ) {
			$matchSequenceCopy[] = $this->makeBruteforceMatch( $password, $s, $password_length - 1, $bruteforce_match->getCardinality() );
		}
		
		$this->matchSequence = $matchSequenceCopy;
		
		return $entropy_stack[$password_length - 1];

	}
	
	protected function prevValue( array $array, int $index ) : mixed
	{
		$index = $index - 1;
		return ($index < 0 || $index >= count( $array )) ? 0 : $array[$index];
	}
	
	protected function makeBruteforceMatch( string $password, int $begin, int $end, ?int $cardinality = null ) : Tester_Bruteforce_Result
	{
		$match = new Tester_Bruteforce_Result( $password, $begin, $end, substr( $password, $begin, $end + 1 ), $cardinality );
		$match->getEntropy();
		
		return $match;
	}
	
	/**
	 * @return array<Tester_Result>
	 */
	public function getMatchSequence(): array
	{
		return $this->matchSequence;
	}
	
	
}