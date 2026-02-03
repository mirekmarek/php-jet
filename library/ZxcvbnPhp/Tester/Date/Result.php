<?php
/**
 * @copyright Benjamin Jeavons
 * @author Benjamin Jeavons
 * @author Miroslav Marek <mirek.marek@web-jet.cz> - PHP8 refactoring
 */

namespace ZxcvbnPhp;

class Tester_Date_Result extends Tester_Result
{
	public const NUM_YEARS = 229;
	public const NUM_MONTHS = 12;
	public const NUM_DAYS = 31;
	
	public ?int $day = null;
	public ?int $month = null;
	public ?int $year = null;
	public ?string $separator = null;
	
	public function __construct( string $password, int $begin, int $end, string $token, array $params )
	{
		parent::__construct( $password, $begin, $end, $token );
		$this->pattern = 'date';
		$this->day = $params['day']??null;
		$this->month = $params['month']??null;
		$this->year = $params['year']??null;
		$this->separator = $params['separator']??null;
	}
	
	public function getEntropy() : float
	{
		if( $this->year < 100 ) {
			$entropy = $this->log( self::NUM_DAYS * self::NUM_MONTHS * 100 );
		} else {
			$entropy = $this->log( self::NUM_DAYS * self::NUM_MONTHS * self::NUM_YEARS );
		}
		if( !empty( $this->separator ) ) {
			$entropy += 2;
		}
		
		return $entropy;
	}
	
}