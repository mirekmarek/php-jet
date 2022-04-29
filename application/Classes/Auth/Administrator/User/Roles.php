<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_IDController_Passive;
use Jet\DataModel_Related_1toN;

/**
 *
 */
#[DataModel_Definition(
	name: 'users_roles',
	database_table_name: 'users_administrators_roles',
	parent_model_class: Auth_Administrator_User::class,
	id_controller_class: DataModel_IDController_Passive::class
)]
class Auth_Administrator_User_Roles extends DataModel_Related_1toN
{
	#[DataModel_Definition(
		is_id: true,
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
	 * @var Auth_Administrator_Role|null
	 */
	protected ?Auth_Administrator_Role $_role = null;

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
	 * @return Auth_Administrator_Role|null
	 */
	public function getRole() : Auth_Administrator_Role|null
	{
		if(!$this->_role) {
			$this->_role = Auth_Administrator_Role::get($this->role_id);
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
	 * @return iterable
	 */
	public static function getRoleUsers( string $id ) : iterable
	{
		$ids = static::dataFetchCol(
			select:['user_id'],
			where: ['role_id'=>$id],
		);

		if(!$ids) {
			return [];
		}

		return Auth_Administrator_User::fetchInstances(['id'=>$ids]);
	}
}