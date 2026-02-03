<?php
/**
 * @copyright Benjamin Jeavons
 * @author Benjamin Jeavons
 * @author Miroslav Marek <mirek.marek@web-jet.cz> - PHP8 refactoring
 */

namespace ZxcvbnPhp;

class Tester_L33t extends Tester_Dictionary
{
	
	public static function test( string $password, array $user_data = [] )  :array
	{
		$map = static::getSubstitutions( $password );
		$indexSubs = array_filter( $map );
		if( empty( $indexSubs ) ) {
			return [];
		}
		$translatedWord = static::translate( $password, $map );
		
		$matches = [];
		$dicts = static::getRankedDictionaries();
		foreach( $dicts as $name => $dict ) {
			$results = static::dictionaryMatch( $translatedWord, $dict );
			foreach( $results as $result ) {
				// Set substituted elements.
				$result['sub'] = [];
				$result['sub_display'] = [];
				foreach( $indexSubs as $i => $t ) {
					$result['sub'][$password[$i]] = $t;
					$result['sub_display'][] = "$password[$i] -> $t";
				}
				$result['sub_display'] = implode( ', ', $result['sub_display'] );
				$result['dictionary_name'] = $name;
				// Replace translated token with original password token.
				$token = substr( $password, $result['begin'], $result['end'] - $result['begin'] + 1 );
				$matches[] = new Tester_L33t_Result( $password, $result['begin'], $result['end'], $token, $result );
			}
		}
		return $matches;
	}
	
	protected static function translate( string $string, array $map ) : string
	{
		$out = '';
		foreach( range( 0, strlen( $string ) - 1 ) as $i ) {
			$out .= !empty( $map[$i] ) ? $map[$i] : $string[$i];
		}
		return $out;
	}
	
	protected static function getSubstitutions( string $password ) : array
	{
		$map = [];
		
		$l33t = [
			'a' => [
				'4',
				'@'
			],
			'b' => ['8'],
			'c' => [
				'(',
				'{',
				'[',
				'<'
			],
			'e' => ['3'],
			'g' => [
				'6',
				'9'
			],
			'i' => [
				'1',
				'!',
				'|'
			],
			'l' => [
				'1',
				'|',
				'7'
			],
			'o' => ['0'],
			's' => [
				'$',
				'5'
			],
			't' => [
				'+',
				'7'
			],
			'x' => ['%'],
			'z' => ['2'],
		];
		// Simplified l33t table to reduce duplicates.
		$l33t = [
			'a' => [
				'4',
				'@'
			],
			'b' => ['8'],
			'c' => [
				'(',
				'{',
				'[',
				'<'
			],
			'e' => ['3'],
			'g' => [
				'6',
				'9'
			],
			'i' => [
				'1',
				'!'
			],
			'l' => [
				'|',
				'7'
			],
			'o' => ['0'],
			's' => [
				'$',
				'5'
			],
			't' => [
				'+',
				'7'
			],
			'x' => ['%'],
			'z' => ['2'],
		];
		
		foreach( range( 0, strlen( $password ) - 1 ) as $i ) {
			$map[$i] = null;
			foreach( $l33t as $char => $subs ) {
				if( in_array( $password[$i], $subs ) ) {
					$map[$i] = $char;
				}
			}
		}
		
		return $map;
	}
}
