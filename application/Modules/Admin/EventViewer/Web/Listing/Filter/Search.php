<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Admin\EventViewer\Web;

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