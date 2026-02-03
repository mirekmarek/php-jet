<?php
/**
 * @copyright Benjamin Jeavons
 * @author Benjamin Jeavons
 * @author Miroslav Marek <mirek.marek@web-jet.cz> - PHP8 refactoring
 */

namespace ZxcvbnPhp;

class Tester_Dictionary extends Tester
{
	
	public static function test( string $password, array $user_data = [] ) : array
	{
		$matches = [];
		$dicts = static::getRankedDictionaries();
		
		if( $user_data ) {
			$dicts['user_inputs'] = [];
			foreach( $user_data as $rank => $input ) {
				$input_lower = strtolower( $input );
				$dicts['user_inputs'][$input_lower] = $rank;
			}
		}
		
		foreach( $dicts as $name => $dict ) {
			$results = static::dictionaryMatch( $password, $dict );
			foreach( $results as $result ) {
				$result['dictionary_name'] = $name;
				$matches[] = new Tester_Dictionary_Result( $password, $result['begin'], $result['end'], $result['token'], $result );
			}
		}
		return $matches;
	}
	
	
	protected static function dictionaryMatch( string $password, array $dict ) : array
	{
		$result = [];
		$length = strlen( $password );
		
		$pw_lower = strtolower( $password );
		
		foreach( range( 0, $length - 1 ) as $i ) {
			foreach( range( $i, $length - 1 ) as $j ) {
				$word = substr( $pw_lower, $i, $j - $i + 1 );
				
				if( isset( $dict[$word] ) ) {
					$result[] = [
						'begin' => $i,
						'end' => $j,
						'token' => substr( $password, $i, $j - $i + 1 ),
						'matched_word' => $word,
						'rank' => $dict[$word],
					];
				}
			}
		}
		
		return $result;
	}
	
	protected static function getRankedDictionaries() : array
	{
		$data = file_get_contents( __DIR__ . '/Dictionary/Data/ranked_frequency_lists.json' );
		return json_decode( $data, true );
	}
}