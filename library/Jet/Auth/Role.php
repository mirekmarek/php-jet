<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * @JetDataModel:name = 'role'
 * @JetDataModel:id_class_name = 'DataModel_Id_Name'
 * @JetDataModel:database_table_name = 'Jet_Auth_Roles'
 */
class Auth_Role extends DataModel implements Auth_Role_Interface
{

	/**
	 * Privilege to sites/page
	 */
	const PRIVILEGE_VISIT_PAGE = 'visit_page';

	/**
	 * Privilege for modules/actions
	 */
	const PRIVILEGE_MODULE_ACTION = 'module_action';

	/**
	 * @var array
	 */
	protected static $standard_privileges = [
		self::PRIVILEGE_VISIT_PAGE => [
			'label' => 'Sites and pages', 'get_available_values_list_method_name' => 'getAclActionValuesList_Pages',
		],

		self::PRIVILEGE_MODULE_ACTION => [
			'label'                                 => 'Modules and actions',
			'get_available_values_list_method_name' => 'getAclActionValuesList_ModulesActions',
		],

	];
	/**
	 * @var Auth_Role_Privilege_AvailablePrivilegesListItem[]
	 */
	protected static $available_privileges_list;
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
	 * @JetDataModel:data_model_class = 'Auth_Role_Privilege'
	 * @JetDataModel:form_field_is_required = false
	 *
	 * @var Auth_Role_Privilege[]
	 */
	protected $privileges;
	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Auth_Role_Users'
	 * @JetDataModel:form_field_type = false
	 *
	 * @var Auth_User[]
	 */
	protected $users;

	/**
	 * @param string $id
	 *
	 * @return Auth_Role
	 */
	public static function get( $id )
	{
		$role = static::load( $id );

		/**
		 * @var Auth_Role $role
		 */
		return $role;
	}

	/**
	 *
	 * @param string $search
	 *
	 * @return DataModel_Fetch_Object_Assoc|Auth_Role[]
	 */
	public static function getList( $search = '' )
	{

		$where = [];
		if( $search ) {
			$search = '%'.$search.'%';

			$where[] = [
				'this.name *' => $search, 'OR', 'this.description *' => $search,
			];
		}

		/**
		 * @var Auth_Role $_this
		 */
		$_this = new static();

		$list = $_this->fetchObjects( $where );
		$list->setLoadFilter(
			[
				'this.id', 'this.name', 'this.description',
			]
		);
		$list->getQuery()->setOrderBy( 'name' );

		return $list;
	}

	/**
	 * Get modules and actions ACL values list
	 *
	 * @return Data_Tree_Forest
	 */
	public static function getAclActionValuesList_ModulesActions()
	{
		$forest = new Data_Tree_Forest();
		$forest->setLabelKey( 'name' );
		$forest->setIdKey( 'id' );

		$modules = Application_Modules::getActivatedModulesList();

		foreach( $modules as $module_name => $module_info ) {

			$module = Application_Modules::getModuleInstance( $module_name );

			$actions = $module->getAclActions();

			if( !$actions ) {
				continue;
			}


			$data = [];


			foreach( $actions as $action => $action_description ) {
				$data[] = [
					'id' => $module_name.':'.$action, 'parent_id' => $module_name, 'name' => $action_description,
				];
			}

			$tree = new Data_Tree();
			$tree->getRootNode()->setLabel( $module_info->getLabel().' ('.$module_name.')' );
			$tree->getRootNode()->setId( $module_name );

			$tree->setData( $data );

			$forest->appendTree( $tree );

		}

		return $forest;
	}

	/**
	 * Get sites and pages ACL values list
	 *
	 * @return Data_Tree_Forest
	 */
	public static function getAclActionValuesList_Pages()
	{

		$forest = new Data_Tree_Forest();
		$forest->setIdKey( 'id' );
		$forest->setLabelKey( 'name' );

		foreach( Mvc_Site::loadSites() as $site ) {
			foreach( $site->getLocales() as $locale ) {

				$homepage = $site->getHomepage( $locale );

				$tree = new Data_Tree();
				$tree->getRootNode()->setId( $homepage->getKey() );
				$tree->getRootNode()->setLabel(
					$homepage->getSite()->getName().' ('.$homepage->getLocale()->getName().')'.' - '.$homepage->getName(
					)
				);

				$pages = [];
				foreach( $homepage->getChildren() as $page ) {
					static::_getAllPagesTree( $page, $pages );
				}

				$tree->setData( $pages );

				$forest->appendTree( $tree );


			}
		}


		foreach( $forest as $node ) {
			//$node->setLabel( $node->getLabel().' ('.$node->getId().')' );

			if( $node->getIsRoot() ) {
				$node->setSelectOptionCssStyle( 'font-weight:bolder;font-size:15px;padding: 3px;' );
			} else {
				$padding = 20*$node->getDepth();
				$node->setSelectOptionCssStyle(
					'padding-left: '.$padding.'px;padding-top:2px; padding-bottom:2px; font-size:12px;'
				);
			}

		}

		return $forest;
	}

	/**
	 * @param Mvc_Page_Interface $page
	 * @param array $data
	 */
	protected static function _getAllPagesTree( Mvc_Page_Interface $page, &$data )
	{
		/*
		if($page->getIsAdminUI()) {
			return;
		}
		*/


		/**
		 * @var Mvc_Page $page
		 */
		$data[$page->getKey()] = [
			'id' => $page->getKey(), 'parent_id' => $page->getParent()->getKey(), 'name' => $page->getName(),
		];

		foreach( $page->getChildren() as $page ) {
			static::_getAllPagesTree( $page, $data );
		}
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
	 * @return Auth_User_Interface[]
	 */
	public function getUsers()
	{
		return $this->users;
	}

	/**
	 * @return Auth_Role_Privilege_Interface[]
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

			/**
			 * @var DataModel_Definition_Property_DataModel $def
			 */
			$def = static::getDataModelDefinition()->getProperty( 'privileges' );
			$class = $def->getValueDataModelClass();

			$this->privileges[$privilege] = new $class( $privilege, $values );
		} else {
			$this->privileges[$privilege]->setValues( $values );
		}

		$this->privileges[$privilege]->setRole( $this );
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
	 * @param string                              $form_name
	 * @param array|DataModel_PropertyFilter|null $property_filter
	 *
	 * @throws DataModel_Exception
	 * @return Form
	 */
	public function getForm( $form_name, $property_filter = null )
	{
		$available_privileges_list = static::getAvailablePrivilegesList();

		foreach( $available_privileges_list as $privilege => $privilege_data ) {
			if( !isset( $this->privileges[$privilege] ) ) {
				$this->setPrivilege( $privilege, [] );
			}
		}


		return parent::getForm( $form_name, $property_filter );
	}

	/**
	 *
	 *
	 * @return Auth_Role_Privilege_AvailablePrivilegesListItem[]
	 */
	public static function getAvailablePrivilegesList()
	{

		if( static::$available_privileges_list===null ) {
			static::$available_privileges_list = [];

			foreach( static::$standard_privileges as $privilege => $d ) {
				$available_values_list = null;

				/**
				 * @var callable $callback
				 */
				$callback = [ get_called_class(), $d['get_available_values_list_method_name'] ];

				$available_values_list = $callback();

				$item = new Auth_Role_Privilege_AvailablePrivilegesListItem(
					$privilege, $d['label'], $available_values_list
				);

				static::$available_privileges_list[$privilege] = $item;
			}

		}

		return static::$available_privileges_list;
	}

	/**
	 *
	 */
	public function afterLoad()
	{
		foreach( $this->privileges as $privilege ) {
			$privilege->setRole( $this );
		}
	}

	/**
	 *
	 */
	public function afterAdd()
	{
	}

	/**
	 *
	 */
	public function afterUpdate()
	{
	}

	/**
	 *
	 */
	public function afterDelete()
	{
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

}