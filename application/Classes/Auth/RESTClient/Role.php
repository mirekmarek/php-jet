<?php
namespace JetApplication;


use Jet\Application_Modules;
use Jet\DataModel;
use Jet\DataModel_Id_Name;
use Jet\DataModel_Related_MtoN_Iterator;
use Jet\Auth_Role_Interface;
use Jet\Data_Forest;
use Jet\Data_Tree;
use Jet\Tr;
use Jet\Mvc;
use Jet\Mvc_Page;
use Jet\Form;
use Jet\Form_Field;
use Jet\DataModel_Fetch_Instances;

/**
 *
 * @JetDataModel:name = 'role'
 * @JetDataModel:id_class_name = 'DataModel_Id_Name'
 * @JetDataModel:database_table_name = 'roles_rest_clients'
 *
 */
class Auth_RESTClient_Role extends DataModel implements Auth_Role_Interface
{

	/**
	 * Privilege for Modules/actions
	 */
	const PRIVILEGE_MODULE_ACTION = 'module_action';


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
	 * @JetDataModel:form_field_label = 'Name'
	 * @JetDataModel:form_field_error_messages = [Form_Field::ERROR_CODE_EMPTY=>'Please enter a name']
	 *
	 * @var string
	 */
	protected $name = '';
	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 65536
	 * @JetDataModel:form_field_label = 'Description'
	 *
	 * @var string
	 */
	protected $description = '';


	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Auth_RESTClient_Role_Privilege'
	 * @JetDataModel:form_field_is_required = false
	 *
	 * @var Auth_RESTClient_Role_Privilege[]
	 */
	protected $privileges;


	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Auth_RESTClient_Role_Users'
	 * @JetDataModel:form_field_type = false
	 *
	 * @var Auth_RESTClient_User[]|DataModel_Related_MtoN_Iterator
	 */
	protected $users;

	/**
	 * @var Form
	 */
	protected $_form_add;

	/**
	 * @var Form
	 */
	protected $_form_edit;


	/**
	 * @param string $id
	 *
	 * @return Auth_RESTClient_Role
	 */
	public static function get( $id )
	{
		$role = static::load( $id );

		/**
		 * @var Auth_RESTClient_Role $role
		 */
		return $role;
	}

	/**
	 *
	 * @param string $search
	 *
	 * @return DataModel_Fetch_Instances|Auth_RESTClient_Role[]
	 */
	public static function getList( $search = '' )
	{

		$where = [];
		if( $search ) {
			$search = '%'.$search.'%';

			$where[] = [
				'name *' => $search,
				'OR',
				'description *' => $search,
			];
		}


		$list = static::fetchInstances(
			$where,
			[
				'id',
				'name',
				'description',

			]);

		$list->getQuery()->setOrderBy( 'name' );

		return $list;
	}


	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->getIdObject()->toString();
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( $name )
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription( $description )
	{
		$this->description = $description;
	}

	/**
	 * @return Auth_RESTClient_User[]
	 */
	public function getUsers()
	{
		return $this->users;
	}

	/**
	 * @return Auth_RESTClient_Role_Privilege[]
	 */
	public function getPrivileges()
	{
		return $this->privileges;
	}

	/**
	 * Data format:
	 *
	 * array(
	 *      'privilege' => array('value1', 'value2')
	 * )
	 *
	 * @param array $privileges
	 */
	public function setPrivileges( array $privileges )
	{
		/** @noinspection PhpUndefinedMethodInspection */
		$this->privileges->clearData();

		foreach( $privileges as $privilege => $values ) {
			$this->setPrivilege( $privilege, $values );
		}
	}

	/**
	 * Example:
	 *
	 * privilege: save_object
	 * values: object_id_1,object_id_2, object_id_N
	 *
	 *
	 * @param string $privilege
	 * @param array  $values
	 */
	public function setPrivilege( $privilege, array $values )
	{
		if( !isset( $this->privileges[$privilege] ) ) {
			$this->privileges[$privilege] = new Auth_RESTClient_Role_Privilege( $privilege, $values );
		} else {
			$this->privileges[$privilege]->setValues( $values );
		}
	}

	/**
	 * Returns privilege values or empty array if the role does not have the privilege
	 *
	 * @param string $privilege
	 *
	 * @return array
	 */
	public function getPrivilegeValues( $privilege )
	{
		if( !isset( $this->privileges[$privilege] ) ) {
			return [];
		} else {
			return $this->privileges[$privilege]->getValues();
		}
	}

	/**
	 * @param string $privilege
	 */
	public function removePrivilege( $privilege )
	{
		if( isset( $this->privileges[$privilege] ) ) {
			unset( $this->privileges[$privilege] );
		}
	}

	/**
	 * @param string $privilege
	 * @param mixed  $value
	 *
	 * @return bool
	 */
	public function hasPrivilege( $privilege, $value )
	{
		if( !isset( $this->privileges[$privilege] ) ) {
			return false;
		}

		return $this->privileges[$privilege]->hasValue( $value );
	}

	/**
	 *
	 *
	 * @return array
	 */
	public static function getAvailablePrivilegesList()
	{

		return [
			static::PRIVILEGE_MODULE_ACTION
		];
	}


	/**
	 * Get Modules and actions ACL values list
	 *
	 * @return Data_Forest
	 */
	public static function getAclActionValuesList_ModulesActions()
	{

		$forest = new Data_Forest();
		$forest->setLabelKey( 'name' );
		$forest->setIdKey( 'id' );

		$modules = Application_Modules::activatedModulesList();

		foreach( $modules as $module_name => $module_info ) {

			$module = Application_Modules::moduleInstance( $module_name );

			$actions = $module->getModuleManifest()->getACLActions();

			if( !$actions ) {
				continue;
			}


			$data = [];


			foreach( $actions as $action => $action_description ) {
				$data[] = [
					'id'   => $module_name.':'.$action,
					'parent_id' => $module_name,
					'name' => $action_description,
				];
			}


			$tree = new Data_Tree();
			$tree->getRootNode()->setLabel( Tr::_( $module_info->getLabel(), [], $module_info->getName() ).' ('.$module_name.')' );
			$tree->getRootNode()->setId( $module_name );


			$tree->setData( $data );

			foreach( $tree as $node ) {
				if( $node->getIsRoot() ) {
					$node->setSelectOptionCssStyle( 'font-weight:bolder;font-size:15px;padding: 3px;' );
				} else {
					$node->setSelectOptionCssStyle(
						'padding-left: 20px;padding-top:2px; padding-bottom:2px; font-size:12px;'
					);
				}
			}

			$forest->appendTree( $tree );

		}

		return $forest;

	}

	/**
	 *
	 * @return Mvc_Page[]
	 */
	public static function getAclActionValuesList_Pages()
	{
		$pages = [];

		foreach( Mvc_Page::getList( Application_Admin::getSiteId(), Mvc::getCurrentLocale() ) as $page ) {
			$pages[$page->getId()] = $page->getName();
		}

		asort( $pages );

		return $pages;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString()
	{
		return $this->name;
	}


	/**
	 *
	 *
	 * @return Form
	 */
	public function _getForm()
	{
		$available_privileges_list = static::getAvailablePrivilegesList();

		foreach( $available_privileges_list as $privilege ) {
			if( !isset( $this->privileges[$privilege] ) ) {
				$this->setPrivilege( $privilege, [] );
			}
		}


		$form = $this->getCommonForm();

		$form->field('/privileges/module_action/values')->setSelectOptions(static::getAclActionValuesList_ModulesActions());
		$form->field('/privileges/module_action/values')->setLabel('Modules and actions');

		return $form;
	}


	/**
	 * @return Form
	 */
	public function getEditForm()
	{
		if(!$this->_form_edit) {
			$this->_form_edit = $this->_getForm();
		}

		return $this->_form_edit;
	}

	/**
	 * @return bool
	 */
	public function catchEditForm()
	{
		return $this->catchForm( $this->getEditForm() );
	}


	/**
	 * @return Form
	 */
	public function getAddForm()
	{
		if(!$this->_form_add) {
			$this->_form_add = $this->_getForm();
		}

		return $this->_form_add;
	}

	/**
	 * @return bool
	 */
	public function catchAddForm()
	{
		return $this->catchForm( $this->getAddForm() );
	}

}