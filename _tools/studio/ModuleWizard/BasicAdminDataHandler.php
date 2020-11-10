<?php
namespace JetStudioModuleWizard;

use Jet\Data_Text;
use Jet\DataModel;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Form_Field_Int;
use Jet\Form_Field_MultiSelect;
use Jet\Form_Field_Select;

use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\IO_File;
use Jet\Mvc_Layout;

use JetStudio\ClassCreator_Class_Method;
use JetStudio\ClassCreator_Class_Property;
use JetStudio\ClassCreator_UseClass;
use JetStudio\DataModels;
use JetStudio\DataModels_Model;
use JetStudio\DataModels_Property;
use JetStudio\Menus;
use JetStudio\Menus_MenuNamespace_Menu_Item;
use JetStudio\Modules;
use JetStudio\Modules_Manifest;
use JetStudio\Modules_Module_Controller;
use JetStudio\Modules_Module_Controller_Action;
use JetStudio\Modules_Wizard;
use JetStudio\Pages_Page;
use JetStudio\Pages_Page_Content;
use JetStudio\Project;
use JetStudio\Sites;
use JetStudio\ClassCreator_Class;
use JetStudio\Pages;
use Jet\AJAX;

//TODO: blbne generovani hlasek (metoda pro %NAME%)
/**
 *
 */
class BasicAdminDataHandler extends Modules_Wizard {

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
	protected $data_model_id = '';

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

		foreach( DataModels::getModels() as $model ) {
			if(!$model instanceof DataModels_Model) {
				continue;
			}

			$data_model_list[] = $model->getInternalId();
		}

		$this->data_model_id = Http_Request::GET()->getString('data_model', '', $data_model_list);

	}

	/**
	 * @return string
	 */
	public function getDataModelId()
	{
		return $this->data_model_id;
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
			$namespaces = Project::getNamespaces();

			foreach( DataModels::getModels() as $model ) {
				if(!$model instanceof DataModels_Model) {
					continue;
				}

				$label = $namespaces[$model->getNamespaceId()]->getLabel();

				$label .= ' / ';
				$label .= $model->getClassName();

				$data_model_list[$model->getInternalId()] = $label;
			}

			$data_model_field = new Form_Field_Select('data_model', 'Select DataModel:', $this->data_model_id );
			$data_model_field->setCatcher( function($value) {
				$this->data_model_id = $value;
			} );
			$data_model_field->setIsRequired(true);
			$data_model_field->setErrorMessages([
				Form_Field_Select::ERROR_CODE_EMPTY => 'Please select DataModel',
				Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select DataModel',
			]);
			$data_model_field->setSelectOptions( $data_model_list );
			$fields[] = $data_model_field;

			$form = new Form('select_data_model_form', $fields);
			$form->setAction( Modules::getActionUrl('module_wizard/setup', [ 'wizard'=>$this->getName() ]) );

			$this->select_data_model_form = $form;
		}


		return $this->select_data_model_form;

	}

	/**
	 * @return bool
	 */
	public function catchSetupForm()
	{
		$GET = Http_Request::GET();
		switch( $GET->getString('setup_action') ) {
			case 'generate_page_id':
				$name = $GET->getString('name');

				$id = Project::generateIdentifier( $name, function( $id ) {
					return Pages::exists( $id );
				} );

				AJAX::response(
					[
						'id' => $id
					]
				);

				break;
		}

		$form = $this->getSelectDataModelForm();

		if($form->catchInput() && $form->validate()) {
			$data_model_id = $form->field('data_model')->getValue();

			Http_Headers::reload(['data_model'=>$data_model_id]);
		}

		if(parent::catchSetupForm()) {
			$this->is_ready = true;

			return true;
		}

		return false;
	}

	/**
	 * @return Form
	 */
	public function getSetupForm()
	{
		parent::getSetupForm();


		if($this->data_model_id) {
			$this->setup_form->setAction( Modules::getActionUrl('module_wizard/setup', [
				'wizard'=>$this->getName(),
				'data_model'=>$this->data_model_id
			]) );

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


		$data_model = DataModels::getModel( $this->data_model_id );
		if(!$data_model) {
			return;
		}

		$id_properties = [];
		$name_properties = [];
		$grid_properties = [];

		foreach( $data_model->getProperties() as $property ) {
			if($property->getIsId()) {
				$id_properties[ $property->getInternalId() ] = $property->getName();
			} else {
				if(
					$property->getType()!=DataModel::TYPE_DATA_MODEL &&
					$property->getType()!=DataModel::TYPE_CUSTOM_DATA
				) {
					$name_properties[$property->getInternalId()] = $property->getName();
				}
			}

			if(
				$property->getType()!=DataModel::TYPE_DATA_MODEL &&
				$property->getType()!=DataModel::TYPE_CUSTOM_DATA
			) {
				$grid_properties[$property->getInternalId()] = $property->getName();
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

		foreach( Menus::getMenuNamespaces() as $namespace ) {
			foreach( $namespace->getMenus() as $menu ) {
				$id = $namespace->getInternalId().'/'.$menu->getId();
				$menus_list[$id] = $namespace->getName().' / '.$menu->getLabel();
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
		$this->create_Controller();
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
	public function create_Controller()
	{
		$module = Modules::getCurrentModule();

		$controllers = $module->getControllers();

		$controller = null;
		foreach( $controllers as $c ) {
			if($c->getName()==$this->controller_name) {
				$controller = $c;
				break;
			}
		}

		if(!$controller) {
			$controller = new Modules_Module_Controller();
			$controller->setName( $this->controller_name );
			$module->addController( $controller );
		}
		$controller->setExtendsClass('Jet\Mvc_Controller_Default');

		$actions = [
			'default' => 'get',
			'view' => 'get',
			'add' => 'add',
			'edit' => 'update',
			'delete' => 'delete'
		];

		foreach( $actions as $c_action=>$acl_action ) {
			$has = false;

			foreach( $controller->getActions() as $action ) {
				if($action->getControllerAction()==$c_action) {

					$action->setACLAction($acl_action);

					$has = true;
					break;
				}
			}

			if(!$has) {
				$action = new Modules_Module_Controller_Action();
				$action->setControllerAction( $c_action );
				$action->setACLAction( $acl_action );

				$controller->addAction( $action );
			}
		}


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
		$content->setModuleName( $module->getInternalId() );
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

		[$namespace_id, $menu_id] = explode('/', $this->menu_item_target_menu_id);

		if(isset($module->getMenuItemsList($namespace_id, $menu_id)[$this->menu_item_id])) {
			return;
		}


		$menu_item = new Menus_MenuNamespace_Menu_Item(
			$this->menu_item_id,
			$this->menu_item_title
		);

		$menu_item->setNamespaceId( $namespace_id );
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

		$module->generate_mainClass();
		$module->generate_manifest();

		$this->create_generate_dataModel();

		foreach( $module->getControllers() as $controller ) {

			if($controller->getName()==$this->controller_name) {
				$class = $this->create_generate_controller( $module, $controller );

				$class->write( $module->getModuleDir().$controller->getScriptName() );

			} else {
				$module->generate_controller( $controller );
			}
		}


		$this->create_generate_controllerRouter();
		$this->create_generate_view_list();
		$this->create_generate_view_edit();

	}

	/**
	 *
	 */
	public function create_generate_dataModel()
	{
		$data_model = DataModels::getModel( $this->data_model_id );

		$name_property = $data_model->getProperty($this->name_property);

		$class = $data_model->createClass();

		$class->addUse( new ClassCreator_UseClass('Jet', 'DataModel_Fetch_Instances') );

		if(!$class->hasMethod('get')) {
			$method_get = $class->createMethod('get');
			$method_get->setReturnType($data_model->getClassName());
			$method_get->setIsStatic( true );
			$method_get->addParameter( 'id' );

			$method_get->line(1,'return static::load( $id );');
		}


		if(!$class->hasMethod('getList')) {
			$method_getList = $class->createMethod('getList');
			$method_getList->setIsStatic(true);
			$method_getList->setReturnType( $data_model->getClassName().'[]|DataModel_Fetch_Instances' );
			$param_search = $method_getList->addParameter( 'search' );
			$param_search->setType('string');
			$param_search->setIsOptional(true);
			$param_search->setDefaultValue('');


			$method_getList->line(1, '$where = [];');
			$method_getList->line(1, 'if( $search ) {');
			$method_getList->line(2, '');
			$method_getList->line(2, '$search = \'%\'.$search.\'%\';');
			$method_getList->line(2, '');
			$method_getList->line(2, '$where[] = [');

			$c = 0;
			$count = count($this->grid_properties);
			foreach($this->grid_properties as $property_id) {
				$property = $data_model->getProperty( $property_id );
				$c++;

				$method_getList->line(3, '\''.$property->getName().' *\' => $search,');
				if($c<$count) {
					$method_getList->line(3, '\'OR\',');
				}
			}
			$method_getList->line(2, '];');
			$method_getList->line(1, '}');
			$method_getList->line(1, '');
			$method_getList->line(1, '$list = static::fetchInstances( $where );');
			//TODO: toto by bylo matuci a proto je to odstranene, ale je nutne to otestovat
			/*
			$method_getList->line(1, '$list = static::fetchInstances(');
			$method_getList->line(2, '$where,');
			$method_getList->line(2, '[');

			foreach($this->grid_properties as $property_id) {
				$property = $data_model->getProperty( $property_id );

				$method_getList->line(3, '\''.$property->getName().'\',');
			}
			$method_getList->line(2, ']);');
			*/
			$method_getList->line(1, '');
			$method_getList->line(1, '$list->getQuery()->setOrderBy( \''.$name_property->getName().'\' );');
			$method_getList->line(1, '');
			$method_getList->line(1, 'return $list;');

		}

		if(!$class->hasProperty('_form_edit')) {
			$class->addUse( new ClassCreator_UseClass('Jet', 'Form') );

			$class->createProperty('_form_edit', 'Form');

		}

		if(!$class->hasMethod('getEditForm')) {
			$class->addUse( new ClassCreator_UseClass('Jet', 'Form') );

			$method_getEditForm = $class->createMethod( 'getEditForm' );
			$method_getEditForm->setReturnType('Form');

			$method_getEditForm->line(1, 'if(!$this->_form_edit) {');
			$method_getEditForm->line(2, '$this->_form_edit = $this->getCommonForm();');
			$method_getEditForm->line(1, '}');
			$method_getEditForm->line(1, '');
			$method_getEditForm->line(1, 'return $this->_form_edit;');
		}


		if(!$class->hasMethod('catchEditForm')) {
			$method_catchEditForm = $class->createMethod( 'catchEditForm' );
			$method_catchEditForm->setReturnType('bool');

			$method_catchEditForm->line(1, 'return $this->catchForm( $this->getEditForm() );');
		}

		if(!$class->hasProperty('_form_add')) {
			$class->addUse( new ClassCreator_UseClass('Jet', 'Form') );

			$class->createProperty('_form_add', 'Form');

		}

		if(!$class->hasMethod('getAddForm')) {
			$class->addUse( new ClassCreator_UseClass('Jet', 'Form') );

			$method_getAddForm = $class->createMethod( 'getAddForm' );
			$method_getAddForm->setReturnType('Form');

			$method_getAddForm->line(1, 'if(!$this->_form_add) {');
			$method_getAddForm->line(2, '$this->_form_add = $this->getCommonForm();');
			$method_getAddForm->line(1, '}');
			$method_getAddForm->line(1, '');
			$method_getAddForm->line(1, 'return $this->_form_add;');
		}


		if(!$class->hasMethod('catchAddForm')) {
			$method_catchAddForm = $class->createMethod( 'catchAddForm' );
			$method_catchAddForm->setReturnType('bool');

			$method_catchAddForm->line(1, 'return $this->catchForm( $this->getAddForm() );');
		}


		$class->write( $data_model->getClassPath() );
	}

	/**
	 * @param Modules_Manifest $module
	 * @param Modules_Module_Controller $controller
	 *
	 * @return ClassCreator_Class
	 */
	public function create_generate_controller( Modules_Manifest $module, Modules_Module_Controller $controller )
	{
		$class = $controller->createClass( $module );

		$data_model = DataModels::getModel( $this->data_model_id );
		/**
		 * @var DataModels_Property $name_property
		 * @var DataModels_Property $id_property
		 */
		$name_property = $data_model->getProperty($this->name_property);
		$id_property = $data_model->getProperty($this->id_property);

		$id_getter = 'get'.$id_property->getSetterGetterMethodName();
		$name_getter = 'get'.$name_property->getSetterGetterMethodName();


		$data_model_namespace = Project::getNamespace( $data_model->getNamespaceId() );

		$class->addUse( new ClassCreator_UseClass('Jet', 'UI') );
		$class->addUse( new ClassCreator_UseClass('Jet', 'UI_dataGrid') );
		$class->addUse( new ClassCreator_UseClass('Jet', 'UI_messages') );
		$class->addUse( new ClassCreator_UseClass('Jet', 'Mvc') );
		$class->addUse( new ClassCreator_UseClass('Jet', 'Http_Headers') );
		$class->addUse( new ClassCreator_UseClass('Jet', 'Http_Request') );
		$class->addUse( new ClassCreator_UseClass('Jet', 'Tr') );
		$class->addUse( new ClassCreator_UseClass('Jet', 'Navigation_Breadcrumb') );

		if($data_model_namespace->getNamespace()!=$class->getNamespace()) {
			$class->addUse( new ClassCreator_UseClass($data_model_namespace->getNamespace(), $data_model->getClassName()) );
		}


		$controller_router_class = 'Controller_'.$this->controller_name.'_Router';

		if(!$class->hasProperty('router')) {
			$router_property = new ClassCreator_Class_Property('router', $controller_router_class);

			$class->addProperty( $router_property );
		}


		if(!$class->hasMethod('getControllerRouter')) {
			$getControllerRouter_method = new ClassCreator_Class_Method('getControllerRouter');
			$getControllerRouter_method->setReturnType($controller_router_class);

			$getControllerRouter_method->line(1, 'if( !$this->router ) {');
			$getControllerRouter_method->line(2, '$this->router = new Controller_Main_Router( $this );');
			$getControllerRouter_method->line(1, '}');
			$getControllerRouter_method->line(1, '');
			$getControllerRouter_method->line(1, 'return $this->router;');

			$class->addMethod( $getControllerRouter_method );
		}


		if(!$class->hasMethod('setBreadcrumbNavigation')) {
			$setBreadcrumbNavigation_method = new ClassCreator_Class_Method('setBreadcrumbNavigation');

			$current_label_param = $setBreadcrumbNavigation_method->addParameter( 'current_label' );
			$current_label_param->setType('string');
			$current_label_param->setIsOptional(true);
			$current_label_param->setDefaultValue('');

			$setBreadcrumbNavigation_method->line(1, 'if($current_label) {');
			$setBreadcrumbNavigation_method->line(2, 'Navigation_Breadcrumb::addURL($current_label);');
			$setBreadcrumbNavigation_method->line(1, '}');

			$class->addMethod( $setBreadcrumbNavigation_method );
		}

		$default_Action_method = $class->getMethod( 'default_Action' );
		$default_Action_method->clearBody();

		$default_Action_method->line(1, '$this->setBreadcrumbNavigation();');
		$default_Action_method->line(1, '');
		$default_Action_method->line(1, '$search_form = UI::searchForm( \''.$this->entity_name.'\' );');
		$default_Action_method->line(1, '$this->view->setVar( \'search_form\', $search_form );');
		$default_Action_method->line(1, '');
		$default_Action_method->line(1, '$grid = new UI_dataGrid();');
		$default_Action_method->line(1, '$grid->setIsPersistent( \''.$this->entity_name.'_list_grid\' );');
		$default_Action_method->line(1, '$grid->setDefaultSort( \''.$name_property->getName().'\' );');

		$default_Action_method->line(1, '');
		$default_Action_method->line(1, '$grid->addColumn( \'_edit_\', \'\' )->setAllowSort( false );');

		foreach( $this->grid_properties as $property_id ) {

			$property = $data_model->getProperty( $property_id );

			$name = $property->getName();
			$title = $property->getFormFieldLabel();

			$title = str_replace(':', '', $title);

			$default_Action_method->line(1, '$grid->addColumn( \''.$name.'\', Tr::_( \''.$title.'\' ) );');
		}


		$default_Action_method->line(1, '');
		$default_Action_method->line(1, '$grid->setData( '.$data_model->getClassName().'::getList( $search_form->getValue() ) );');
		$default_Action_method->line(1, '');
		$default_Action_method->line(1, '$this->view->setVar( \'grid\', $grid );');
		$default_Action_method->line(1, '');
		$default_Action_method->line(1, '$this->render( \'list\' );');




		
		
		
		$add_Action_method = $class->getMethod( 'add_Action' );
		$add_Action_method->clearBody();

		$add_Action_method->line(1, '$this->setBreadcrumbNavigation(Tr::_(\''.addslashes(htmlspecialchars_decode($this->text_crete_bt_label)).'\'));');
		$add_Action_method->line(1, '');
		$add_Action_method->line(1, '$item = new '.$data_model->getClassName().'();');
		$add_Action_method->line(1, '');
		$add_Action_method->line(1, '$form = $item->getAddForm();');
		$add_Action_method->line(1, '');
		$add_Action_method->line(1, 'if( $item->catchAddForm() ) {');
		$add_Action_method->line(2, '$item->save();');
		$add_Action_method->line(2, '$this->logAllowedAction( \'Item created\', $item->'.$id_getter.'(), $item->'.$name_getter.'(), $item );');
		$add_Action_method->line(2, 'UI_messages::success(');
		$add_Action_method->line(3, 'Tr::_( \''.addslashes(htmlspecialchars_decode($this->text_item_created)).'\', [ \'NAME\' => $item->'.$name_getter.'() ] )');
		$add_Action_method->line(2, ');');
		$add_Action_method->line(2, 'Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI( $item->'.$id_getter.'() ) );');
		$add_Action_method->line(1, '}');
		$add_Action_method->line(1, '');
		$add_Action_method->line(1, '$this->view->setVar( \'form\', $form );');
		$add_Action_method->line(1, '$this->render( \'edit\' );');
		$add_Action_method->line(1, '');



		$edit_Action_method = $class->getMethod( 'edit_Action' );
		$edit_Action_method->clearBody();
		//TODO: sem pridat typ $item
		$edit_Action_method->line(1, '$item = $this->getParameter( \'item\' );');
		$edit_Action_method->line(1, '');
		$edit_Action_method->line(1, '$this->setBreadcrumbNavigation( Tr::_( \''.addslashes(htmlspecialchars_decode($this->text_edit_item)).'\', [ \'NAME\' => $item->'.$name_getter.'() ] ) );');
		$edit_Action_method->line(1, '');
		$edit_Action_method->line(1, '$form = $item->getEditForm();');
		$edit_Action_method->line(1, '');
		$edit_Action_method->line(1, 'if( $item->catchEditForm() ) {');
		$edit_Action_method->line(2, '$item->save();');
		$edit_Action_method->line(2, '$this->logAllowedAction( \'Item updated\', $item->'.$id_getter.'(), $item->'.$name_getter.'(), $item );');
		$edit_Action_method->line(1, '');
		$edit_Action_method->line(2, 'UI_messages::success(');
		$edit_Action_method->line(3, 'Tr::_( \''.addslashes(htmlspecialchars_decode($this->text_item_updated)).'\', [ \'NAME\' => $item->'.$name_getter.'() ] )');
		$edit_Action_method->line(2, ');');
		$edit_Action_method->line(1, '');
		$edit_Action_method->line(2, 'Http_Headers::movedTemporary( $this->getControllerRouter()->getEditURI( $item->'.$id_getter.'() ) );');
		$edit_Action_method->line(1, '}');
		$edit_Action_method->line(1, '');
		$edit_Action_method->line(1, '$this->view->setVar( \'form\', $form );');
		$edit_Action_method->line(1, '$this->view->setVar( \'item\', $item );');
		$edit_Action_method->line(1, '');
		$edit_Action_method->line(1, '$this->render( \'edit\' );');


		$view_Action_method = $class->getMethod( 'view_Action' );
		$view_Action_method->clearBody();
		//TODO: sem pridat typ $item
		//TODO: item doplnit i do view
		$view_Action_method->line(1, '$item = $this->getParameter( \'item\' );');
		$view_Action_method->line(1, '');
		$view_Action_method->line(1, '$this->setBreadcrumbNavigation(');
		$view_Action_method->line(2, 'Tr::_( \''.addslashes(htmlspecialchars_decode($this->text_item_detail)).'\', [ \'NAME\' => $item->'.$name_getter.'() ] )');
		$view_Action_method->line(1, ');');
		$view_Action_method->line(1, '');
		$view_Action_method->line(1, '$form = $item->getEditForm();');
		$view_Action_method->line(1, '');
		$view_Action_method->line(1, '$this->view->setVar( \'form\', $form );');
		$view_Action_method->line(1, '$this->view->setVar( \'item\', $item );');
		$view_Action_method->line(1, '');
		$view_Action_method->line(1, '$form->setIsReadonly();');
		$view_Action_method->line(1, '');
		$view_Action_method->line(1, '$this->render( \'edit\' );');
		
		
		$delete_Action_method = $class->getMethod( 'delete_Action' );
		$delete_Action_method->clearBody();
		$delete_Action_method->line(1, '$POST = Http_Request::POST();');
		$delete_Action_method->line(1, '');
		$delete_Action_method->line(1, '$ids = $POST->getRaw(\'id\', []);');
		$delete_Action_method->line(1, 'foreach( $ids as $id ) {');
		$delete_Action_method->line(2, '$item = '.$data_model->getClassName().'::get( $id );');
		$delete_Action_method->line(2, '');
		$delete_Action_method->line(2, 'if( $item ) {');
		$delete_Action_method->line(3, '$item->delete();');
		$delete_Action_method->line(3, '');
		$delete_Action_method->line(3, 'UI_messages::success(');
		$delete_Action_method->line(4, 'Tr::_( \''.addslashes(htmlspecialchars_decode($this->text_item_deleted)).'\', [ \'NAME\' => $item->'.$name_getter.'() ] )');
		$delete_Action_method->line(3, ');');
		$delete_Action_method->line(3, '');
		$delete_Action_method->line(3, '$this->logAllowedAction( \'Item deleted\', $item->'.$id_getter.'(), $item->'.$name_getter.'(), $item );');
		$delete_Action_method->line(3, '');
		$delete_Action_method->line(2, '}');
		$delete_Action_method->line(1, '}');
		$delete_Action_method->line(1, '');
		$delete_Action_method->line(1, 'Http_Headers::movedTemporary( $this->getControllerRouter()->getBaseUri() );');

		return $class;
	}

	/**
	 * @throws \Jet\BaseObject_Exception
	 */
	public function create_generate_controllerRouter()
	{
		$module = Modules::getCurrentModule();
		$data_model = DataModels::getModel( $this->data_model_id );

		$class_name = 'Controller_'.$this->controller_name.'_Router';

		$class = new ClassCreator_Class();

		$class->setNamespace( rtrim($module->getNamespace(), '\\') );
		$class->setName( $class_name );

		$data_model_namespace = Project::getNamespace( $data_model->getNamespaceId() );
		if($data_model_namespace->getNamespace()!=$class->getNamespace()) {
			$class->addUse( new ClassCreator_UseClass($data_model_namespace->getNamespace(), $data_model->getClassName()) );
		}



		$class->addUse( new ClassCreator_UseClass( 'Jet', 'Mvc_Controller') );
		$class->addUse( new ClassCreator_UseClass( 'Jet', 'Mvc_Controller_Router') );
		$class->addUse( new ClassCreator_UseClass( 'Jet', 'Mvc_Controller_Router_Action') );
		$class->addUse( new ClassCreator_UseClass( 'Jet', 'Mvc_Page') );

		$class->setExtends( 'Mvc_Controller_Router' );

		$class->createProperty('add_action', 'Mvc_Controller_Router_Action');
		$class->createProperty('edit_action', 'Mvc_Controller_Router_Action');
		$class->createProperty('view_action', 'Mvc_Controller_Router_Action');
		$class->createProperty('delete_action', 'Mvc_Controller_Router_Action');





		$constructor = $class->createMethod('__construct');
		$constructor->addParameter('controller')->setType('Mvc_Controller');

		$constructor->line(1, '');
		$constructor->line(1, 'parent::__construct( $controller );');
		$constructor->line(1, '');
		$constructor->line(1, '$validator = function( $parameters, Mvc_Controller_Router_Action $action ) {');
		$constructor->line(1, '');
		$constructor->line(2, 'if( ($item = '.$data_model->getClassName().'::get( $parameters[0] )) ) {');
		$constructor->line(3, '$action->controller()->getContent()->setParameter(\'item\', $item);');
		$constructor->line(3, 'return true;');
		$constructor->line(2, '}');
		$constructor->line(2, '');
		$constructor->line(2, 'return false;');
		$constructor->line(1, '};');
		$constructor->line(1, '');
		$constructor->line(1, '');
		$constructor->line(1, '$this->add_action = $this->addAction( \'add\', \'/^add$/\' );');
		$constructor->line(1, '$this->add_action->setURICreator(');
		$constructor->line(2, 'function() {');
		$constructor->line(3, 'return Mvc_Page::get( \''.$this->page_id.'\' )->getURI([\'add\' ]);');
		$constructor->line(2, '}');
		$constructor->line(1, ');');
		$constructor->line(1, '');
		$constructor->line(1, '');
		$constructor->line(1, '$this->edit_action = $this->addAction( \'edit\', \'/^edit:([a-z\-0-9\_]+)$/\' );');
		$constructor->line(1, '$this->edit_action->setURICreator(');
		$constructor->line(2, 'function( $id ) {');
		$constructor->line(3, 'return Mvc_Page::get( \''.$this->page_id.'\' )->getURI([\'edit:\'.$id ]);');
		$constructor->line(2, '}');
		$constructor->line(1, ');');
		$constructor->line(1, '$this->edit_action->setValidator( $validator );');
		$constructor->line(1, '');
		$constructor->line(1, '');
		$constructor->line(1, '$this->view_action = $this->addAction( \'view\', \'/^view:([a-z\-0-9\_]+)$/\' );');
		$constructor->line(1, '$this->view_action->setURICreator(');
		$constructor->line(2, 'function( $id ) {');
		$constructor->line(3, 'return Mvc_Page::get( \''.$this->page_id.'\' )->getURI([\'view:\'.$id ]);');
		$constructor->line(2, '}');
		$constructor->line(1, ');');
		$constructor->line(1, '$this->view_action->setValidator( $validator );');
		$constructor->line(1, '');
		$constructor->line(1, '');
		$constructor->line(1, '$this->delete_action = $this->addAction( \'delete\', \'/^delete$/\' );');
		$constructor->line(1, '$this->delete_action->setURICreator(');
		$constructor->line(2, 'function() {');
		$constructor->line(3, 'return Mvc_Page::get( \''.$this->page_id.'\' )->getURI([\'delete\' ]);');
		$constructor->line(2, '}');
		$constructor->line(1, ');');
		$constructor->line(1, '');
		$constructor->line(1, '');

		$getBaseURI = $class->createMethod('getBaseURI');
		$getBaseURI->setReturnType('string');
		$getBaseURI->line( 1, 'return Mvc_Page::get( \''.$this->page_id.'\' )->getURI();' );


		$getAddURI = $class->createMethod('getAddURI');
		$getAddURI->setReturnType('bool|string');
		$getAddURI->line( 1, 'return $this->add_action->URI();' );


		$getEditURI = $class->createMethod('getEditURI');
		$getEditURI->addParameter('id');
		$getEditURI->setReturnType('bool|string');
		$getEditURI->line( 1, 'return $this->edit_action->URI( $id );' );

		$getViewURI = $class->createMethod('getViewURI');
		$getViewURI->addParameter('id');
		$getViewURI->setReturnType('bool|string');
		$getViewURI->line( 1, 'return $this->view_action->URI( $id );' );


		$getEditOrViewURI = $class->createMethod('getEditOrViewURI');
		$getEditOrViewURI->addParameter('id');
		$getEditOrViewURI->setReturnType('bool|string');
		$getEditOrViewURI->line( 1, 'if( !( $uri = $this->getEditURI( $id ) ) ) {' );
		$getEditOrViewURI->line( 2, '$uri = $this->getViewURI( $id );' );
		$getEditOrViewURI->line( 1, '}' );
		$getEditOrViewURI->line( 1, '' );
		$getEditOrViewURI->line( 1, 'return $uri;' );


		$getDeleteURI = $class->createMethod('getDeleteURI');
		$getDeleteURI->setReturnType('bool|string');
		$getDeleteURI->line( 1, 'return $this->delete_action->URI();' );


		$class->write( $module->getModuleDir().str_replace('_','/', $class->getName()).'.php' );
	}

	/**
	 *
	 */
	public function create_generate_view_list()
	{
		$module = Modules::getCurrentModule();

		$data_model = DataModels::getModel( $this->data_model_id );
		$data_model_namespace = Project::getNamespace( $data_model->getNamespaceId() );
		
		
		
		$source = $this->getBaseDir().'templates/view/list.phtml';
		$target = $module->getModuleDir().'views/'.$this->list_view.'.phtml';

		if( IO_File::exists( $target ) ) {
			return;
		}

		/**
		 * @var DataModels_Property $name_property
		 * @var DataModels_Property $id_property
		 */
		$name_property = $data_model->getProperty($this->name_property);
		$id_property = $data_model->getProperty($this->id_property);

		$id_getter = 'get'.$id_property->getSetterGetterMethodName();
		$name_getter = 'get'.$name_property->getSetterGetterMethodName();

		$ident = ClassCreator_Class::getIndentation();
		$nl = ClassCreator_Class::getNl();

		$g_r = '';
		
		$g_r .= '$c_edit = $grid->getColumn( \'_edit_\' );'.$nl;
		$g_r .= '$c_edit->setRenderer('.$nl;
		$g_r .= $ident.'function( '.$data_model->getClassName().' $item ) use ( $router, $delete_uri ) {'.$nl;
		$g_r .= $ident.$ident.'if($delete_uri):'.$nl;
		$g_r .= $ident.$ident.$ident.'?>'.$nl;
		$g_r .= $ident.$ident.$ident.'<input type="checkbox" name="id[]" value="<?=$item->'.$id_getter.'()?>"/>'.$nl;
		$g_r .= $ident.$ident.$ident.'<?php'.$nl;
		$g_r .= $ident.$ident.'endif;'.$nl;
		$g_r .= $ident.$ident.''.$nl;
		$g_r .= $ident.$ident.'if( ( $edit_uri = $router->getEditURI( $item->'.$id_getter.'() ) ) ):'.$nl;
		$g_r .= $ident.$ident.$ident.'echo UI::button_edit()->setUrl( $edit_uri )->setSize( UI_button::SIZE_EXTRA_SMALL);'.$nl;
		$g_r .= $ident.$ident.'endif;'.$nl;
		$g_r .= $ident.'}'.$nl;
		$g_r .= ');'.$nl;
		$g_r .= '$c_edit->setCssStyle( \'width:180px;\' );'.$nl;
		$g_r .= $nl;




		foreach($this->grid_properties as $property_id) {
			/**
			 * @var DataModels_Property $property
			 */
			$property = $data_model->getProperty( $property_id );
			$getter = 'get'.$property->getSetterGetterMethodName();

			$g_r .= '$grid->getColumn( \''.$property->getName().'\' )->setRenderer('.$nl;
			$g_r .= $ident.'function( '.$data_model->getClassName().' $item ) use ( $router ) {'.$nl;
			$g_r .= $ident.$ident.'$edit_uri = $router->getEditOrViewURI( $item->'.$id_getter.'() );'.$nl;
			$g_r .= $ident.$ident.$nl;
			if($property_id==$this->id_property || $property_id==$this->name_property) {
				$g_r .= $ident.$ident.'?><a href="<?=$edit_uri?>"><?=$item->'.$getter.'();?></a><?php'.$nl;
			} else {
				$g_r .= $ident.$ident.'echo $item->'.$getter.'();'.$nl;
			}
			$g_r .= $ident.'}'.$nl;
			$g_r .= ');'.$nl;
			$g_r .= $nl;

		}


		$module_namespace = rtrim($module->getNamespace(), '\\');

		if($data_model_namespace->getNamespace()!=$module_namespace) {
			$item_use = 'use '.$data_model->getFullClassName().';';
		} else {
			$item_use = '';
		}


		$data = [
			'ITEM_USE' => $item_use,
			'NAMESPACE' => $module_namespace,
			'ROUTER_CLASS_NAME' =>  'Controller_'.$this->controller_name.'_Router',
			'CRETE_BT_LABEL' => htmlspecialchars_decode($this->text_crete_bt_label),
			'DELETE_BT_LABEL' => htmlspecialchars_decode($this->text_delete_bt_label),
			'GRID_RENDERERS' => $g_r
		];

		$script = IO_File::read($source);
		$script = Data_Text::replaceData( $script, $data );

		IO_File::write( $target, $script );
	}

	/**
	 *
	 */
	public function create_generate_view_edit()
	{
		$module = Modules::getCurrentModule();

		$source = $this->getBaseDir().'templates/view/edit.phtml';
		$target = $module->getModuleDir().'views/'.$this->edit_view.'.phtml';

		if( IO_File::exists( $target ) ) {
			return;
		}

		IO_File::copy( $source, $target );

	}

}