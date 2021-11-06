<?php

namespace JetApplication;

use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_IDController_Name;
use Jet\DataModel_Related_MtoN_Iterator;
use Jet\Auth_Role_Interface;
use Jet\Data_Forest;
use Jet\Data_Tree;
use Jet\Form;
use Jet\Form_Field;
use Jet\MVC_Page_Interface;

/**
 *
 */
#[DataModel_Definition(
	name: 'role',
	id_controller_class: DataModel_IDController_Name::class,
	database_table_name: 'roles_visitors'
)]
class Auth_Visitor_Role extends DataModel implements Auth_Role_Interface
{
	const PRIVILEGE_VISIT_PAGE = 'visit_page';


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
	 * @var Auth_Visitor_Role_Privilege[]
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_DATA_MODEL,
		data_model_class: Auth_Visitor_Role_Privilege::class,
		form_field_is_required: false
	)]
	protected $privileges;


	/**
	 * @var Auth_Visitor_User[]|DataModel_Related_MtoN_Iterator
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_DATA_MODEL,
		data_model_class: Auth_Visitor_Role_Users::class,
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
	 * @return Auth_Visitor_Role[]
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
	 * @return Auth_Visitor_User[]
	 */
	public function getUsers(): iterable
	{
		return $this->users;
	}

	/**
	 * @return Auth_Visitor_Role_Privilege[]
	 */
	public function getPrivileges(): array
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
			$this->privileges[$privilege] = new Auth_Visitor_Role_Privilege( $privilege, $values );
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
	public static function getAvailablePrivilegesList() : array
	{

		return [
			static::PRIVILEGE_VISIT_PAGE
		];
	}

	/**
	 * @return string
	 */
	public function __toString() : string
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString() : string
	{
		return $this->name;
	}


	/**
	 *
	 *
	 * @return Form
	 */
	public function _getForm() : Form
	{
		$available_privileges_list = static::getAvailablePrivilegesList();

		foreach( $available_privileges_list as $privilege ) {
			if( !isset( $this->privileges[$privilege] ) ) {
				$this->setPrivilege( $privilege, [] );
			}
		}


		$form = $this->getCommonForm();

		$form->field( '/privileges/visit_page/values' )->setSelectOptions( static::getAclActionValuesList_Pages() );
		$form->field( '/privileges/visit_page/values' )->setLabel( 'Secret area access' );


		return $form;
	}

	/**
	 *
	 * @return Data_Forest
	 */
	public static function getAclActionValuesList_Pages() : Data_Forest
	{

		$forest = new Data_Forest();
		$forest->setIdKey( 'id' );
		$forest->setLabelKey( 'name' );


		$base = Application_Web::getBase();
		foreach( $base->getLocales() as $locale ) {

			$homepage = $base->getHomepage( $locale );

			$tree = new Data_Tree();
			$tree->setAdoptOrphans( true );

			$tree->getRootNode()->setId( $homepage->getKey() );
			$tree->getRootNode()->setLabel(
				$homepage->getBase()->getName() . ' (' . $homepage->getLocale()->getName() . ')' . ' - ' . $homepage->getName()
			);

			$pages = [];
			foreach( $homepage->getChildren() as $page ) {
				static::_getPagesTree( $page, $pages );
			}

			$tree->setData( $pages );

			$forest->appendTree( $tree );
		}


		foreach( $forest as $node ) {
			//$node->setLabel( $node->getLabel().' ('.$node->getId().')' );

			if( $node->getIsRoot() ) {
				$node->setSelectOptionCssStyle( 'font-weight:bolder;font-size:15px;padding: 3px;' );
			} else {
				$padding = 20 * $node->getDepth();
				$node->setSelectOptionCssStyle(
					'padding-left: ' . $padding . 'px;padding-top:2px; padding-bottom:2px; font-size:12px;'
				);
			}

		}

		return $forest;
	}

	/**
	 * @param MVC_Page_Interface $page
	 * @param                    $data
	 */
	protected static function _getPagesTree( MVC_Page_Interface $page, &$data )
	{

		if( $page->getIsSecret() ) {
			$data[$page->getKey()] = [
				'id'        => $page->getKey(),
				'parent_id' => $page->getParent()->getKey(),
				'name'      => $page->getName(),
			];
		}


		foreach( $page->getChildren() as $page ) {
			static::_getPagesTree( $page, $data );
		}
	}


	/**
	 * @return Form
	 */
	public function getEditForm() : Form
	{
		if( !$this->_form_edit ) {
			$this->_form_edit = $this->_getForm();
		}

		return $this->_form_edit;
	}

	/**
	 * @return bool
	 */
	public function catchEditForm() : bool
	{
		return $this->getEditForm()->catch();
	}


	/**
	 * @return Form
	 */
	public function getAddForm() : Form
	{
		if( !$this->_form_add ) {
			$this->_form_add = $this->_getForm();
		}

		return $this->_form_add;
	}

	/**
	 * @return bool
	 */
	public function catchAddForm() : bool
	{
		return $this->getAddForm()->catch();
	}


}