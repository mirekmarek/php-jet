<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetStudio\ModuleWizard\BasicAdminDataHandler;

use Jet\DataModel;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Form_Field_Int;
use Jet\Form_Field_MultiSelect;
use Jet\Form_Field_Select;
use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Mvc_Layout;

use JetStudio\DataModel_Definition_Model_Main;
use JetStudio\DataModels;
use JetStudio\Menus;
use JetStudio\Menus_Menu_Item;
use JetStudio\Modules;
use JetStudio\ModuleWizard;
use JetStudio\ModuleWizards;
use JetStudio\Pages_Page;
use JetStudio\Pages_Page_Content;
use JetStudio\Sites;

/**
 *
 */
class Wizard extends ModuleWizard {

	/**
	 * @var string
	 */
	protected $title = 'Basic administration module for DataModel';

	/**
	 * @var string
	 */
	protected $description = 'Create basic module which allows to create, edit and delete data entity';

	/**
	 * @var bool
	 */
	protected $is_ready = false;

	/**
	 * @var string
	 */
	protected $data_model_class_name = '';

	/**
	 * @var string
	 */
	protected $id_property = '';

	/**
	 * @var string
	 */
	protected $name_property = '';

	/**
	 * @var array
	 */
	protected $grid_properties = [];

	/**
	 * @var string 
	 */
	protected $entity_name = '';




	/**
	 * @var string
	 */
	protected $controller_name = 'Main';


	/**
	 * @var string
	 */
	protected $edit_view = 'edit';

	/**
	 * @var string
	 */
	protected $list_view = 'list';


	/**
	 * @var string
	 */
	protected $page_site_id = 'admin';

	/**
	 * @var string
	 */
	protected $page_name = '';

	/**
	 * @var string
	 */
	protected $page_id = '';

	/**
	 * @var string
	 */
	protected $page_title = '';

	/**
	 * @var string
	 */
	protected $page_icon = '';

	/**
	 * @var string
	 */
	protected $page_relative_path_fragment = '';






	/**
	 * @var string
	 */
	protected $menu_item_target_menu_id = '';

	/**
	 * @var string
	 */
	protected $menu_item_id = '';

	/**
	 * @var string
	 */
	protected $menu_item_title = '';

	/**
	 * @var string
	 */
	protected $menu_item_icon = '';

	/**
	 * @var int
	 */
	protected $menu_item_index = 0;




	/**
	 * @var string
	 */
	protected $text_item_created = 'Item <b>%NAME%</b> has been created';

	/**
	 * @var string
	 */
	protected $text_edit_item = 'Edit item <b>%NAME%</b>';

	/**
	 * @var string
	 */
	protected $text_item_updated = 'Item <b>%NAME%</b> has been updated';

	/**
	 * @var string
	 */
	protected $text_item_detail = 'Item detail <b>%NAME%</b>';

	/**
	 * @var string
	 */
	protected $text_item_deleted = 'Item <b>%NAME%</b> has been deleted';

	/**
	 * @var string
	 */
	protected $text_crete_bt_label = 'Create new item';

	/**
	 * @var string
	 */
	protected $text_delete_bt_label = 'Delete selected items';



	/**
	 * @var Form
	 */
	protected $select_data_model_form;

	

	/**
	 *
	 */
	public function init()
	{
		$data_model_list = [];

		foreach( DataModels::getClasses() as $class ) {
			$model = $class->getDefinition();
			if(!$model instanceof DataModel_Definition_Model_Main) {
				continue;
			}

			$data_model_list[] = $class->getFullClassName();
		}

		$this->data_model_class_name = Http_Request::GET()->getString('data_model', '', $data_model_list);

	}

	/**
	 * @return string
	 */
	public function getDataModelClassname()
	{
		return $this->data_model_class_name;
	}


	/**
	 * @return bool
	 */
	public function isReady() {
		return $this->is_ready;
	}

	/**
	 * @return Form
	 */
	public function getSelectDataModelForm()
	{

		if(!$this->select_data_model_form) {
			$data_model_list = ['' => ''];

			foreach( DataModels::getClasses() as $class ) {
				$model = $class->getDefinition();
				if(!$model instanceof DataModel_Definition_Model_Main) {
					continue;
				}

				$label = $model->getModelName().' / '.$model->getClassName();

				$data_model_list[$model->getClassName()] = $label;
			}

			$data_model_field = new Form_Field_Select('data_model', 'Select DataModel:', $this->data_model_class_name );
			$data_model_field->setCatcher( function($value) {
				$this->data_model_class_name = $value;
			} );
			$data_model_field->setIsRequired(true);
			$data_model_field->setErrorMessages([
				Form_Field_Select::ERROR_CODE_EMPTY => 'Please select DataModel',
				Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select DataModel',
			]);
			$data_model_field->setSelectOptions( $data_model_list );
			$fields[] = $data_model_field;

			$form = new Form('select_data_model_form', $fields);

			$form->setAction( ModuleWizards::getActionUrl('select_data_model') );

			$this->select_data_model_form = $form;
		}


		return $this->select_data_model_form;

	}

	/**
	 *
	 */
	public function catchSetupForm()
	{
		$form = $this->getSelectDataModelForm();

		if($form->catchInput() && $form->validate()) {
			$data_model_id = $form->field('data_model')->getValue();

			Http_Headers::reload(['data_model'=>$data_model_id]);
		}
	}

	/**
	 * @return Form
	 */
	public function getSetupForm()
	{
		parent::getSetupForm();


		if($this->data_model_class_name) {
			$this->setup_form->setAction( ModuleWizards::getActionUrl('setup') );

		}

		return $this->setup_form;
	}


	/**
	 * @return Form
	 */
	public function generateSetupForm()
	{

		$fields = [];


		$this->generateSetupForm_dataModel( $fields );
		$this->generateSetupForm_page( $fields );
		$this->generateSetupForm_menuItem( $fields );
		$this->generateSetupForm_texts( $fields );
		$this->generateSetupForm_module( $fields );


		$form = new Form('module_wizard_setup_form', $fields);

		return $form;
	}

	/**
	 * @param array $fields
	 */
	public function generateSetupForm_dataModel( array &$fields )
	{

		if(!$this->data_model_class_name) {
			return;
		}

		$class = DataModels::getClass( $this->data_model_class_name );
		if(!$class) {
			return;
		}

		$data_model = $class->getDefinition();

		$id_properties = [];
		$name_properties = [];
		$grid_properties = [];

		foreach( $data_model->getProperties() as $property ) {
			if($property->getIsId()) {
				$id_properties[ $property->getName() ] = $property->getName();
			} else {
				if(
					$property->getType()!=DataModel::TYPE_DATA_MODEL &&
					$property->getType()!=DataModel::TYPE_CUSTOM_DATA
				) {
					$name_properties[$property->getName()] = $property->getName();
				}
			}

			if(
				$property->getType()!=DataModel::TYPE_DATA_MODEL &&
				$property->getType()!=DataModel::TYPE_CUSTOM_DATA
			) {
				$grid_properties[$property->getName()] = $property->getName();
			}

		}

		if(!$this->entity_name) {
			$this->entity_name = $data_model->getModelName();
		}


		$entity_name_field = new Form_Field_Input('entity_name', 'Entity name:', $this->entity_name);
		$entity_name_field->setIsRequired(true);
		$entity_name_field->setCatcher( function($value) {
			$this->entity_name = $value;
		} );
		$entity_name_field->setErrorMessages([
			Form_Field_Select::ERROR_CODE_EMPTY => 'Please enter entity name',
		]);
		$fields[] = $entity_name_field;
		
		

		$id_property_field = new Form_Field_Select('id_property', 'ID property:', $this->id_property);
		$id_property_field->setCatcher( function($value) {
			$this->id_property = $value;
		} );
		$id_property_field->setIsRequired(true);
		$id_property_field->setErrorMessages([
			Form_Field_Select::ERROR_CODE_EMPTY => 'Please select ID property',
			Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select ID property',
		]);
		$id_property_field->setSelectOptions( $id_properties );
		$fields[] = $id_property_field;





		$name_property_field = new Form_Field_Select('name_property', 'Name property:', $this->name_property);
		$name_property_field->setCatcher( function($value) {
			$this->name_property = $value;
		} );
		$name_property_field->setIsRequired(true);
		$name_property_field->setErrorMessages([
			Form_Field_Select::ERROR_CODE_EMPTY => 'Please select name property',
			Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select name property',
		]);
		$name_property_field->setSelectOptions( $name_properties );
		$fields[] = $name_property_field;



		if(!$this->grid_properties) {
			$this->grid_properties = array_keys($grid_properties);
		}
		
		$grid_properties_field = new Form_Field_MultiSelect('grid_properties', 'Grid properties:', $this->grid_properties);
		$grid_properties_field->setCatcher( function($value) {
			$this->grid_properties = $value;
		} );
		$grid_properties_field->setIsRequired(true);
		$grid_properties_field->setErrorMessages([
			Form_Field_Select::ERROR_CODE_EMPTY => 'Please select name property',
			Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select name property',
		]);
		$grid_properties_field->setSelectOptions( $grid_properties );
		$fields[] = $grid_properties_field;
		
	}

	/**
	 * @param array $fields
	 */
	public function generateSetupForm_page( array &$fields )
	{
		$sites_list = [''=>''];

		foreach( Sites::getSites() as $site ) {
			$sites_list[$site->getId()] = $site->getName();
		}

		$page_site_id_field = new Form_Field_Select('page_site_id', 'Site:', $this->page_site_id);
		$page_site_id_field->setCatcher( function($value) {
			$this->page_site_id = $value;
		} );
		$page_site_id_field->setIsRequired(true);
		$page_site_id_field->setErrorMessages([
			Form_Field_Select::ERROR_CODE_EMPTY => 'Please select site',
			Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select site',
		]);
		$page_site_id_field->setSelectOptions( $sites_list );
		$fields[] = $page_site_id_field;


		
		
		$page_name_field = new Form_Field_Input('page_name', 'Name:', $this->page_name);
		$page_name_field->setIsRequired(true);
		$page_name_field->setCatcher( function($value) {
			$this->page_name = $value;
		} );
		$page_name_field->setErrorMessages([
			Form_Field_Select::ERROR_CODE_EMPTY => 'Please enter page name',
		]);
		$fields[] = $page_name_field;

		
		
		$page_id_field = new Form_Field_Input('page_id', 'ID:', $this->page_id);
		$page_id_field->setIsRequired(true);
		$page_id_field->setCatcher( function($value) {
			$this->page_id = $value;
		} );
		$page_id_field->setErrorMessages([
			Form_Field_Select::ERROR_CODE_EMPTY => 'Please enter page ID',
		]);
		$fields[] = $page_id_field;




		$page_title_field = new Form_Field_Input('page_title', 'Title:', $this->page_title);
		$page_title_field->setIsRequired(true);
		$page_title_field->setCatcher( function($value) {
			$this->page_title = $value;
		} );
		$page_title_field->setErrorMessages([
			Form_Field_Select::ERROR_CODE_EMPTY => 'Please enter page title',
		]);
		$fields[] = $page_title_field;




		$page_icon_field = new Form_Field_Input('page_icon', 'Icon:', $this->page_icon);
		$page_icon_field->setCatcher( function($value) {
			$this->page_icon = $value;
		} );
		$fields[] = $page_icon_field;



		$page_relative_path_fragment_field = new Form_Field_Input('page_relative_path_fragment', 'URL:', $this->page_relative_path_fragment);
		$page_relative_path_fragment_field->setIsRequired(true);
		$page_relative_path_fragment_field->setCatcher( function($value) {
			$this->page_relative_path_fragment = $value;
		} );
		$page_relative_path_fragment_field->setErrorMessages([
			Form_Field_Select::ERROR_CODE_EMPTY => 'Please enter page URL',
		]);
		$fields[] = $page_relative_path_fragment_field;
		
	}


	/**
	 * @param array $fields
	 */
	public function generateSetupForm_menuItem( array &$fields )
	{
		$menus_list = [''=>''];

		foreach( Menus::getSets() as $set ) {
			foreach( $set->getMenus() as $menu ) {
				$id = $set->getName().'/'.$menu->getId();
				$menus_list[$id] = $set->getName().' / '.$menu->getLabel();
			}
		}

		$menu_item_target_menu_id_field = new Form_Field_Select('menu_item_target_menu_id', 'Target menu:', $this->menu_item_target_menu_id);
		$menu_item_target_menu_id_field->setCatcher( function($value) {
			$this->menu_item_target_menu_id = $value;
		} );
		$menu_item_target_menu_id_field->setIsRequired(true);
		$menu_item_target_menu_id_field->setErrorMessages([
			Form_Field_Select::ERROR_CODE_EMPTY => 'Please select menu',
			Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select menu',
		]);
		$menu_item_target_menu_id_field->setSelectOptions( $menus_list );
		$fields[] = $menu_item_target_menu_id_field;

		$menu_item_id_field = new Form_Field_Input('menu_item_id', 'ID:', $this->menu_item_id);
		$menu_item_id_field->setIsRequired(true);
		$menu_item_id_field->setCatcher( function($value) {
			$this->menu_item_id = $value;
		} );
		$menu_item_id_field->setErrorMessages([
			Form_Field_Select::ERROR_CODE_EMPTY => 'Please enter menu item ID',
		]);
		$fields[] = $menu_item_id_field;
		

		$menu_item_title_field = new Form_Field_Input('menu_item_title', 'Title:', $this->menu_item_title);
		$menu_item_title_field->setIsRequired(true);
		$menu_item_title_field->setCatcher( function($value) {
			$this->menu_item_title = $value;
		} );
		$menu_item_title_field->setErrorMessages([
			Form_Field_Select::ERROR_CODE_EMPTY => 'Please enter menu item title',
		]);
		$fields[] = $menu_item_title_field;



		$menu_item_icon_field = new Form_Field_Input('menu_item_icon', 'Icon:', $this->menu_item_icon);
		$menu_item_icon_field->setCatcher( function($value) {
			$this->menu_item_icon = $value;
		} );
		$fields[] = $menu_item_icon_field;



		$menu_item_index_field = new Form_Field_Int('menu_item_index', 'Index:', $this->menu_item_index);
		$menu_item_index_field->setCatcher( function($value) {
			$this->menu_item_index = $value;
		} );
		$fields[] = $menu_item_index_field;
		
	}


	/**
	 * @param array $fields
	 */
	public function generateSetupForm_texts( array &$fields )
	{

		$scope = [
			'text_item_created' => 'Item created:',
			'text_edit_item' => 'Edit item:',
			'text_item_updated' => 'Item updated:',
			'text_item_detail' => 'Item detail:',
			'text_item_deleted' => 'Item deleted:',

			'text_crete_bt_label' => 'Button - create',
			'text_delete_bt_label' => 'Button - delete',

		];

		foreach( $scope as $f=>$title ) {

			$field = new Form_Field_Input($f, $title, $this->{$f});
			$field->setIsRequired(true);
			$field->setCatcher( function($value) use ($f) {
				$this->{$f} = $value;
			} );
			$field->setErrorMessages([
				Form_Field_Select::ERROR_CODE_EMPTY => 'Please enter text',
			]);
			$fields[] = $field;

		}

	}

	/**
	 * @param array $fields
	 */
	public function generateSetupForm_module( array &$fields )
	{
		$controller_name_field = new Form_Field_Input('controller_name', 'Controller name:', $this->controller_name);
		$controller_name_field->setCatcher( function($value) {
			$this->controller_name = $value;
		} );
		$controller_name_field->setIsRequired(true);
		$controller_name_field->setErrorMessages([
			Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter controller name',
		]);
		$fields[] = $controller_name_field;




		$list_view_field = new Form_Field_Input('list_view', 'View - list:', $this->list_view);
		$list_view_field->setCatcher( function($value) {
			$this->list_view = $value;
		} );
		$list_view_field->setIsRequired(true);
		$list_view_field->setErrorMessages([
			Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter list view name',
		]);
		$fields[] = $list_view_field;




		$edit_view_field = new Form_Field_Input('edit_view', 'View - edit:', $this->edit_view);
		$edit_view_field->setCatcher( function($value) {
			$this->edit_view = $value;
		} );
		$edit_view_field->setIsRequired(true);
		$edit_view_field->setErrorMessages([
			Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter edit view name',
		]);
		$fields[] = $edit_view_field;
	}
	

	/**
	 * @return bool
	 */
	public function create()
	{
		$this->create_ACLActions();
		$this->create_page();
		$this->create_menuItem();

		$this->create_generate();

		return false;
	}

	/**
	 *
	 */
	public function create_ACLActions()
	{
		$module = Modules::getCurrentModule();

		$add_actions = [
			'get' => 'Get '.$this->entity_name,
			'add' => 'Add '.$this->entity_name,
			'update' => 'Update '.$this->entity_name,
			'delete' => 'Delete '.$this->entity_name,
		];

		$actions = $module->getACLActions( false );

		foreach( $add_actions as $action=>$action_title ) {
			if(!isset($actions[$action])) {
				$actions[$action] = $action_title;
			}
		}

		$module->setACLActions( $actions );

		$module->save();
	}



	/**
	 *
	 */
	public function create_page()
	{
		$module = Modules::getCurrentModule();

		if($module->getPage( $this->page_site_id, $this->page_id )) {
			return;
		}

		$page = new Pages_Page();

		$page->setSiteId( $this->page_site_id );
		$page->setId( $this->page_id );

		$page->setName( $this->page_name );
		$page->setTitle( $this->page_title );
		$page->setIcon( $this->page_icon );
		$page->setRelativePathFragment( $this->page_relative_path_fragment );

		$content = new Pages_Page_Content();
		$content->setModuleName( $module->getName() );
		$content->setControllerName('Main');
		$content->setControllerAction('default');
		$content->setOutputPosition( Mvc_Layout::DEFAULT_OUTPUT_POSITION );

		$page->addContent( $content );


		$module->addPage( $this->page_site_id, $page );

		$module->save();
	}

	/**
	 *
	 */
	public function create_menuItem()
	{

		$module = Modules::getCurrentModule();

		[$set_id, $menu_id] = explode('/', $this->menu_item_target_menu_id);

		if(isset($module->getMenuItemsList($set_id, $menu_id)[$this->menu_item_id])) {
			return;
		}


		$menu_item = new Menus_Menu_Item(
			$this->menu_item_id,
			$this->menu_item_title
		);

		$menu_item->setSetId( $set_id );
		$menu_item->setMenuId( $menu_id );

		$menu_item->setIcon( $this->menu_item_icon );
		$menu_item->setIndex( $this->menu_item_index );
		$menu_item->setPageId( $this->page_id );

		$module->addMenuItem( $menu_item );

		$module->save();
	}

	/**
	 *
	 */
	public function create_generate()
	{
		$module = Modules::getCurrentModule();

		//TODO:

		die();
	}



}