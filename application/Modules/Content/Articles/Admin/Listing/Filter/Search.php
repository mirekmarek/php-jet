<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Content\Articles\Admin;

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
				'article_localized.title *'      => $search,
				'OR',
				'article_localized.annotation *' => $search,
				'OR',
				'article_localized.text *'       => $search,
			] );
		}
	}
}