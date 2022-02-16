<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Auth_Role_Privilege_Interface;
use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_IDController_AutoIncrement;
use Jet\DataModel_Related_1toN;

/**
 *
 */
#[DataModel_Definition(
	name: 'role_privilege',
	database_table_name: 'roles_administrators_privileges',
	id_controller_class: DataModel_IDController_AutoIncrement::class,
	id_controller_options: ['id_property_name' => 'id'],
	parent_model_class: Auth_Administrator_Role::class
)]
class Auth_Administrator_Role_Privilege extends DataModel_Related_1toN implements Auth_Role_Privilege_Interface
{
	/**
	 */
	#[DataModel_Definition(
		related_to: 'main.id'
	)]
	protected string $role_id = '';

	/**
	 * @var int
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_ID_AUTOINCREMENT,
		is_id: true
	)]
	protected int $id = 0;

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 100,
	)]
	protected string $privilege = '';

	/**
	 * @var array
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_CUSTOM_DATA,
	)]
	protected array $values = [];


	/**
	 * @param string $privilege
	 * @param array $values
	 */
	public function __construct( string $privilege = '', array $values = [] )
	{

		if( $privilege ) {
			$this->setPrivilege( $privilege );
			$this->setValues( $values );
		}

		parent::__construct();
	}

	/**
	 * @return string
	 */
	public function getPrivilege(): string
	{
		return $this->privilege;
	}

	/**
	 * @param string $privilege
	 */
	public function setPrivilege( string $privilege ): void
	{
		$this->privilege = $privilege;
	}

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function hasValue( mixed $value ): bool
	{
		if( is_array( $value ) ) {
			foreach( $value as $v ) {
				if( in_array( $v, $this->values ) ) {
					return true;
				}
			}

			return false;
		}

		return in_array( $value, $this->values );
	}

	/**
	 * @return array
	 */
	public function getValues(): array
	{
		return $this->values;
	}

	/**
	 * @param array $values
	 */
	public function setValues( array $values ): void
	{
		$this->values = $values;
	}

	/**
	 * @return string
	 */
	public function getArrayKeyValue(): string
	{
		return $this->privilege;
	}
}