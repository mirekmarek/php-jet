<?php /** @noinspection SpellCheckingInspection */
/**
 * @copyright Benjamin Jeavons
 * @author Benjamin Jeavons
 * @author Miroslav Marek <mirek.marek@web-jet.cz> - PHP8 refactoring
 */

namespace ZxcvbnPhp;


abstract class Tester
{
	
	/**
	 * @param string $password
	 * @param array<string> $user_data
	 * @return array<Tester_Result>
	 */
	abstract public static function test( string $password, array $user_data = [] ): array;
	
	/**
	 * Find all occurrences of regular expression in a string.
	 *
	 * @param string $string
	 *   String to search.
	 * @param string $regex
	 *   Regular expression with captures.
	 * @return array
	 *   Array of capture groups. Captures in a group have named indexes: 'begin', 'end', 'token'.
	 *     e.g. fishfish /(fish)/
	 *     array(
	 *       array(
	 *         array('begin' => 0, 'end' => 3, 'token' => 'fish'),
	 *         array('begin' => 0, 'end' => 3, 'token' => 'fish')
	 *       ),
	 *       array(
	 *         array('begin' => 4, 'end' => 7, 'token' => 'fish'),
	 *         array('begin' => 4, 'end' => 7, 'token' => 'fish')
	 *       )
	 *     )
	 *
	 */
	protected static function findAll( string $string, string $regex ) : array
	{
		$count = preg_match_all( $regex, $string, $matches, PREG_SET_ORDER );
		if( !$count ) {
			return [];
		}
		
		$pos = 0;
		$groups = [];
		foreach( $matches as $group ) {
			$captureBegin = 0;
			$match = array_shift( $group );
			$matchBegin = strpos( $string, $match, $pos );
			$captures = [
				[
					'begin' => $matchBegin,
					'end'   => $matchBegin + strlen( $match ) - 1,
					'token' => $match,
				],
			];
			foreach( $group as $capture ) {
				$captureBegin = strpos( $match, $capture, $captureBegin );
				$captures[] = [
					'begin' => $matchBegin + $captureBegin,
					'end'   => $matchBegin + $captureBegin + strlen( $capture ) - 1,
					'token' => $capture,
				];
			}
			$groups[] = $captures;
			$pos += strlen( $match ) - 1;
		}
		return $groups;
	}
	
	
	/**
	 * @param string $password
	 * @param array<string> $user_data
	 * @return array<Tester_Result>
	 */
	public static function performTests( string $password, array $user_data = [] ) : array
	{
		$results = [];
		foreach( static::getTesters() as $Tester ) {
			$result = $Tester::test( $password, $user_data );
			if( $result ) {
				$results = array_merge( $results, $result );
			}
		}
		return $results;
	}
	
	/**
	 * @return array<string,Tester>
	 */
	protected static function getTesters()  :array
	{
		return [
			Tester_Date::class,
			Tester_Digit::class,
			Tester_L33t::class,
			Tester_Repeat::class,
			Tester_Sequence::class,
			Tester_Spatial::class,
			Tester_Year::class,
			Tester_Dictionary::class,
		];
	}
}