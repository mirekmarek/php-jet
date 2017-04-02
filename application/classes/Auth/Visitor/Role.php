<?php
namespace JetExampleApp;

use Jet\DataModel_Related_MtoN_Iterator;
use Jet\Auth_Role;

/**
 *
 * @JetDataModel:database_table_name = 'roles_visitors'
 * @JetDataModel:id_class_name = 'DataModel_Id_AutoIncrement'
 * @JetDataModel:id_options = ['id_property_name'=>'id']
 */
class Auth_Visitor_Role extends Auth_Role{
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
	 * @JetDataModel:data_model_class = 'JetExampleApp\Auth_Visitor_Role_Users'
	 *
	 * @var Auth_Visitor_Role_Users|DataModel_Related_MtoN_Iterator|Auth_Visitor_User[]
	 */
	protected $users;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'JetExampleApp\Auth_Visitor_Role_Privilege'
	 * @JetDataModel:form_field_is_required = false
	 *
	 * @var Auth_Visitor_Role_Privilege[]
	 */
	protected $privileges;


	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

}