<?php
namespace JetExampleApp;

use Jet\DataModel_Related_MtoN_Iterator;
use Jet\Auth_User;

/**
 *
 * @JetDataModel:database_table_name = 'users_administrators'
 * @JetDataModel:id_class_name = 'DataModel_Id_AutoIncrement'
 * @JetDataModel:id_options = ['id_property_name'=>'id']
 */
class Auth_Administrator_User extends Auth_User{

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID_AUTOINCREMENT
	 * @JetDataModel:is_id = true
	 *
	 * @var int
	 */
	protected $id = 0;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'JetExampleApp\Auth_Administrator_User_Roles'
	 * @JetDataModel:form_field_get_select_options_callback = ['JetExampleApp\Auth_Administrator_Role', 'getList']
	 *
	 * @var Auth_Administrator_User_Roles|DataModel_Related_MtoN_Iterator|Auth_Administrator_Role[]
	 */
	protected $roles;

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

}