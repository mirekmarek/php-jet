<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Auth_Role_Privilege_Interface;
use Jet\DataModel;
use Jet\DataModel_Id_AutoIncrement;
use Jet\DataModel_Related_1toN;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Select;

/**
 *
 * @JetDataModel:name = 'role_privilege'
 * @JetDataModel:database_table_name = 'roles_visitors_privileges'
 * @JetDataModel:id_class_name = 'DataModel_Id_AutoIncrement'
 * @JetDataModel:id_options = ['id_property_name'=>'id']
 * @JetDataModel:parent_model_class_name = 'Auth_Visitor_Role'
 */
class Auth_Visitor_Role_Privilege extends DataModel_Related_1toN implements Auth_Role_Privilege_Interface
{
	/**
	 * @JetDataModel:related_to = 'main.id'
	 * @JetDataModel:form_field_type = false
	 */
	protected $role_id = 0;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID_AUTOINCREMENT
	 * @JetDataModel:is_id = true
	 * @JetDataModel:form_field_type = false
	 *
	 * @var string
	 */
	protected $id = 0;

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
	 * @JetDataModel:type = DataModel::TYPE_CUSTOM_DATA
	 * @JetDataModel:form_field_type = Form::TYPE_MULTI_SELECT
	 * @JetDataModel:form_field_error_messages = [Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Invalid value']
	 *
	 * @var array
	 */
	protected $values = [];


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
	public function hasValue( $value )
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

}