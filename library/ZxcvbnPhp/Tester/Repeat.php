<?php
/**
 * @copyright Benjamin Jeavons
 * @author Benjamin Jeavons
 * @author Miroslav Marek <mirek.marek@web-jet.cz> - PHP8 refactoring
 */

namespace ZxcvbnPhp;


class Tester_Repeat extends Tester
{
	
	public static function test( string $password, array $user_data = [] ) : array
	{
		$groups = static::group( $password );
		$matches = [];
		
		$k = 0;
		foreach( $groups as $group ) {
			$char = $group[0];
			$length = strlen( $group );
			
			if( $length > 2 ) {
				$end = $k + $length - 1;
				$token = substr( $password, $k, $end + 1 );
				$matches[] = new Tester_Repeat_Result( $password, $k, $end, $token, $char );
			}
			$k += $length;
		}
		return $matches;
	}
	
	protected static function group( string $string ) : array
	{
		$grouped = [];
		$chars = str_split( $string );
		
		$prevChar = null;
		$i = 0;
		foreach( $chars as $char ) {
			if( $prevChar === $char ) {
				$grouped[$i - 1] .= $char;
			} else {
				$grouped[$i] = $char;
				$i++;
				$prevChar = $char;
			}
		}
		return $grouped;
	}
}