<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\ManageAccess\Administrators\Users;

use JetApplication\Auth_Administrator_User as User;
use JetApplication\Auth_Administrator_Role as Role;

use Jet\Data_Listing;
use Jet\Data_Listing_Filter_search;
use Jet\Form;
use Jet\Form_Field_Select;
use Jet\Http_Request;
use Jet\Tr;

/**
 *
 */
class Listing extends Data_Listing {

	use Data_Listing_Filter_search;

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
	 * @var array
	 */
	protected array $filters = [
		'search',
		'role'
	];

	/**
	 * @var string
	 */
	protected string $role = '';

	/**
	 * @return User[]
	 */
	protected function getList() : iterable
	{
		return User::getList();
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
			'username *'   => $search,
			'OR',
			'first_name *' => $search,
			'OR',
			'surname *'    => $search,
			'OR',
			'email *'      => $search,
		]);

	}




	/**
	 *
	 */
	protected function filter_role_catchGetParams() : void
	{
		$this->role = Http_Request::GET()->getString('role');
		$this->setGetParam('role', $this->role);
	}

	/**
	 * @param Form $form
	 */
	public function filter_role_catchForm( Form $form ) : void
	{
		$value = $form->field('role')->getValue();

		$this->role = $value;
		$this->setGetParam('role', $value);
	}

	/**
	 * @param Form $form
	 */
	protected function filter_role_getForm( Form $form ) : void
	{
		$field = new Form_Field_Select('role', 'Role:', $this->role);
		$field->setErrorMessages([
			Form_Field_Select::ERROR_CODE_INVALID_VALUE => ' '
		]);
		$options = [0=>Tr::_('- all -')];

		foreach(Role::getList() as $role) {
			$options[$role->getId()] = $role->getName();
		}
		$field->setSelectOptions( $options );


		$form->addField($field);
	}

	/**
	 *
	 */
	protected function filter_role_getWhere() : void
	{
		if($this->role) {
			$this->filter_addWhere([
				'role.id' => $this->role,
			]);
		}
	}

}