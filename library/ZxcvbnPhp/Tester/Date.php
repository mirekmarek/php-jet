<?php
/**
 * @copyright Benjamin Jeavons
 * @author Benjamin Jeavons
 * @author Miroslav Marek <mirek.marek@web-jet.cz> - PHP8 refactoring
 */

namespace ZxcvbnPhp;

class Tester_Date extends Tester
{
	public const DATE_RX_YEAR_SUFFIX = '/(\d{1,2})(\s|-|\/|\\|_|\.)(\d{1,2})\2(19\d{2}|200\d|201\d|\d{2})/';
	public const DATE_RX_YEAR_PREFIX = '/(19\d{2}|200\d|201\d|\d{2})(\s|-|\/|\\|_|\.)(\d{1,2})\2(\d{1,2})/';
	
	
	public static function test( string $password, array $user_data = [] ) : array
	{
		$matches = [];
		$dates = static::datesWithoutSeparators( $password ) + static::datesWithSeparators( $password );
		foreach( $dates as $date ) {
			$matches[] = new Tester_Date_Result( $password, $date['begin'], $date['end'], $date['token'], $date );
		}
		return $matches;
	}
	
	
	protected static function datesWithSeparators( string $password ) : array
	{
		$dates = [];
		foreach( static::findAll( $password, static::DATE_RX_YEAR_SUFFIX ) as $captures ) {
			$date = [
				'day'   => (int)$captures[1]['token'],
				'month' => (int)$captures[3]['token'],
				'year'  => (int)$captures[4]['token'],
				'sep'   => $captures[2]['token'],
				'begin' => $captures[0]['begin'],
				'end'   => $captures[0]['end'],
			];
			$dates[] = $date;
		}
		foreach( static::findAll( $password, static::DATE_RX_YEAR_PREFIX ) as $captures ) {
			$date = [
				'day'   => (int)$captures[4]['token'],
				'month' => (int)$captures[3]['token'],
				'year'  => (int)$captures[1]['token'],
				'sep'   => $captures[2]['token'],
				'begin' => $captures[0]['begin'],
				'end'   => $captures[0]['end'],
			];
			$dates[] = $date;
		}
		
		$results = [];
		foreach( $dates as $candidate ) {
			$date = static::checkDate( $candidate['day'], $candidate['month'], $candidate['year'] );
			
			if( $date === false ) {
				continue;
			}
			[
				$day,
				$month,
				$year
			] = $date;
			
			$results[] = [
				'pattern'   => 'date',
				'begin'     => $candidate['begin'],
				'end'       => $candidate['end'],
				'token'     => substr( $password, $candidate['begin'], $candidate['begin'] + $candidate['end'] - 1 ),
				'separator' => $candidate['sep'],
				'day'       => $day,
				'month'     => $month,
				'year'      => $year
			];
		}
		
		return $results;
	}
	
	protected static function datesWithoutSeparators( string $password ) : array
	{
		$dateMatches = [];
		
		// 1197 is length-4, 01011997 is length 8
		foreach( static::findAll( $password, '/(\d{4,8})/' ) as $captures ) {
			$capture = $captures[1];
			$begin = $capture['begin'];
			$end = $capture['end'];
			
			$token = $capture['token'];
			$tokenLen = strlen( $token );
			
			// Create year candidates.
			$candidates1 = [];
			if( $tokenLen <= 6 ) {
				// 2 digit year prefix (990112)
				$candidates1[] = [
					'daymonth' => substr( $token, 2 ),
					'year'     => substr( $token, 0, 2 ),
					'begin'    => $begin,
					'end'      => $end
				];
				// 2 digit year suffix (011299)
				$candidates1[] = [
					'daymonth' => substr( $token, 0, ($tokenLen - 2) ),
					'year'     => substr( $token, -2 ),
					'begin'    => $begin,
					'end'      => $end
				];
			}
			if( $tokenLen >= 6 ) {
				// 4 digit year prefix (199912)
				$candidates1[] = [
					'daymonth' => substr( $token, 4 ),
					'year'     => substr( $token, 0, 4 ),
					'begin'    => $begin,
					'end'      => $end
				];
				// 4 digit year suffix (121999)
				$candidates1[] = [
					'daymonth' => substr( $token, 0, ($tokenLen - 4) ),
					'year'     => substr( $token, -4 ),
					'begin'    => $begin,
					'end'      => $end
				];
			}
			// Create day/month candidates from years.
			$candidates2 = [];
			foreach( $candidates1 as $candidate ) {
				switch( strlen( $candidate['daymonth'] ) ) {
					case 2: // ex. 1 1 97
						$candidates2[] = [
							'day'   => $candidate['daymonth'][0],
							'month' => $candidate['daymonth'][1],
							'year'  => $candidate['year'],
							'begin' => $candidate['begin'],
							'end'   => $candidate['end']
						];
						break;
					case 3: // ex. 11 1 97 or 1 11 97
						$candidates2[] = [
							'day'   => substr( $candidate['daymonth'], 0, 2 ),
							'month' => substr( $candidate['daymonth'], 2 ),
							'year'  => $candidate['year'],
							'begin' => $candidate['begin'],
							'end'   => $candidate['end']
						];
						$candidates2[] = [
							'day'   => substr( $candidate['daymonth'], 0, 1 ),
							'month' => substr( $candidate['daymonth'], 1, 3 ),
							'year'  => $candidate['year'],
							'begin' => $candidate['begin'],
							'end'   => $candidate['end']
						];
						break;
					case 4: // ex. 11 11 97
						$candidates2[] = [
							'day'   => substr( $candidate['daymonth'], 0, 2 ),
							'month' => substr( $candidate['daymonth'], 2, 4 ),
							'year'  => $candidate['year'],
							'begin' => $candidate['begin'],
							'end'   => $candidate['end']
						];
						break;
				}
			}
			// Reject invalid candidates
			foreach( $candidates2 as $candidate ) {
				$day = (int)$candidate['day'];
				$month = (int)$candidate['month'];
				$year = (int)$candidate['year'];
				
				$date = static::checkDate( $day, $month, $year );
				if( $date === false ) {
					continue;
				}
				[
					$day,
					$month,
					$year
				] = $date;
				
				$dateMatches[] = [
					'begin'     => $candidate['begin'],
					'end'       => $candidate['end'],
					'token'     => substr( $password, $begin, $begin + $end - 1 ),
					'separator' => '',
					'day'       => $day,
					'month'     => $month,
					'year'      => $year
				];
			}
		}
		return $dateMatches;
	}
	
	protected static function checkDate( int $day, int $month, int $year ) : array|false
	{
		// Tolerate both day-month and month-day order
		if( (12 <= $month && $month <= 31) && $day <= 12 ) {
			$m = $month;
			$month = $day;
			$day = $m;
		}
		if( $day > 31 || $month > 12 ) {
			return false;
		}
		if( !((1900 <= $year && $year <= 2229)) ) {
			return false;
		}
		return [
			$day,
			$month,
			$year
		];
	}
}