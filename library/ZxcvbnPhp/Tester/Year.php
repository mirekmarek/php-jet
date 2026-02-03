<?php
/**
 * @copyright Benjamin Jeavons
 * @author Benjamin Jeavons
 * @author Miroslav Marek <mirek.marek@web-jet.cz> - PHP8 refactoring
 */

namespace ZxcvbnPhp;

class Tester_Year extends Tester
{
	
	public static function test( string $password, array $user_data = [] ) : array
	{
		$matches = [];
		$groups = static::findAll( $password, '/(19\d\d|200\d|201\d)/' );
		foreach( $groups as $captures ) {
			$matches[] = new Tester_Year_Result( $password, $captures[1]['begin'], $captures[1]['end'], $captures[1]['token'] );
		}
		return $matches;
	}
	
}