<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\Admin\EventViewer\Admin;

use Jet\DataListing_Filter_Search;

/**
 *
 */
class Listing_Filter_Search extends DataListing_Filter_Search {
	
	public const KEY = 'search';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function generateWhere(): void
	{
		if( $this->search ) {
			$search = '%'.$this->search.'%';
			$this->listing->addFilterWhere([
				'event *'        => $search,
				'OR',
				'event_class *' => $search,
				'OR',
				'event_message *' => $search,
			]);
		}
	}

}