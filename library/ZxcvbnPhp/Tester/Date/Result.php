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
	
	/**
	 * @param string $password
	 * @param int $begin
	 * @param int $end
	 * @param string $token
	 * @param array<string,string|int|float|bool> $params
	 */
	public function __construct( string $password, int $begin, int $end, string $token, array $params )
	{
		parent::__construct( $password, $begin, $end, $token );
		$this->pattern = 'date';
		if(isset($params['day'])) {
			$this->day = (int)$params['day'];
		}
		if(isset($params['month'])) {
			$this->month = (int)$params['month'];
		}
		if(isset($params['year'])) {
			$this->year = (int)$params['year'];
		}
		if(isset($params['separator'])) {
			$this->separator = (string)$params['separator'];
		}
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