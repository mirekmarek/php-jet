<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Web\Auth\Entity;

use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_IDController_Passive;
use Jet\DataModel_Related_1toN;

/**
 *
 */
#[DataModel_Definition(
	name: 'visitors_roles',
	database_table_name: 'visitors_roles',
	parent_model_class: Visitor::class,
	id_controller_class: DataModel_IDController_Passive::class
)]
class Visitor_Roles extends DataModel_Related_1toN
{
	#[DataModel_Definition(
		related_to: 'main.id'
	)]
	protected int $user_id = 0;

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_ID,
		is_id: true,
	)]
	protected string $role_id = '';

	/**
	 * @var Role|null
	 */
	protected ?Role $_role = null;

	/**
	 * @return string
	 */
	public function getArrayKeyValue(): string
	{
		return $this->role_id;
	}

	/**
	 * @return int
	 */
	public function getUserId(): int
	{
		return $this->user_id;
	}

	/**
	 * @param int $user_id
	 */
	public function setUserId( int $user_id ): void
	{
		$this->user_id = $user_id;
	}

	/**
	 * @return string
	 */
	public function getRoleId(): string
	{
		return $this->role_id;
	}

	/**
	 * @param string $role_id
	 */
	public function setRoleId( string $role_id ): void
	{
		$this->_role = null;
		$this->role_id = $role_id;
	}


	/**
	 * @return Role|null
	 */
	public function getRole() : Role|null
	{
		if(!$this->_role) {
			$this->_role = Role::get($this->role_id);
		}
		return $this->_role;
	}

	/**
	 * @param string $id
	 */
	public static function roleDeleted( string $id ) : void
	{
		$items = static::fetchInstances(['role_id'=>$id]);

		foreach($items as $item) {
			$item->delete();
		}
	}

	/**
	 * @param string $id
	 *
	 * @return array<Visitor>
	 */
	public static function getRoleUsers( string $id ) : iterable
	{
		$ids = static::dataFetchCol(
			select:['user_id'],
			where: ['role_id'=>$id]
		);
		if(!$ids) {
			return [];
		}

		return Visitor::fetchInstances(['id' =>$ids]);
	}

}