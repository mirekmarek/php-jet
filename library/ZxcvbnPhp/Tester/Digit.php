<?php
/**
 * @copyright Benjamin Jeavons
 * @author Benjamin Jeavons
 * @author Miroslav Marek <mirek.marek@web-jet.cz> - PHP8 refactoring
 */

namespace ZxcvbnPhp;

class Tester_Digit extends Tester
{
	public static function test( string $password, array $user_data = [] ) : array
	{
		$matches = [];
		$groups = static::findAll( $password, '/(\d{3,})/' );
		foreach( $groups as $captures ) {
			$matches[] = new Tester_Digit_Result( $password, $captures[1]['begin'], $captures[1]['end'], $captures[1]['token'] );
		}
		return $matches;
	}

}