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
use Jet\DataModel_Fetch_Instances;
use Jet\Form;
use Jet\Form_Field_Search;
use Jet\Http_Request;

class Listing extends Data_Listing {

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
	 * @var string
	 */
	protected $search = '';

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
	protected function filter_search_catchGetParams()
	{
		$this->search = Http_Request::GET()->getString('search');
		$this->setGetParam('search', $this->search);
	}

	/**
	 * @param Form $form
	 */
	public function filter_search_catchForm( Form $form )
	{
		$value = $form->field('search')->getValue();

		$this->search = $value;
		$this->setGetParam('search', $value);
	}

	/**
	 * @param Form $form
	 */
	protected function filter_search_getForm( Form $form )
	{
		$search = new Form_Field_Search('search', '', $this->search);
		$form->addField($search);
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