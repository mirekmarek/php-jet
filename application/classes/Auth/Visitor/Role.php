<?php
namespace JetExampleApp;

use Jet\DataModel_Related_MtoN_Iterator;
use Jet\Auth_Role;
use Jet\Mvc;
use Jet\Mvc_Factory;
use Jet\Data_Tree_Forest;
use Jet\Application_Modules;

use JetApplicationModule\JetExample\Admin\Visitors\Roles\Main as Admin_Administrator_Roles_Module;


/**
 *
 * @JetDataModel:database_table_name = 'roles_visitors'
 * @JetDataModel:id_class_name = 'DataModel_Id_AutoIncrement'
 * @JetDataModel:id_options = ['id_property_name'=>'id']
 */
class Auth_Visitor_Role extends Auth_Role{

	/**
	 * @var array
	 */
	protected static $standard_privileges = [
		self::PRIVILEGE_VISIT_PAGE => [
			'label' => 'Sites and pages',
			'get_available_values_list_method_name' => 'getAclActionValuesList_Pages'
		]

	];


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

	/**
	 * @return Admin_Administrator_Roles_Module
	 */
	public static function getAdminModule() {
		return Application_Modules::getModuleInstance(Admin_Administrator_Roles_Module::MODULE_NAME);
	}

	/**
	 * @return string|bool
	 */
	public static function getAddURI() {
		return static::getAdminModule()->getRoleAddURI();
	}

	/**
	 * @return string|bool
	 */
	public function getEditURI()
	{
		return static::editURI($this->getId());
	}

	/**
	 * @param int $role_id
	 * @return string|bool
	 */
	public static function editURI( $role_id ) {
		return static::getAdminModule()->getRoleEditURI( $role_id );
	}


	/**
	 * @return string|bool
	 */
	public function getDeleteURI()
	{
		return static::deleteURI($this->getId());
	}

	/**
	 * @param int $role_id
	 * @return string|bool
	 */
	public static function deleteURI( $role_id ) {
		return static::getAdminModule()->getRoleDeleteURI( $role_id );
	}


	/**
	 * Get sites and pages ACL values list
	 *
	 * @return Data_Tree_Forest
	 */
	public static function getAclActionValuesList_Pages() {
		$pages_tree =Mvc_Factory::getPageInstance()->getAllPagesTree();

		return $pages_tree;
	}

}