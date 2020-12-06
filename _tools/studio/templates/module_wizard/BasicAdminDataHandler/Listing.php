<?php
/**
 *
 * @copyright %<COPYRIGHT>%
 * @license  %<LICENSE>%
 * @author  %<AUTHOR>%
 */
namespace %<NAMESPACE>%;

use %<DATA_MODEL_CLASS_NAME>% as %<DATA_MODEL_CLASS_ALIAS>%;

use Jet\Data_Listing;
use Jet\Data_Listing_Filter_search;
use Jet\DataModel_Fetch_Instances;

/**
 *
 */
class Listing extends Data_Listing {

	use Data_Listing_Filter_search;

	/**
	 * @var array
	 */
	protected $grid_columns = [
		'_edit_'     => [
			'title'         => '',
			'disallow_sort' => true
		],
		'%<ID_PROPERTY>%'         => ['title' => '%<TXT_LISTING_TITLE_ID>%'],
		'%<NAME_PROPERTY>%'   => ['title' => '%<TXT_LISTING_TITLE_NAME>%'],
	];

	/**
	 * @var string[]
	 */
	protected $filters = [
		'search',
	];

	/**
	 * @var int
	 */
	protected $role = 0;

	/**
	 * @return DataModel_Fetch_Instances
	 */
	protected function getList()
	{
		return %<DATA_MODEL_CLASS_ALIAS>%::getList();
	}

	/**
	 *
	 */
	protected function filter_search_getWhere()
	{
		if(!$this->search) {
			return;
		}

		$search = '%'.$this->search.'%';
		$this->filter_addWhere([
			'%<NAME_PROPERTY>% *'   => $search,
		]);

	}
}