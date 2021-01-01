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
#[DataModel_Definition(name: 'role_privilege' )]
#[DataModel_Definition(database_table_name: 'roles_administrators_privileges')]
#[DataModel_Definition(id_controller_class: DataModel_IDController_AutoIncrement::class)]
#[DataModel_Definition(id_controller_options: ['id_property_name'=>'id'])]
#[DataModel_Definition(parent_model_class: Auth_Administrator_Role::class)]
class Auth_Administrator_Role_Privilege extends DataModel_Related_1toN implements Auth_Role_Privilege_Interface
{
	/**
	 */
	#[DataModel_Definition(related_to: 'main.id')]
	#[DataModel_Definition(form_field_type: false)]
	protected string $role_id = '';

	/**
	 * @var int
	 */
	#[DataModel_Definition(type: DataModel::TYPE_ID_AUTOINCREMENT)]
	#[DataModel_Definition(is_id: true)]
	#[DataModel_Definition(form_field_type: false)]
	protected int $id = 0;

	/**
	 * @var string
	 */
	#[DataModel_Definition(type: DataModel::TYPE_STRING)]
	#[DataModel_Definition(max_len: 100)]
	#[DataModel_Definition(form_field_is_required: true)]
	#[DataModel_Definition(form_field_type: false)]
	protected string $privilege = '';

	/**
	 * @var array
	 */
	#[DataModel_Definition(type: DataModel::TYPE_CUSTOM_DATA)]
	#[DataModel_Definition(form_field_type: Form::TYPE_MULTI_SELECT)]
	#[DataModel_Definition(form_field_error_messages: [Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Invalid value'])]
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
	public function setValues( array $values ) : void
	{
		$this->values = $values;
	}

	/**
	 * @return string|int|null
	 */
	public function getArrayKeyValue(): null|string|int
	{
		return $this->privilege;
	}
}