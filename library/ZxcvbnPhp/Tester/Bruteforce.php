<?php
/**
 * @copyright Benjamin Jeavons
 * @author Benjamin Jeavons
 * @author Miroslav Marek <mirek.marek@web-jet.cz> - PHP8 refactoring
 */

namespace ZxcvbnPhp;

class Tester_Bruteforce extends Tester
{
	public static function test( string $password, array $user_data = [] ) : array
	{
		// Matches entire string.
		$match = new Tester_Bruteforce_Result( $password, 0, strlen( $password ) - 1, $password );
		return [$match];
	}
}