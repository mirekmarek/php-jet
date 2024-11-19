<?php
/**
 *
 * @copyright %<COPYRIGHT>%
 * @license  %<LICENSE>%
 * @author  %<AUTHOR>%
 */
namespace %<NAMESPACE>%;

use Jet\DataListing_Filter_Search;

class Listing_Filter_Search extends DataListing_Filter_Search {
	
	public const KEY = 'search';
	
	
	public function getKey(): string
	{
		return static::KEY;
	}

	public function generateWhere(): void
	{
		if( $this->search ) {
			$search = '%' . $this->search . '%';
			$this->listing->addFilterWhere( [
				'%<ID_PROPERTY>% *'   => $search,
				'OR',
				'%<NAME_PROPERTY>% *'   => $search,
			] );
		}
	}
}