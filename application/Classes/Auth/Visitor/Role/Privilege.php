<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Auth_Role_Privilege_Interface;
use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_IDController_AutoIncrement;
use Jet\DataModel_Related_1toN;
use Jet\Form;
use Jet\Form_Field_Select;

/**
 *
 */
#[DataModel_Definition(
	name: 'role_privilege',
	database_table_name: 'roles_visitors_privileges',
	id_controller_class: DataModel_IDController_AutoIncrement::class,
	id_controller_options: ['id_property_name'=>'id'],
	parent_model_class: Auth_Visitor_Role::class
)]
class Auth_Visitor_Role_Privilege extends DataModel_Related_1toN implements Auth_Role_Privilege_Interface
{

	#[DataModel_Definition(
		related_to: 'main.id',
		form_field_type: false
	)]
	protected string $role_id = '';

	#[DataModel_Definition(
		type: DataModel::TYPE_ID_AUTOINCREMENT,
		is_id: true,
		form_field_type: false
	)]
	protected int $id = 0;

	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 100,
		form_field_is_required: true,
		form_field_type: false
	)]
	protected string $privilege = '';

	#[DataModel_Definition(
		type: DataModel::TYPE_CUSTOM_DATA,
		form_field_type: Form::TYPE_MULTI_SELECT,
		form_field_error_messages: [
			Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Invalid value'
		]
	)]
	protected array $values = [];


	/**
	 * @param string $privilege
	 * @param array $values
	 */
	public function __construct( $privilege = '', array $values = [] )
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
	public function getPrivilege() : string
	{
		return $this->privilege;
	}

	/**
	 * @param string $privilege
	 */
	public function setPrivilege( string $privilege ) : void
	{
		$this->privilege = $privilege;
	}

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function hasValue( mixed $value ) : bool
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
	public function getValues() : array
	{
		return $this->values;
	}

	/**
	 * @param array $values
	 */
	public function setValues( array $values )
	{
		$this->values = $values;
	}

	/**
	 *
	 * @return null|string|int
	 */
	public function getArrayKeyValue(): null|string|int
	{
		return $this->privilege;
	}

}