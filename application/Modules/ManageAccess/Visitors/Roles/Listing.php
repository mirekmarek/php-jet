<?php

/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\ManageAccess\Visitors\Roles;

use JetApplication\Auth_Visitor_Role as Role;

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
		'id'          => ['title' => 'ID'],
		'name'        => ['title' => 'Name'],
		'description' => ['title' => 'Description'],
	];

	/**
	 * @var string[]
	 */
	protected $filters = [
		'search'
	];

	/**
	 * @return DataModel_Fetch_Instances
	 */
	protected function getList()
	{
		return Role::getList();
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
			'name *' => $search,
			'OR',
			'description *' => $search,
		]);

	}
}