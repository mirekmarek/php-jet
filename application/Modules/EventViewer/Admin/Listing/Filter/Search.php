<?php
/**
 *
 * @copyright
 * @license
 * @author  Miroslav Marek
 */
namespace JetApplicationModule\EventViewer\Admin;

use Jet\Data_Listing_Filter_Search;

/**
 *
 */
class Listing_Filter_Search extends Data_Listing_Filter_Search {

	public function generateWhere(): void
	{
		if( $this->search ) {
			$search = '%'.$this->search.'%';
			$this->listing->addWhere([
				'event *'        => $search,
				'OR',
				'event_class *' => $search,
				'OR',
				'event_message *' => $search,
			]);
		}
	}

}