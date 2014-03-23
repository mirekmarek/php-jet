<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Auth
 * @subpackage Auth_Role
 */
namespace Jet;

/**
 * Class Auth_Role_Privilege_Default
 *
 * @JetDataModel:database_table_name = 'Jet_Auth_Roles_Privileges'
 * @JetDataModel:parent_model_class_name = 'Jet\Auth_Role_Default'
 */
class Auth_Role_Privilege_Default extends Auth_Role_Privilege_Abstract {

	/**
	 * @JetDataModel:related_to = 'main.ID'
	 */
	protected $role_ID = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_ID
	 * @JetDataModel:is_ID = true
	 *
	 * @var string
	 */
	protected $ID = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 100
	 * @JetDataModel:is_required = true
	 * @JetDataModel:form_field_type = false
	 *
	 * @var string
	 */
	protected $privilege = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_ARRAY
	 * @JetDataModel:item_type = 'String'
	 * @JetDataModel:form_field_creator_method_name = 'getValuesFormField'
	 *
	 * @var array
	 */
	protected $values = array ();

	/**
	 * @var Auth_Role_Privilege_AvailablePrivilegesListItem[]
	 */
	private static $available_privileges_list;

	/**
	 * @return string
	 */
	public function getPrivilege() {
		return $this->privilege;
	}

	/**
	 * @param string $privilege
	 */
	public function setPrivilege($privilege) {
		$this->privilege = $privilege;
	}

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function getHasValue( $value ) {
		return in_array($value, $this->values);
	}

	/**
	 * @return mixed[]
	 */
	public function getValues() {
		return $this->values;
	}

	/**
	 * @param array $values
	 */
	public function setValues(array $values) {
		$this->values = $values;
	}

	/**
	 * DataModel method
	 *
	 * @return string
	 */
	public function getArrayKeyValue() {
		return $this->privilege;
	}

	/**
	 * DataModel method
	 *
	 * @return mixed[]
	 */
	protected function _jsonSerializeItem() {
		return $this->values;
	}

	/**
	 * @param DataModel_Definition_Property_Abstract $values_property_def
	 *
	 * @return Form_Field_Abstract
	 */
	public function getValuesFormField( DataModel_Definition_Property_Abstract $values_property_def ) {

		$form_field = $values_property_def->getFormField();

		if(!static::$available_privileges_list) {
			static::$available_privileges_list = Auth::getAvailablePrivilegesList(true);
		}

		$privilege_data = static::$available_privileges_list[ $this->privilege ];

		$form_field->setLabel( $privilege_data->getLabel() );
		$form_field->setSelectOptions( $privilege_data->getValuesList() );

		return $form_field;
	}

}