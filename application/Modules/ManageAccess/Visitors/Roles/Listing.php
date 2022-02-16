<?php

/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\ManageAccess\Visitors\Roles;

use Jet\DataModel_Fetch_Instances;
use JetApplication\Auth_Visitor_Role as Role;

use Jet\Data_Listing;
use Jet\Data_Listing_Filter_Search;

/**
 *
 */
class Listing extends Data_Listing
{

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
	 * @var string
	 */
	protected string $default_sort = 'name';

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
						'name *'        => $search,
						'OR',
						'description *' => $search,
					] );
				}

			}
		};
	}


	/**
	 * @return Role[]|DataModel_Fetch_Instances
	 * @noinspection PhpDocSignatureInspection
	 */
	protected function getList(): DataModel_Fetch_Instances
	{
		return Role::getList();
	}

}