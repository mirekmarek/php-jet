<?php
namespace JetExampleApp;

use Jet\DataModel_Related_MtoN_Iterator;
use Jet\Auth_User;
use Jet\Application_Modules;

use JetApplicationModule\JetExample\Admin\Visitors\Users\Main as Admin_Visitor_Users_Module;

/**
 *
 * @JetDataModel:database_table_name = 'users_visitors'
 * @JetDataModel:id_class_name = 'DataModel_Id_AutoIncrement'
 * @JetDataModel:id_options = ['id_property_name'=>'id']
 */
class Auth_Visitor_User extends Auth_User{
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
	 * @JetDataModel:data_model_class = 'JetExampleApp\Auth_Visitor_User_Roles'
	 * @JetDataModel:form_field_get_select_options_callback = ['JetExampleApp\Auth_Visitor_Role', 'getList']
	 *
	 * @var Auth_Visitor_User_Roles|DataModel_Related_MtoN_Iterator|Auth_Visitor_Role[]
	 */
	protected $roles;

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return Admin_Visitor_Users_Module
	 */
	public static function getAdminModule() {
		return Application_Modules::getModuleInstance(Admin_Visitor_Users_Module::MODULE_NAME);
	}


	/**
	 * @return string|bool
	 */
	public static function getAddURI() {
		return static::getAdminModule()->getUserAddURI();
	}

	/**
	 * @return string|bool
	 */
	public function getEditURI()
	{
		return static::editURI($this->getId());
	}

	/**
	 * @param int $user_id
	 * @return string|bool
	 */
	public static function editURI( $user_id ) {
		return static::getAdminModule()->getUserEditURI( $user_id );
	}


	/**
	 * @return string|bool
	 */
	public function getDeleteURI()
	{
		return static::deleteURI($this->getId());
	}

	/**
	 * @param int $user_id
	 * @return string|bool
	 */
	public static function deleteURI( $user_id ) {
		return static::getAdminModule()->getUserDeleteURI( $user_id );
	}

}