<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\ManageAccess\Administrators\Users;

use Jet\DataModel_Fetch_Instances;
use JetApplication\Auth_Administrator_User as User;

use Jet\Data_Listing;

/**
 *
 */
class Listing extends Data_Listing
{

	/**
	 * @var array
	 */
	protected array $grid_columns = [
		'_edit_'     => [
			'title'         => '',
			'disallow_sort' => true
		],
		'id'         => ['title' => 'ID'],
		'username'   => ['title' => 'Username'],
		'first_name' => ['title' => 'First name'],
		'surname'    => ['title' => 'Surname'],
	];


	/**
	 *
	 */
	protected function initFilters(): void
	{
		$this->filters['search'] = new Listing_Filter_Search($this);
		$this->filters['role'] = new Listing_Filter_Role($this);
	}


	/**
	 * @return User[]|DataModel_Fetch_Instances
	 * @noinspection PhpDocSignatureInspection
	 */
	protected function getList(): DataModel_Fetch_Instances
	{
		return User::getList();
	}

}