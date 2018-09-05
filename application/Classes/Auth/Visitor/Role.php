<?php
namespace JetApplication;

use Jet\DataModel;
use Jet\DataModel_IDController_Name;
use Jet\DataModel_Related_MtoN_Iterator;
use Jet\Auth_Role_Interface;
use Jet\Data_Forest;
use Jet\Data_Tree;
use Jet\Tr;
use Jet\Form;
use Jet\Form_Field;
use Jet\DataModel_Fetch_Instances;
use Jet\Mvc_Page_Interface;

/**
 *
 * @JetDataModel:name = 'role'
 * @JetDataModel:id_controller_class_name = 'DataModel_IDController_Name'
 * @JetDataModel:database_table_name = 'roles_visitors'
 */
class Auth_Visitor_Role extends DataModel implements Auth_Role_Interface
{
	/**
	 * Privilege to sites/page
	 */
	const PRIVILEGE_VISIT_PAGE = 'visit_page';


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
	 * @JetDataModel:data_model_class = 'Auth_Visitor_Role_Privilege'
	 * @JetDataModel:form_field_is_required = false
	 *
	 * @var Auth_Visitor_Role_Privilege[]
	 */
	protected $privileges;


	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Auth_Visitor_Role_Users'
	 * @JetDataModel:form_field_type = false
	 *
	 * @var Auth_Visitor_User[]|DataModel_Related_MtoN_Iterator
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
	 * @return Auth_Visitor_Role
	 */
	public static function get( $id )
	{
		$role = static::load( $id );

		/**
		 * @var Auth_Visitor_Role $role
		 */
		return $role;
	}

	/**
	 *
	 * @param string $search
	 *
	 * @return DataModel_Fetch_Instances|Auth_Visitor_Role[]
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
		return $this->id;
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
	 * @return Auth_Visitor_User[]
	 */
	public function getUsers()
	{
		return $this->users;
	}

	/**
	 * @return Auth_Visitor_Role_Privilege[]
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
			static::PRIVILEGE_VISIT_PAGE
		];
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

		$form->field('/privileges/visit_page/values')->setSelectOptions(static::getAclActionValuesList_Pages());
		$form->field('/privileges/visit_page/values')->setLabel('Secret area access');


		return $form;
	}

	/**
	 *
	 * @return Data_Forest
	 */
	public static function getAclActionValuesList_Pages()
	{

		$forest = new Data_Forest();
		$forest->setIdKey( 'id' );
		$forest->setLabelKey( 'name' );


		$site = Application_Web::getSite();
		foreach( $site->getLocales() as $locale ) {

			$homepage = $site->getHomepage( $locale );

			$tree = new Data_Tree();
			$tree->setAdoptOrphans( true );

			$tree->getRootNode()->setId( $homepage->getKey() );
			$tree->getRootNode()->setLabel(
				$homepage->getSite()->getName().' ('.$homepage->getLocale()->getName().')'.' - '.$homepage->getName(
				)
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
	 * @param                    $data
	 */
	protected static function _getPagesTree( Mvc_Page_Interface $page, &$data )
	{

		if($page->getIsSecret()) {
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