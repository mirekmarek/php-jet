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
use Jet\DataModel_Fetch_Instances;

/**
 *
 */
class Listing extends Data_Listing {


	/**
	 * @var array
	 */
	protected array $grid_columns = [
		'_edit_'     => [
			'title'         => '',
			'disallow_sort' => true
		],
		'%<ID_PROPERTY>%'         => ['title' => '%<TXT_LISTING_TITLE_ID>%'],
		'%<NAME_PROPERTY>%'   => ['title' => '%<TXT_LISTING_TITLE_NAME>%'],
	];

	/**
	 *
	 */
	protected function initFilters(): void
	{
		$this->filters['search'] = new Listing_Filter_Search($this);
	}

	/**
	 * @return %<DATA_MODEL_CLASS_ALIAS>%[]|DataModel_Fetch_Instances
	 */
	protected function getList() : DataModel_Fetch_Instances
	{
		return %<DATA_MODEL_CLASS_ALIAS>%::getList();
	}

}