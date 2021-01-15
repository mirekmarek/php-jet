<?php

/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\ManageAccess\RESTClients\Roles;

use Jet\DataModel_Fetch_Instances;
use JetApplication\Auth_RESTClient_Role as Role;

use Jet\Data_Listing;
use Jet\Data_Listing_Filter_search;

/**
 *
 */
class Listing extends Data_Listing
{

	use Data_Listing_Filter_search;

	/**
	 * @var array
	 */
	protected array $grid_columns = [
		'_edit_'      => [
			'title'         => '',
			'disallow_sort' => true
		],
		'id'          => ['title' => 'ID'],
		'name'        => ['title' => 'Name'],
		'description' => ['title' => 'Description'],
	];

	/**
	 * @var array
	 */
	protected array $filters = [
		'search'
	];

	/**
	 * @return Role[]|DataModel_Fetch_Instances
	 * @noinspection PhpDocSignatureInspection
	 */
	protected function getList(): DataModel_Fetch_Instances
	{
		return Role::getList();
	}

	/**
	 *
	 */
	protected function filter_search_getWhere(): void
	{
		if( !$this->search ) {
			return;
		}

		$search = '%' . $this->search . '%';
		$this->filter_addWhere( [
			'name *'        => $search,
			'OR',
			'description *' => $search,
		] );

	}
}