<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 * @JetDataModel:name = 'role_privilege'
 * @JetDataModel:parent_model_class_name = 'Auth_Role'
 * @JetDataModel:database_table_name = 'Jet_Auth_Roles_Privileges'
 * @JetDataModel:id_class_name = 'DataModel_Id_UniqueString'
 */
class Auth_Role_Privilege extends DataModel_Related_1toN implements Auth_Role_Privilege_Interface
{

	/**
	 * @JetDataModel:related_to = 'main.id'
	 * @JetDataModel:form_field_type = false
	 */
	protected $role_id = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:is_id = true
	 * @JetDataModel:form_field_type = false
	 *
	 * @var string
	 */
	protected $id = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 100
	 * @JetDataModel:form_field_is_required = true
	 * @JetDataModel:form_field_type = false
	 *
	 * @var string
	 */
	protected $privilege = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ARRAY
	 * @JetDataModel:form_field_creator_method_name = 'getFormField'
	 * @JetDataModel:form_field_error_messages = [Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Invalid value']
	 *
	 * @var array
	 */
	protected $values = [];

	/**
	 * @var Auth_Role
	 */
	protected $_role;

	/**
	 * @param         $privilege
	 * @param mixed[] $values
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
	 * @param Auth_Role_Interface $role
	 */
	public function setRole( Auth_Role_Interface $role )
	{
		$this->_role = $role;
	}

	/**
	 * @return string
	 */
	public function getPrivilege()
	{
		return $this->privilege;
	}

	/**
	 * @param string $privilege
	 */
	public function setPrivilege( $privilege )
	{
		$this->privilege = $privilege;
	}

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function getHasValue( $value )
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
	 * @return mixed[]
	 */
	public function getValues()
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
	 * DataModel method
	 *
	 * @return string
	 */
	public function getArrayKeyValue()
	{
		return $this->privilege;
	}

	/**
	 * @param DataModel_Definition_Property_Abstract $values_property_definition
	 *
	 * @return Form_Field_Abstract|bool
	 */
	public function getFormField( DataModel_Definition_Property_Abstract $values_property_definition )
	{


		/** @noinspection PhpStaticAsDynamicMethodCallInspection */
		$available_privileges_list = $this->_role->getAvailablePrivilegesList();

		if( !isset( $available_privileges_list[$this->privilege] ) ) {
			return false;
		}

		$privilege_data = $available_privileges_list[$this->privilege];

		$form_field = Form_Factory::getFieldInstance(
			$values_property_definition->getFormFieldType(), $values_property_definition->getName(),
			$privilege_data->getLabel(), $this->values, $values_property_definition->getFormFieldIsRequired()
		);

		$form_field->setErrorMessages( $values_property_definition->getFormFieldErrorMessages() );
		$form_field->setOptions( $values_property_definition->getFormFieldOptions() );
		$form_field->setSelectOptions( $privilege_data->getValuesList() );

		return $form_field;

	}

	/**
	 * DataModel method
	 *
	 * @return mixed[]
	 */
	protected function _jsonSerializeItem()
	{
		return $this->values;
	}

}