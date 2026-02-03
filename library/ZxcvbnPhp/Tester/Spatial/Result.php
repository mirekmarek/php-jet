<?php
/**
 * @copyright Benjamin Jeavons
 * @author Benjamin Jeavons
 * @author Miroslav Marek <mirek.marek@web-jet.cz> - PHP8 refactoring
 */

namespace ZxcvbnPhp;

class Tester_Spatial_Result extends Tester_Result
{
	
	public string $graph;
	public ?int $shifted_count = null;
	public ?int $turns = null;
	
	protected ?int $keyboard_starting_pos = null;
	protected ?int $keypad_starting_pos = null;
	protected ?float $keyboard_avg_degree = null;
	protected ?float $keypad_avg_degree = null;
	
	public function __construct( string $password, int $begin, int $end, string $token, array $params = [] )
	{
		parent::__construct( $password, $begin, $end, $token );
		$this->pattern = 'spatial';
		
		$this->graph = $params['graph'];
		$this->shifted_count = $params['shifted_count'] ?? null;
		$this->turns = $params['turns'] ?? null;
		
		$this->keyboard_starting_pos = 94;
		$this->keypad_starting_pos = 15;
		$this->keyboard_avg_degree = 432 / 94;
		$this->keypad_avg_degree = 76 / 15;
	}
	
	public function getEntropy() : float
	{
		if( $this->graph === 'qwerty' || $this->graph === 'dvorak' ) {
			$starting_pos = $this->keyboard_starting_pos;
			$avg_degree = $this->keyboard_avg_degree;
		} else {
			$starting_pos = $this->keypad_starting_pos;
			$avg_degree = $this->keypad_avg_degree;
		}
		
		$possibilities = 0;
		for( $i = 2; $i <= strlen( $this->token ); $i++ ) {
			$possible_turns = min( $this->turns, $i - 1 );
			
			for( $j = 1; $j <= $possible_turns; $j++ ) {
				$possibilities += $this->binom( $i - 1, $j - 1 ) * $starting_pos * pow( $avg_degree, $j );
			}
		}
		$entropy = $this->log( $possibilities );
		
		if( !empty( $this->shifted_count ) ) {
			$possibilities = 0;
			$unshifted_count = strlen( $this->token ) - $this->shifted_count;
			$len = min( $this->shifted_count, $unshifted_count );
			
			for( $i = 0; $i <= $len; $i++ ) {
				$possibilities += $this->binom( $this->shifted_count + $unshifted_count, $i );
			}
			$entropy += $this->log( $possibilities );
		}
		return $entropy;
	}
	
}