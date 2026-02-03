<?php
/**
 * @copyright Benjamin Jeavons
 * @author Benjamin Jeavons
 * @author Miroslav Marek <mirek.marek@web-jet.cz> - PHP8 refactoring
 */

namespace ZxcvbnPhp;

class Tester_Spatial extends Tester
{
	public static function test( string $password, array $user_data = [] ) : array
	{
		
		$matches = [];
		$graphs = static::getAdjacencyGraphs();
		foreach( $graphs as $name => $graph ) {
			$results = static::graphMatch( $password, $graph );
			foreach( $results as $result ) {
				$result['graph'] = $name;
				$matches[] = new Tester_Spatial_Result( $password, $result['begin'], $result['end'], $result['token'], $result );
			}
		}
		return $matches;
	}
	
	protected static function graphMatch( string $password, array $graph ) : array
	{
		$result = [];
		$i = 0;
		
		$passwordLength = strlen( $password );
		
		while( $i < $passwordLength - 1 ) {
			$j = $i + 1;
			$lastDirection = null;
			$turns = 0;
			$shiftedCount = 0;
			
			while( true ) {
				$prevChar = $password[$j - 1];
				$found = false;
				$curDirection = -1;
				$adjacents = $graph[$prevChar] ?? [];
				// Consider growing pattern by one character if j hasn't gone over the edge.
				if( $j < $passwordLength ) {
					$curChar = $password[$j];
					foreach( $adjacents as $adj ) {
						$curDirection += 1;
						$curCharPos = static::indexOf( $adj, $curChar );
						if( $adj && $curCharPos !== -1 ) {
							$found = true;
							$foundDirection = $curDirection;
							
							if( $curCharPos === 1 ) {
								// index 1 in the adjacency means the key is shifted, 0 means unshifted: A vs a, % vs 5, etc.
								// for example, 'q' is adjacent to the entry '2@'. @ is shifted w/ index 1, 2 is unshifted.
								$shiftedCount += 1;
							}
							if( $lastDirection !== $foundDirection ) {
								// adding a turn is correct even in the initial case when last_direction is null:
								// every spatial pattern starts with a turn.
								$turns += 1;
								$lastDirection = $foundDirection;
							}
							
							break;
						}
					}
				}
				
				// if the current pattern continued, extend j and try to grow again
				if( $found ) {
					$j += 1;
				} // otherwise push the pattern discovered so far, if any...
				else {
					// Ignore length 1 or 2 chains.
					if( $j - $i > 2 ) {
						$result[] = [
							'begin'         => $i,
							'end'           => $j - 1,
							'token'         => substr( $password, $i, $j - $i ),
							'turns'         => $turns,
							'shifted_count' => $shiftedCount
						];
					}
					// ...and then start a new search for the rest of the password.
					$i = $j;
					break;
				}
			}
		}
		
		return $result;
	}
	
	protected static function indexOf( ?string $string, string $char ) : int
	{
		if(
			!$string ||
			!str_contains($string, $char)
		) {
			return -1;
		}
		return strpos( $string, $char );
	}
	
	protected static function calcAverageDegree( array $graph ) : float
	{
		$sum = 0;
		foreach( $graph as $neighbors ) {
			foreach( $neighbors as $neighbor ) {
				// Ignore empty neighbors.
				if( !is_null( $neighbor ) ) {
					$sum++;
				}
			}
		}
		return $sum / count( array_keys( $graph ) );
	}
	
	protected static function getAdjacencyGraphs() : array
	{
		$data = file_get_contents( __DIR__ . '/Spatial/Data/adjacency_graphs.json' );
		return json_decode( $data, true );
	}
}