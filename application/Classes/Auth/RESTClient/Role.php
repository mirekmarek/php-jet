<?php

namespace JetApplication;


use Jet\Application_Modules;
use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_IDController_Name;
use Jet\DataModel_Related_MtoN_Iterator;
use Jet\Auth_Role_Interface;
use Jet\Data_Forest;
use Jet\Data_Tree;
use Jet\Tr;
use Jet\Mvc;
use Jet\Mvc_Page;
use Jet\Form;
use Jet\Form_Field;

/**
 *
 */
#[DataModel_Definition(
	name: 'role',
	id_controller_class: DataModel_IDController_Name::class,
	database_table_name: 'roles_rest_clients'
)]
class Auth_RESTClient_Role extends DataModel implements Auth_Role_Interface
{
	const PRIVILEGE_MODULE_ACTION = 'module_action';


	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_ID,
		is_id: true,
		form_field_type: false
	)]
	protected string $id = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 100,
		form_field_is_required: true,
		form_field_label: 'Name',
		form_field_error_messages: [
			Form_Field::ERROR_CODE_EMPTY => 'Please enter a name'
		]
	)]
	protected string $name = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 65536,
		form_field_label: 'Description'
	)]
	protected string $description = '';


	/**
	 * @var Auth_RESTClient_Role_Privilege[]
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_DATA_MODEL,
		data_model_class: Auth_RESTClient_Role_Privilege::class,
		form_field_is_required: false
	)]
	protected $privileges;


	/**
	 * @var Auth_RESTClient_User[]|DataModel_Related_MtoN_Iterator
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_DATA_MODEL,
		data_model_class: Auth_RESTClient_Role_Users::class,
		form_field_type: false
	)]
	protected $users;

	/**
	 * @var ?Form
	 */
	protected ?Form $_form_add = null;

	/**
	 * @var ?Form
	 */
	protected ?Form $_form_edit = null;


	/**
	 * @param string $id
	 *
	 * @return static|null
	 */
	public static function get( string $id ): static|null
	{
		return static::load( $id );
	}

	/**
	 *
	 * @param string $search
	 *
	 * @return Auth_RESTClient_Role[]
	 */
	public static function getList( string $search = '' ): iterable
	{

		$where = [];
		if( $search ) {
			$search = '%' . $search . '%';

			$where[] = [
				'name *'        => $search,
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

			] );

		$list->getQuery()->setOrderBy( 'name' );

		return $list;
	}


	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
	}


	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ): void
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription( string $description ): void
	{
		$this->description = $description;
	}

	/**
	 * @return Auth_RESTClient_User[]
	 */
	public function getUsers(): iterable
	{
		return $this->users;
	}

	/**
	 * @return Auth_RESTClient_Role_Privilege[]
	 */
	public function getPrivileges(): array
	{
		return $this->privileges;
	}

	/**
	 * [
	 *      'privilege' => array('value1', 'value2')
	 * ]
	 *
	 * @param array $privileges
	 */
	public function setPrivileges( array $privileges ): void
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
	 * @param array $values
	 */
	public function setPrivilege( string $privilege, array $values ): void
	{
		if( !isset( $this->privileges[$privilege] ) ) {
			$this->privileges[$privilege] = new Auth_RESTClient_Role_Privilege( $privilege, $values );
		} else {
			$this->privileges[$privilege]->setValues( $values );
		}
	}

	/**
	 *
	 * @param string $privilege
	 *
	 * @return array
	 */
	public function getPrivilegeValues( string $privilege ): array
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
	public function removePrivilege( string $privilege ): void
	{
		if( isset( $this->privileges[$privilege] ) ) {
			unset( $this->privileges[$privilege] );
		}
	}

	/**
	 * @param string $privilege
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function hasPrivilege( string $privilege, mixed $value ): bool
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
	public static function getAvailablePrivilegesList(): array
	{

		return [
			static::PRIVILEGE_MODULE_ACTION
		];
	}


	/**
	 *
	 * @return Data_Forest
	 */
	public static function getAclActionValuesList_ModulesActions(): Data_Forest
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
					'id'        => $module_name . ':' . $action,
					'parent_id' => $module_name,
					'name'      => $action_description,
				];
			}


			$tree = new Data_Tree();
			$tree->getRootNode()->setLabel( Tr::_( $module_info->getLabel(), [], $module_info->getName() ) . ' (' . $module_name . ')' );
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
	public static function getAclActionValuesList_Pages(): array
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
	public function __toString(): string
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		return $this->name;
	}


	/**
	 *
	 *
	 * @return Form
	 */
	public function _getForm(): Form
	{
		$available_privileges_list = static::getAvailablePrivilegesList();

		foreach( $available_privileges_list as $privilege ) {
			if( !isset( $this->privileges[$privilege] ) ) {
				$this->setPrivilege( $privilege, [] );
			}
		}


		$form = $this->getCommonForm();

		$form->field( '/privileges/module_action/values' )->setSelectOptions( static::getAclActionValuesList_ModulesActions() );
		$form->field( '/privileges/module_action/values' )->setLabel( 'Modules and actions' );

		return $form;
	}


	/**
	 * @return Form
	 */
	public function getEditForm(): Form
	{
		if( !$this->_form_edit ) {
			$this->_form_edit = $this->_getForm();
		}

		return $this->_form_edit;
	}

	/**
	 * @return bool
	 */
	public function catchEditForm(): bool
	{
		return $this->catchForm( $this->getEditForm() );
	}


	/**
	 * @return Form
	 */
	public function getAddForm(): Form
	{
		if( !$this->_form_add ) {
			$this->_form_add = $this->_getForm();
		}

		return $this->_form_add;
	}

	/**
	 * @return bool
	 */
	public function catchAddForm(): bool
	{
		return $this->catchForm( $this->getAddForm() );
	}

}