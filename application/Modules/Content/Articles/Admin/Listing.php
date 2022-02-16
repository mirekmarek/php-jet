<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Content\Articles\Admin;

use JetApplication\Content_Article;

use Jet\Data_Listing;
use Jet\Data_Listing_Filter_Search;
use Jet\DataModel_Fetch_Instances;

/**
 *
 */
class Listing extends Data_Listing
{

	/**
	 * @var array
	 */
	protected array $grid_columns = [
		'_edit_'    => [
			'title'         => '',
			'disallow_sort' => true
		],
		'title'     => [
			'title'         => 'Title',
			'disallow_sort' => true
		],
		'date_time' => ['title' => 'Date and time'],
	];

	/**
	 *
	 */
	protected function initFilters(): void
	{
		$this->filters['search'] = new class($this) extends Data_Listing_Filter_Search {
			public function generateWhere(): void
			{
				if( $this->search ) {
					$search = '%' . $this->search . '%';
					$this->listing->addWhere( [
						'article_localized.title *'      => $search,
						'OR',
						'article_localized.annotation *' => $search,
						'OR',
						'article_localized.text *'       => $search,
					] );
				}

			}
		};
	}


	/**
	 * @return Content_Article[]|DataModel_Fetch_Instances
	 * @noinspection PhpDocSignatureInspection
	 */
	protected function getList(): DataModel_Fetch_Instances
	{
		return Content_Article::getList();
	}

}