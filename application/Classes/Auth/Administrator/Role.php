<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;


use Jet\Application_Modules;
use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_IDController_Passive;
use Jet\Auth_Role_Interface;
use Jet\Data_Forest;
use Jet\Data_Tree;
use Jet\Form_Definition;
use Jet\Form_Field_Input;
use Jet\Form_Field_MultiSelect;
use Jet\Tr;
use Jet\MVC;
use Jet\MVC_Page;
use Jet\Form;
use Jet\Form_Field;

/**
 *
 */
#[DataModel_Definition(
	name: 'role',
	id_controller_class: DataModel_IDController_Passive::class,
	database_table_name: 'roles_administrators'
)]
class Auth_Administrator_Role extends DataModel implements Auth_Role_Interface
{

	const PRIVILEGE_VISIT_PAGE = 'visit_page';
	const PRIVILEGE_MODULE_ACTION = 'module_action';


	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_ID,
		is_id: true,
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_INPUT,
		label: 'ID',
		is_required: true,
		error_messages: [
			Form_Field::ERROR_CODE_EMPTY => 'Please enter ID',
			'exists' => 'Sorry, but ID %ID% is used.'
		]
	)]
	protected string $id = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 100,
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_INPUT,
		is_required: true,
		label: 'Name',
		error_messages: [
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
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_TEXTAREA,
		label: 'Description',
	)]
	protected string $description = '';


	/**
	 * @var Auth_Administrator_Role_Privilege[]
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_DATA_MODEL,
		data_model_class: Auth_Administrator_Role_Privilege::class,
	)]
	protected array $privileges = [];


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
	 * @param ?string $search
	 *
	 * @return Auth_Administrator_Role[]
	 */
	public static function getList( ?string $search = '' ): iterable
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
	 *
	 * @param string $id
	 *
	 * @return bool
	 */
	public static function idExists( string $id ): bool
	{
		return (bool)static::getBackendInstance()->getCount( static::createQuery( [
			'id' => $id,
		] ) );
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
	 * @return Auth_Administrator_User[]
	 */
	public function getUsers(): iterable
	{
		return Auth_Administrator_User_Roles::getRoleUsers( $this->id );
	}

	/**
	 *
	 */
	public function afterDelete(): void
	{
		Auth_Administrator_User_Roles::roleDeleted($this->id);
	}

	/**
	 * @return Auth_Administrator_Role_Privilege[]
	 */
	public function getPrivileges(): array
	{
		return $this->privileges;
	}

	/**
	 * Data format:
	 *
	 * array(
	 *      'privilege' => ['value1', 'value2']
	 * )
	 *
	 * @param array $privileges
	 */
	public function setPrivileges( array $privileges ): void
	{
		foreach($this->privileges as $privilege) {
			$privilege->delete();
		}
		$this->privileges = [];

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
		if($values) {
			if( !isset( $this->privileges[$privilege] ) ) {
				$this->privileges[$privilege] = new Auth_Administrator_Role_Privilege( $privilege, $values );
			} else {
				$this->privileges[$privilege]->setValues( $values );
			}
		} else {
			if(isset($this->privileges[$privilege])) {
				$this->privileges[$privilege]->delete();
				unset($this->privileges[$privilege]);
			}
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
	public function hasPrivilege( string $privilege, mixed $value=null ): bool
	{
		if( !isset( $this->privileges[$privilege] ) ) {
			return false;
		}

		if($value===null) {
			return true;
		}

		return $this->privileges[$privilege]->hasValue( $value );
	}

	/**
	 * @return string[]
	 */
	public static function getAvailablePrivilegesList(): array
	{

		return [
			static::PRIVILEGE_VISIT_PAGE => [
				'label' => 'Administration sections',
				'options_getter' => 'getAclActionValuesList_Pages'
			],
			static::PRIVILEGE_MODULE_ACTION => [
				'label' => 'Modules and actions',
				'options_getter' => 'getAclActionValuesList_ModulesActions'
			],
		];
	}


	/**
	 * @return Data_Forest
	 */
	public static function getAclActionValuesList_ModulesActions(): Data_Forest
	{

		$forest = new Data_Forest();

		$modules = Application_Modules::activatedModulesList();

		foreach( $modules as $module_name => $module_info ) {
			if( str_ends_with($module_name, '.REST') ) {
				continue;
			}

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
	 * @return MVC_Page[]
	 */
	public static function getAclActionValuesList_Pages(): array
	{
		$pages = [];

		foreach( MVC::getPages( Application_Admin::getBaseId(), MVC::getLocale() ) as $page ) {
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
	 * @return Form
	 */
	public function _getForm(): Form
	{


		$form = $this->createForm('role_edit');

		if( $this->getIsNew() ) {
			$form->field('id')->setValidator(
				function( Form_Field_Input $field ) {
					$id = $field->getValue();

					if( static::idExists( $id ) ) {
						$field->setError('exists', ['ID' => $id]);
						return false;
					}

					return true;
				}
			);
		} else {
			$form->field('id')->setIsReadonly(true);
		}

		$available_privileges_list = static::getAvailablePrivilegesList();

		foreach($available_privileges_list as $priv=>$priv_data) {

			$values = isset($this->privileges[$priv]) ? $this->privileges[$priv]->getValues() : [];

			$field = new Form_Field_MultiSelect( '/privileges/'.$priv.'/values', $priv_data['label'] );
			$field->setDefaultValue($values);

			$field->setSelectOptions($this->{$priv_data['options_getter']}());

			$field->setErrorMessages([
				Form_Field::ERROR_CODE_INVALID_VALUE => 'Invalid value'
			]);

			$field->setFieldValueCatcher(function( $values) use ($priv) {
				$this->setPrivilege($priv, $values);
			});

			$form->addField($field);
		}

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
		return $this->getEditForm()->catch();
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
		return $this->getAddForm()->catch();
	}

}