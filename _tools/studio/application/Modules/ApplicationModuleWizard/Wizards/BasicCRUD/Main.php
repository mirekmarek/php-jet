<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\ApplicationModuleWizard\BasicCRUD;

use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;
use Jet\Http_Headers;
use Jet\Http_Request;

use Jet\MVC;
use Jet\Navigation_MenuSet;
use JetStudio\ClassMetaInfo;
use JetStudio\JetStudio;
use JetStudioModule\ApplicationModuleWizard\Wizard;
use JetStudioModule\ApplicationModuleWizard\ModuleWizards;


class Main extends Wizard
{

	protected string $title = 'Basic CRUD module for DataModel';
	
	protected string $description = 'Create basic module which allows to create, edit and delete data entity';
	
	protected string $data_model_class_name = '';
	
	protected ?Form $__select_data_model_form = null;
	
	/**
	 * @var ClassMetaInfo[]
	 */
	protected array $data_model_classes;
	
	public function init(): void
	{
		$this->data_model_classes = JetStudio::getModule_DataModel()?->getDataModelClasses()??[];
		
		$data_model_list = [];

		foreach( $this->data_model_classes as $class ) {
			$model = $class->getDefinition();

			$data_model_list[] = $class->getFullClassName();
		}

		$this->data_model_class_name = Http_Request::GET()->getString( 'data_model', '', $data_model_list );

		if( !$this->data_model_class_name ) {
			return;
		}

		//$class = $this->data_model_classes[ $this->data_model_class_name ];
		$model = DataModel_Definition::get( $this->data_model_class_name );

		$model_name = $model->getModelName();

		$def_name = explode( '_', $model_name );
		foreach( $def_name as $i => $n ) {
			$def_name[$i] = ucfirst( strtolower( $n ) );
		}

		$var_name = strtolower( $model_name );

		$class_alias = implode( '', $def_name );
		$def_name = implode( ' ', $def_name );


		$page_id = str_replace( '_', '-', $model_name );

		$this->values = [
			//'NAMESPACE' => '',
			'COPYRIGHT'   => '',
			'LICENSE'     => '',
			'AUTHOR'      => '',
			'LABEL'       => '',
			'DESCRIPTION' => '',

			'DATA_MODEL_CLASS_NAME'  => $this->data_model_class_name,
			'DATA_MODEL_CLASS_ALIAS' => $class_alias,

			'ACL_ENTITY_NAME'       => $var_name,

			'ITEM_VAR_NAME' => $var_name,

			'NAME_PROPERTY'    => '',
			'ITEM_NAME_GETTER' => '',

			'ID_PROPERTY'    => 'id',
			'ITEM_ID_GETTER' => 'getId',

			'TXT_BTN_NEW'                 => 'Create a new ' . $def_name,
			'TXT_DELETE_CONFIRM_QUESTION' => 'Do you really want to delete this ' . strtolower( $def_name ) . '?',
			'TXT_MSG_CREATED'             => $def_name . ' <b>%ITEM_NAME%</b> has been created',
			'TXT_MSG_UPDATED'             => $def_name . ' <b>%ITEM_NAME%</b> has been updated',
			'TXT_MSG_DELETED'             => $def_name . ' <b>%ITEM_NAME%</b> has been deleted',
			'TXT_BN_DETAIL'               => $def_name . ' detail <b>%ITEM_NAME%</b>',
			'TXT_BN_EDIT'                 => 'Edit ' . strtolower( $def_name ) . ' <b>%ITEM_NAME%</b>',
			'TXT_BN_DELETE'               => 'Delete ' . strtolower( $def_name ) . '  <b>%ITEM_NAME%</b>',

			'TXT_LISTING_TITLE_ID'   => 'ID',
			'TXT_LISTING_TITLE_NAME' => $def_name,

			'LOG_EVENT_CREATED' => $model_name . '_created',
			'LOG_EVENT_CREATED_MESSAGE' => $def_name . ' created',
			'LOG_EVENT_UPDATED' => $model_name . '_updated',
			'LOG_EVENT_UPDATED_MESSAGE' => $def_name . ' updated',
			'LOG_EVENT_DELETED' => $model_name . '_deleted',
			'LOG_EVENT_DELETED_MESSAGE' => $def_name . ' deleted',

			'PAGE_BASE_ID'       => 'admin',
			'PAGE_ID'            => $page_id,
			'PAGE_TITLE'         => $def_name . ' admín panel',
			'PAGE_ICON'          => '',
			'PAGE_PATH_FRAGMENT' => $page_id,

			'MENU_ITEM_ID'       => $page_id,
			'TARGET_MENU_SET_ID' => '',
			'TARGET_MENU_ID'     => ''

		];
		
	}
	
	public function getDataModelClassName(): string
	{
		return $this->data_model_class_name;
	}
	
	public function getSetupForm(): Form
	{
		parent::getSetupForm();


		if( $this->data_model_class_name ) {
			$this->__setup_form->setAction( ModuleWizards::getActionUrl( 'create', ['data_model' => $this->data_model_class_name] ) );

		}

		return $this->__setup_form;
	}
	
	public function generateSetupForm(): Form
	{
		$fields = [];

		$this->generateSetupForm_mainFields( $fields );

		$this->generateSetupForm_dataModel( $fields );
		$this->generateSetupForm_menuItem( $fields );
		$this->generateSetupForm_page( $fields );
		$this->generateSetupForm_texts( $fields );

		$this->generateSetupForm_logger( $fields );
		$this->generateSetupForm_ACL( $fields );


		$form = new Form( 'module_wizard_setup_form', $fields );

		foreach( $this->values as $k => $v ) {
			if( $form->fieldExists( $k ) ) {
				$form->field( $k )->setDefaultValue( $v );
			}
		}

		return $form;
	}
	
	public function generateSetupForm_logger( array &$fields ) : void
	{

		$scope = [
			'LOG_EVENT_CREATED' => 'Create event:',
			'LOG_EVENT_CREATED_MESSAGE' => 'Create event - message:',
			'LOG_EVENT_UPDATED' => 'Update event:',
			'LOG_EVENT_UPDATED_MESSAGE' => 'Update event - message:',
			'LOG_EVENT_DELETED' => 'Delete event:',
			'LOG_EVENT_DELETED_MESSAGE' => 'Delete event - message:',
		];

		foreach( $scope as $f => $title ) {

			$field = new Form_Field_Input( $f, $title );
			$field->setIsRequired( true );
			$field->setFieldValueCatcher( function( $value ) use ( $f ) {
				$this->values[$f] = $value;
			} );
			$field->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY => 'Please enter event name',
			] );
			$fields[] = $field;

		}

	}

	
	public function generateSetupForm_ACL( array &$fields ): void
	{
		$acl_entity_name = new Form_Field_Input( 'ACL_ENTITY_NAME', 'ACL entity name:' );
		$acl_entity_name->setIsRequired( true );
		$acl_entity_name->setValidationRegexp( '/^[a-z0-9\_]{2,}$/i' );
		$acl_entity_name->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY          => 'Please enter valid entity name',
			Form_Field::ERROR_CODE_INVALID_FORMAT => 'Please enter valid entity name',
		] );

		$acl_entity_name->setFieldValueCatcher( function( $value ) {
			$this->values['ACL_ENTITY_NAME'] = $value;
		} );
		$fields[] = $acl_entity_name;
		
	}
	
	public function generateSetupForm_dataModel( array &$fields ): void
	{

		if( !$this->data_model_class_name ) {
			return;
		}

		$data_model = DataModel_Definition::get( $this->data_model_class_name );

		$id_properties = [];
		$name_properties = [];
		//$grid_properties = [];

		foreach( $data_model->getProperties() as $property ) {
			if( $property->getIsId() ) {
				$id_properties[$property->getName()] = $property->getName();
			}

			if(
				$property->getType() != DataModel::TYPE_DATA_MODEL &&
				$property->getType() != DataModel::TYPE_CUSTOM_DATA
			) {
				$name_properties[$property->getName()] = $property->getName();
			}
		}
		


		$item_var_name = new Form_Field_Input( 'ITEM_VAR_NAME', 'Item variable name:' );
		$item_var_name->setIsRequired( true );
		$item_var_name->setFieldValueCatcher( function( $value ) {
			$this->values['ITEM_VAR_NAME'] = $value;
		} );
		$item_var_name->setValidationRegexp( '/^[a-z0-9\_]{2,}$/i' );
		$item_var_name->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY          => 'Please variable name',
			Form_Field::ERROR_CODE_INVALID_FORMAT => 'Please variable name',
		] );
		$fields[] = $item_var_name;


		$id_property_field = new Form_Field_Select( 'ID_PROPERTY', 'ID property:' );
		$id_property_field->setFieldValueCatcher( function( $value ) {
			$this->values['ID_PROPERTY'] = $value;
		} );
		$id_property_field->setIsRequired( true );
		$id_property_field->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY         => 'Please select ID property',
			Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select ID property',
		] );
		$id_property_field->setSelectOptions( $id_properties );
		$fields[] = $id_property_field;

		$item_id_getter = new Form_Field_Input( 'ITEM_ID_GETTER', 'ID getter:' );
		$item_id_getter->setIsRequired( true );
		$item_id_getter->setFieldValueCatcher( function( $value ) {
			$this->values['ITEM_ID_GETTER'] = $value;
		} );
		$item_id_getter->setValidationRegexp( '/^[a-z0-9\_]{2,}$/i' );
		$item_id_getter->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY          => 'Please getter method name',
			Form_Field::ERROR_CODE_INVALID_FORMAT => 'Please getter method name',
		] );
		$fields[] = $item_id_getter;


		$name_property_field = new Form_Field_Select( 'NAME_PROPERTY', 'Name property:' );
		$name_property_field->setFieldValueCatcher( function( $value ) {
			$this->values['NAME_PROPERTY'] = $value;
		} );
		$name_property_field->setIsRequired( true );
		$name_property_field->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY         => 'Please select name property',
			Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select name property',
		] );
		$name_property_field->setSelectOptions( $name_properties );
		$fields[] = $name_property_field;


		$item_name_getter = new Form_Field_Input( 'ITEM_NAME_GETTER', 'Name getter:' );
		$item_name_getter->setIsRequired( true );
		$item_name_getter->setFieldValueCatcher( function( $value ) {
			$this->values['ITEM_NAME_GETTER'] = $value;
		} );
		$item_name_getter->setValidationRegexp( '/^[a-z0-9\_]{2,}$/i' );
		$item_name_getter->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY          => 'Please getter method name',
			Form_Field::ERROR_CODE_INVALID_FORMAT => 'Please getter method name',
		] );
		$fields[] = $item_name_getter;


		$data_model_class_name = new Form_Field_Input( 'DATA_MODEL_CLASS_NAME', 'DataModel class name:' );
		$data_model_class_name->setIsReadonly( true );
		$fields[] = $data_model_class_name;

		$data_model_class_alias = new Form_Field_Input( 'DATA_MODEL_CLASS_ALIAS', 'DataModel class alias:' );
		$data_model_class_alias->setIsRequired( true );
		$data_model_class_alias->setFieldValueCatcher( function( $value ) {
			$this->values['DATA_MODEL_CLASS_ALIAS'] = $value;
		} );
		$data_model_class_alias->setValidationRegexp( '/^[a-z0-9\_]{2,}$/i' );
		$data_model_class_alias->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY          => 'Please enter class alias',
			Form_Field::ERROR_CODE_INVALID_FORMAT => 'Please enter class alias',
		] );
		$fields[] = $data_model_class_alias;


		$data_model_class_alias = new Form_Field_Input( 'DATA_MODEL_CLASS_ALIAS', 'DataModel class alias:' );
		$data_model_class_alias->setIsRequired( true );
		$data_model_class_alias->setFieldValueCatcher( function( $value ) {
			$this->values['DATA_MODEL_CLASS_ALIAS'] = $value;
		} );
		$data_model_class_alias->setValidationRegexp( '/^[a-z0-9\_]{2,}$/i' );
		$data_model_class_alias->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY          => 'Please enter class alias',
			Form_Field::ERROR_CODE_INVALID_FORMAT => 'Please enter class alias',
		] );
		$fields[] = $data_model_class_alias;


	}
	
	public function generateSetupForm_page( array &$fields ): void
	{

		$bases_list = ['' => ''];

		foreach( MVC::getBases() as $base ) {
			$bases_list[$base->getId()] = $base->getName();
		}

		$page_base_id_field = new Form_Field_Select( 'PAGE_BASE_ID', 'Base:' );
		$page_base_id_field->setFieldValueCatcher( function( $value ) {
			$this->values['PAGE_BASE_ID'] = $value;
		} );
		$page_base_id_field->setIsRequired( true );
		$page_base_id_field->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY         => 'Please select base',
			Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select base',
		] );
		$page_base_id_field->setSelectOptions( $bases_list );
		$fields[] = $page_base_id_field;


		/*
		$page_name_field = new Form_Field_Input('page_name', 'Name:', $this->page_name);
		$page_name_field->setIsRequired(true);
		$page_name_field->setCatcher( function($value) {
			$this->page_name = $value;
		} );
		$page_name_field->setErrorMessages([
			Form_Field::ERROR_CODE_EMPTY => 'Please enter page name',
		]);
		$fields[] = $page_name_field;
		*/


		$page_id_field = new Form_Field_Input( 'PAGE_ID', 'ID:' );
		$page_id_field->setIsRequired( true );
		$page_id_field->setFieldValueCatcher( function( $value ) {
			$this->values['PAGE_ID'] = $value;
		} );
		$page_id_field->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY => 'Please enter page ID',
		] );
		$fields[] = $page_id_field;


		$page_title_field = new Form_Field_Input( 'PAGE_TITLE', 'Title:' );
		$page_title_field->setIsRequired( true );
		$page_title_field->setFieldValueCatcher( function( $value ) {
			$this->values['PAGE_TITLE'] = $value;
		} );
		$page_title_field->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY => 'Please enter page title',
		] );
		$fields[] = $page_title_field;


		$page_icon_field = new Form_Field_Input( 'PAGE_ICON', 'Icon:' );
		$page_icon_field->setFieldValueCatcher( function( $value ) {
			$this->values['PAGE_ICON'] = $value;
		} );
		$fields[] = $page_icon_field;


		$page_relative_path_fragment_field = new Form_Field_Input( 'PAGE_PATH_FRAGMENT', 'URL:' );
		$page_relative_path_fragment_field->setIsRequired( true );
		$page_relative_path_fragment_field->setFieldValueCatcher( function( $value ) {
			$this->values['PAGE_PATH_FRAGMENT'] = $value;
		} );
		$page_relative_path_fragment_field->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY => 'Please enter page URL',
		] );
		$fields[] = $page_relative_path_fragment_field;

	}
	
	public function generateSetupForm_menuItem( array &$fields ): void
	{
		$menus_list = ['' => ''];

		foreach( Navigation_MenuSet::getList() as $set ) {
			foreach( $set->getMenus() as $menu ) {
				$id = $set->getName() . '/' . $menu->getId();
				$menus_list[$id] = $set->getName() . ' / ' . $menu->getLabel();
			}
		}

		$menu_item_target_menu_id_field = new Form_Field_Select( 'TARGET_MENU', 'Target menu:' );
		$menu_item_target_menu_id_field->setFieldValueCatcher( function( $value ) {
			[
				$this->values['TARGET_MENU_SET_ID'],
				$this->values['TARGET_MENU_ID']
			] = explode( '/', $value );
		} );
		$menu_item_target_menu_id_field->setIsRequired( true );
		$menu_item_target_menu_id_field->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY         => 'Please select menu',
			Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select menu',
		] );
		$menu_item_target_menu_id_field->setSelectOptions( $menus_list );
		$fields[] = $menu_item_target_menu_id_field;

		$menu_item_id_field = new Form_Field_Input( 'MENU_ITEM_ID', 'ID:' );
		$menu_item_id_field->setIsRequired( true );
		$menu_item_id_field->setFieldValueCatcher( function( $value ) {
			$this->values['MENU_ITEM_ID'] = $value;
		} );
		$menu_item_id_field->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY => 'Please enter menu item ID',
		] );
		$fields[] = $menu_item_id_field;


	}


	public function generateSetupForm_texts( array &$fields ): void
	{

		$scope = [
			'TXT_MSG_CREATED' => 'Info message - item has been created:',
			'TXT_MSG_UPDATED' => 'Info message - item has been updated:',
			'TXT_MSG_DELETED' => 'Info message - item has been deleted:',

			'TXT_BN_DETAIL' => 'Breadcrumb navigation - detail:',
			'TXT_BN_EDIT'   => 'Breadcrumb navigation - edit:',
			'TXT_BN_DELETE' => 'Breadcrumb navigation - delete confirmation:',

			'TXT_LISTING_TITLE_ID'   => 'Listing - column title - ID:',
			'TXT_LISTING_TITLE_NAME' => 'Listing - column title - name:',

			'TXT_DELETE_CONFIRM_QUESTION' => 'Delete - confirmation message:',

			'TXT_BTN_NEW' => 'Button - create',
		];

		foreach( $scope as $f => $title ) {

			$field = new Form_Field_Input( $f, $title );
			$field->setIsRequired( true );
			$field->setFieldValueCatcher( function( $value ) use ( $f, $field ) {
				$this->values[$f] = $field->getValueRaw();
			} );
			$field->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY => 'Please enter text',
			] );
			$fields[] = $field;

		}
	}
	
	public function getSelectDataModelForm(): Form
	{

		if( !$this->__select_data_model_form ) {
			$data_model_list = ['' => ''];

			foreach( $this->data_model_classes as $class ) {
				$model = DataModel_Definition::get($class->getFullClassName());

				$label = $model->getModelName() . ' / ' . $model->getClassName();

				$data_model_list[$model->getClassName()] = $label;
			}

			$data_model_field = new Form_Field_Select( 'data_model', 'Select DataModel:' );
			$data_model_field->setDefaultValue( $this->data_model_class_name );
			$data_model_field->setFieldValueCatcher( function( $value ) {
				$this->data_model_class_name = $value;
			} );
			$data_model_field->setIsRequired( true );
			$data_model_field->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY         => 'Please select DataModel',
				Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select DataModel',
			] );
			$data_model_field->setSelectOptions( $data_model_list );
			$fields[] = $data_model_field;

			$form = new Form( 'select_data_model_form', $fields );

			$form->setAction( ModuleWizards::getActionUrl( 'select_data_model' ) );

			$this->__select_data_model_form = $form;
		}


		return $this->__select_data_model_form;

	}
	
	public function catchSelectModelForm(): void
	{
		$form = $this->getSelectDataModelForm();
		
		if( $form->catchInput() && $form->validate() ) {
			$data_model_id = $form->field( 'data_model' )->getValue();

			Http_Headers::reload( ['data_model' => $data_model_id], unset_GET_params: ['wizard_action'] );
		}
	}
	
	
	public function create_generateFiles( string $target_dir ) : void
	{
		JetStudio::getModule_Forms()?->generateViewFile(
			$this->data_model_class_name,
			$target_dir.'/views/edit.phtml'
		);
	}
	
}