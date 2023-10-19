<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\Admin\EventViewer\Admin;

use Jet\DataListing_Filter_DateTimeInterval;

/**
 *
 */
class Listing_Filter_DateTime extends DataListing_Filter_DateTimeInterval {
	
	public const KEY = 'date_time';
	
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function generateWhere(): void
	{
		if( $this->date_time_from ) {
			$this->listing->addFilterWhere( [
				'date_time >=' => $this->date_time_from,
			] );
		}
		
		if( $this->date_time_till ) {
			$this->listing->addFilterWhere( [
				'date_time <=' => $this->date_time_till,
			] );
		}
	}
	
}