<?php
/**
 * @copyright Benjamin Jeavons
 * @author Benjamin Jeavons
 * @author Miroslav Marek <mirek.marek@web-jet.cz> - PHP8 refactoring
 */

namespace ZxcvbnPhp;

class Zxcvbn
{
	public static function passwordStrength( string $password, array $user_data = [] ): Result
	{
		$time_start = microtime( true );
		if( strlen( $password ) === 0 ) {
			return new Result(
				password: $password,
				entropy: 0,
				test_results: [],
				score: 0,
				calc_duration: microtime( true ) - $time_start
			);
		}
		
		
		$results = Tester::performTests( $password, $user_data );
		
		
		
		$searcher = new Searcher();
		$entropy = $searcher->getMinimumEntropy( $password, $results );
		$bests_results = $searcher->getMatchSequence();
		
		$score = (new Scorer())->score( $entropy );
		
		
		return new Result(
			password: $password,
			entropy: $entropy,
			test_results: $bests_results,
			score: $score,
			calc_duration: microtime( true ) - $time_start
		);
	}

}