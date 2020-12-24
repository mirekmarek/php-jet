<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Content\Articles;

use Jet\Data_Listing;
use Jet\Data_Listing_Filter_search;

/**
 *
 */
class Article_AdminListing extends Data_Listing {

	use Data_Listing_Filter_search;

	/**
	 * @var array
	 */
	protected array $grid_columns = [
		'_edit_'     => [
			'title'         => '',
			'disallow_sort' => true
		],
		'title'     => [
			'title' => 'Title',
			'disallow_sort' => true
		],
		'date_time' => ['title' => 'Date and time'],
	];

	/**
	 * @var string[]
	 */
	protected array $filters = [
		'search'
	];

	/**
	 * @return Article[]
	 */
	protected function getList() : iterable
	{
		return Article::getList();
	}


	/**
	 *
	 */
	protected function filter_search_getWhere() : void
	{
		if(!$this->search) {
			return;
		}

		$search = '%'.$this->search.'%';
		$this->filter_addWhere([
			'article_localized.title *' => $search,
			'OR',
			'article_localized.annotation *' => $search,
			'OR',
			'article_localized.text *' => $search,
		]);

	}
}