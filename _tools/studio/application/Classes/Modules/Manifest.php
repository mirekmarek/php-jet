<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\Application_Module_Manifest;
use Jet\Data_Array;
use Jet\Exception;
use Jet\Form;
use Jet\Form_Field_Checkbox;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;
use Jet\IO_Dir;
use Jet\IO_File;
use Jet\Mvc_Layout;
use Jet\Tr;
use \ReflectionClass;
use \ReflectionMethod;

/**
 *
 */
class Modules_Manifest extends Application_Module_Manifest
{
	const MAX_ACL_ACTION_COUNT = 100;


	/**
	 * @var Form
	 */
	protected $__edit_form;

	/**
	 * @var Modules_Module_Controller[]
	 */
	protected $controllers = [];

	/**
	 * @var Pages_Page[][]
	 */
	protected $pages = [];


	/**
	 * @var Form
	 */
	protected static $create_form;

	/**
	 * @var Form
	 */
	protected $page_create_form;

	/**
	 * @var Form
	 */
	protected $menu_item_create_form;


	/**
	 * @return bool
	 */
	public function save()
	{
		$ok = true;
		try {
			$this->create_saveManifest();
			Application::resetOPCache();
		} catch( Exception $e ) {
			$ok = false;
			Application::handleError( $e );
		}

		return $ok;
	}

	/**
	 * @param array $manifest_data
	 */
	public function setupProperties( array $manifest_data )
	{
		parent::setupProperties( $manifest_data );

		foreach($this->menu_items as $set_id=>$menus) {
			foreach($menus as $menu_id=>$items) {
				foreach($items as $item_id=>$item_data) {
					$item = new Menus_Menu_Item( $item_id, $item_data['label']??'' );
					$item->setData($item_data);
					$item->setSetId( $set_id );
					$item->setMenuId($menu_id);

					$this->menu_items[$set_id][$menu_id][$item_id] = $item;
				}
			}
		}

		foreach($this->pages as $site_id=>$pages ) {
			$site = Sites::getSite($site_id);
			if(!$site) {
				unset($this->pages[$site_id]);
				continue;
			}

			$locale = $site->getDefaultLocale();

			foreach($pages as $page_id=>$page_data) {
				/**
				 * @var array $page_data
				 */
				$page_data['id'] = $page_id;

				$page = Pages_Page::createByData( $site, $locale, $page_data );

				$this->pages[$site_id][$page_id] = $page;
			}
		}
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->_name = $name;
	}

	/**
	 * @param string $vendor
	 */
	public function setVendor($vendor)
	{
		$this->vendor = $vendor;
	}

	/**
	 * @param string $version
	 */
	public function setVersion($version)
	{
		$this->version = $version;
	}

	/**
	 * @param string $label
	 */
	public function setLabel($label)
	{
		$this->label = $label;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}

	/**
	 * @param bool $is_mandatory
	 */
	public function setIsMandatory($is_mandatory)
	{
		$this->is_mandatory = $is_mandatory;
	}

	/**
	 * @param array $ACL_actions
	 */
	public function setACLActions( $ACL_actions )
	{
		$this->ACL_actions = $ACL_actions;
	}


	/**
	 * @return Pages_Page[][]
	 */
	public function getPagesList()
	{
		return $this->pages;
	}

	/**
	 * @param $site_id
	 * @param $page_id
	 *
	 * @return null|Pages_Page
	 */
	public function getPage( $site_id, $page_id )
	{
		if(
			!isset($this->pages[$site_id]) ||
			!isset($this->pages[$site_id][$page_id])
		) {
			return null;
		}

		$page = $this->pages[$site_id][$page_id];

		$site = Sites::getSite($page->getSiteId());
		$page->setLocale( $site->getDefaultLocale() );

		return $page;
	}



	/**
	 * @return Form
	 */
	public static function getCreateForm()
	{
		if(!static::$create_form) {


			$module_name = new Form_Field_Input('module_name', 'Name:', '' );
			$module_label = new Form_Field_Input('module_label', 'Label:', '' );


			$module_name->setIsRequired(true);
			$module_name->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter module name',
				Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid module name format'
			]);
			$module_name->setValidator( function( Form_Field_Input $field ) {
				$name = $field->getValue();

				return Modules_Manifest::checkModuleName( $field, $name );
			} );

			$module_label->setIsRequired(true);
			$module_label->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter module label'
			]);

			$fields = [
				$module_name,
				$module_label,
			];

			$form = new Form('create_module_form', $fields );


			$form->setAction( Modules::getActionUrl('add') );

			static::$create_form = $form;
		}

		return static::$create_form;
	}

	/**
	 * @return bool|Modules_Manifest
	 */
	public static function catchCreateForm()
	{
		$form = static::getCreateForm();
		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}


		$new_module = Modules::createModule(
			$form->field('module_name')->getValue(),
			$form->field('module_label')->getValue()
		);

		return $new_module;
	}


	/**
	 *
	 * @return Form
	 */
	public function getEditForm()
	{
		if( !$this->__edit_form ) {

			$module_name = new Form_Field_Input('module_name', 'Name:', $this->getName() );
			$module_name->setIsRequired(true);
			$module_name->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter module name',
				Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid module name format'
			]);
			$module_name->setValidator( function( Form_Field_Input $field ) {
				$name = $field->getValue();
				$old_module_name = $this->getName();

				return Modules_Manifest::checkModuleName( $field, $name, $old_module_name );
			} );
			$module_name->setCatcher( function( $value ) {
				$this->setName( $value );
			} );



			$module_label = new Form_Field_Input('module_label', 'Label:', $this->getLabel() );
			$module_label->setIsRequired(true);
			$module_label->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter module label'
			]);
			$module_label->setCatcher( function( $value ) {
				$this->setLabel( $value );
			} );


			$vendor = new Form_Field_Input('vendor', 'Vendor:', $this->getVendor() );
			$vendor->setCatcher( function( $value ) {
				$this->setVendor( $value );
			} );

			$version = new Form_Field_Input('version', 'Version:', $this->getVersion() );
			$version->setCatcher( function( $value ) {
				$this->setVersion( $value );
			} );

			$description = new Form_Field_Input('description', 'Description:', $this->getDescription() );
			$description->setCatcher( function( $value ) {
				$this->setDescription( $value );
			} );

			$is_mandatory = new Form_Field_Checkbox('is_mandatory', 'Is mandatory', $this->isMandatory() );
			$is_mandatory->setCatcher( function( $value ) {
				$this->setIsMandatory( $value );
			} );

			$is_active = new Form_Field_Checkbox('is_active', 'Is active', $this->isActivated() );
			$is_active->setIsReadonly(true);

			$is_installed = new Form_Field_Checkbox('is_installed', 'Is installed', $this->isInstalled() );
			$is_installed->setIsReadonly(true);



			$fields = [
				$module_name,
				$module_label,
				$vendor,
				$version,
				$description,
				$is_mandatory,
				$is_active,
				$is_installed,
			];


			$m = 0;
			foreach( $this->getACLActions( false ) as $action=>$description) {

				$acl_action = new Form_Field_Input('/ACL_action/'.$m.'/action', 'Action:', $action );
				$fields[] = $acl_action;

				$acl_action_description = new Form_Field_Input('/ACL_action/'.$m.'/description', 'Label:', $description );
				$fields[] = $acl_action_description;

				$m++;
			}

			for( $c=0;$c<8;$c++ ) {

				$acl_action = new Form_Field_Input('/ACL_action/'.$m.'/action', 'Action:', '' );
				$fields[] = $acl_action;

				$acl_action_description = new Form_Field_Input('/ACL_action/'.$m.'/description', 'Label:', '' );
				$fields[] = $acl_action_description;

				$m++;
			}



			$form = new Form('edit_module_form', $fields );


			$form->setAction( Modules::getActionUrl('edit') );

			$this->__edit_form = $form;
		}

		return $this->__edit_form;
	}

	/**
	 * @return bool
	 */
	public function catchEditForm()
	{
		$form = $this->getEditForm();
		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$form->catchData();
		$this->catchEditForm_ACLAction( $form );


		return true;
	}

	/**
	 * @param Form $form
	 *
	 */
	public function catchEditForm_ACLAction( Form $form )
	{
		$ACL_actions = [];
		for( $m=0;$m<static::MAX_ACL_ACTION_COUNT;$m++ ) {
			if(!$form->fieldExists('/ACL_action/'.$m.'/action')) {
				break;
			}

			$action = $form->field('/ACL_action/'.$m.'/action')->getValue();
			$description = $form->field('/ACL_action/'.$m.'/description')->getValue();

			if(
				!$action
			) {
				continue;
			}

			if(!$description) {
				$description = $action;
			}

			$ACL_actions[$action] = $description;
		}

		$this->ACL_actions = $ACL_actions;

	}



	/**
	 * @param Form_Field_Input $field
	 * @param string $name
	 * @param string $old_module_name
	 *
	 * @return bool
	 */
	public static function checkModuleName(Form_Field_Input $field, $name, $old_module_name='' )
	{


		if(!$name)	{
			$field->setError( Form_Field_Input::ERROR_CODE_EMPTY );
			return false;
		}

		if(
			!preg_match('/^[a-z0-9.]{3,}$/i', $name) ||
			strpos( $name, '..' )!==false ||
			$name[0]=='.' ||
			$name[strlen($name)-1]=='.'
		) {
			$field->setError(Form_Field_Input::ERROR_CODE_INVALID_FORMAT);

			return false;
		}

		if(
			(
				!$old_module_name &&
				Modules::exists($name)
			)
			||
			(
				$old_module_name &&
				$old_module_name!=$name &&
				Modules::exists($name)
			)
		) {
			$field->setCustomError(
				Tr::_('Module with the same name already exists'),
				'module_name_is_not_unique'
			);

			return false;
		}

		return true;

	}

	/**
	 * @param string $site_id
	 *
	 * @param Pages_Page $page
	 */
	public function addPage( $site_id, Pages_Page $page )
	{
		if(!isset($this->pages[$site_id])) {
			$this->pages[$site_id] = [];
		}

		$this->pages[$site_id][$page->getId()] = $page;

	}

	/**
	 * @return Form
	 */
	public function getPageCreateForm()
	{
		if(!$this->page_create_form) {
			$sites = [''=>''];
			foreach( Sites::getSites() as $site ) {
				$sites[$site->getId()] = $site->getName();
			}

			$site_id = new Form_Field_Select('site_id', 'Site: ', '');
			$site_id->setSelectOptions( $sites );
			$site_id->setIsRequired( true );
			$site_id->setErrorMessages([
				Form_Field_Select::ERROR_CODE_EMPTY => 'Please select site',
				Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select site',
			]);
			
			$page_name = new Form_Field_Input('page_name', 'Page name:', '');
			$page_name->setIsRequired(true);
			$page_name->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter page name'
			]);

			$page_id = new Form_Field_Input('page_id', 'Page ID:', '');
			$page_id->setIsRequired(true);
			$page_id->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter page ID'
			]);
			

			$form = new Form('add_page_form', [
				$site_id,
				$page_name,
				$page_id
			]);

			$form->setAction( Modules::getActionUrl('page/add') );


			$this->page_create_form = $form;
		}

		return $this->page_create_form;
	}

	/**
	 *
	 * @return Pages_Page|bool
	 */
	public function catchCratePageForm()
	{
		$form = $this->getPageCreateForm();

		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$site_id = $form->getField('site_id')->getValue();
		$page_name = $form->getField('page_name')->getValue();
		$page_id = $form->getField('page_id')->getValue();


		$page_id = static::generatePageId( $page_id, $site_id );

		$page = new Pages_Page();

		$page->setSiteId( $site_id );
		$page->setId( $page_id );
		$page->setName( $page_name );
		$page->setTitle( $page_name );
		$page->setRelativePathFragment( $page_id );

		$content = new Pages_Page_Content();
		$content->setModuleName( $this->getName() );
		$content->setControllerName( 'Main' );
		$content->setControllerAction( 'default' );
		$content->setOutputPosition( Mvc_Layout::DEFAULT_OUTPUT_POSITION );

		$page->setContent([
			$content
		]);

		$this->addPage( $site_id, $page );

		return $page;
	}

	/**
	 * @param string $name
	 * @param string $site_id
	 * @return string
	 */
	public static function generatePageId( $name, $site_id )
	{
		$site = Sites::getSite( $site_id );

		$id = Project::generateIdentifier( $name, function( $id ) use ($site) {

			foreach( $site->getLocales() as $locale ) {
				if( Pages::exists( $id, $locale, $site->getId() ) ) {
					return true;
				}
			}

			return false;
		} );

		return $id;
	}

	/**
	 * @param string $site_id
	 * @param string $page_id
	 * @return Pages_Page|null
	 */
	public function deletePage( $site_id, $page_id )
	{
		if( !isset($this->pages[$site_id][$page_id]) ) {
			return null;
		}

		$old_page = $this->pages[$site_id][$page_id];

		unset($this->pages[$site_id][$page_id]);

		if(!count($this->pages[$site_id])) {
			unset($this->pages[$site_id]);
		}

		return $old_page;
	}

	/**
	 * @param Pages_Page $page
	 *
	 * @return Form
	 */
	public static function getPageContentCreateForm( Pages_Page $page )
	{
		$form = Pages_Page_Content::getCreateForm( $page );

		$form->setAction( Modules::getActionUrl('page/content/add', [
			'site' => $page->getSiteId(),
			'page' => $page->getId()
		]) );

		return $form;
	}

	/**
	 * @return array
	 */
	public function getControllers()
	{
		$controllers = [];

		/**
		 * @param string $dir
		 */
		$readDir = function ( $dir  ) use (&$readDir, &$controllers)
		{
			$dirs = IO_Dir::getList( $dir, '*', true, false );
			$files = IO_Dir::getList( $dir, '*.php', false, true );

			foreach( $files as $path=>$name ) {
				$file_data = IO_File::read($path);

				$parser = new ClassParser($file_data);

				foreach($parser->classes as $class ) {
					$full_name = $parser->namespace->namespace.'\\'.$class->name;

					$_class = new ReflectionClass( $full_name );

					$parents = [];

					while( ($parent = $_class->getParentClass()) ) {
						$parents[] = $parent->getName();
						$_class = $parent;
					}

					if(!in_array('Jet\Mvc_Controller', $parents)) {
						continue;
					}

					$c_n = substr($class->name, 11);

					$controllers[$c_n] = $c_n;
				}

			}

			foreach( $dirs as $path=>$name ) {
				$readDir( $path );
			}
		};

		$readDir($this->getModuleDir().'Controller/');

		return $controllers;
	}

	/**
	 * @param string $controller_name
	 *
	 * @return array
	 */
	public function getControllerAction( $controller_name )
	{
		$class_name = $this->getNamespace().'Controller_'.$controller_name;

		$reflection = new ReflectionClass( $class_name );

		$methods = $reflection->getMethods( ReflectionMethod::IS_PUBLIC );

		$actions = [];

		foreach($methods as $method) {
			$name = $method->getName();
			if(substr($name, -7)!='_Action') {
				continue;
			}

			$name = substr($name, 0, -7);

			$actions[$name] = $name;
		}

		return $actions;
	}


	/**
	 * @return Form
	 */
	public function getCreateMenuItemForm()
	{
		if(!$this->menu_item_create_form) {

			$form = Menus_Menu_Item::getCreateForm();
			$form->setCustomTranslatorNamespace('menus');

			$target_menus = [''=>''];
			foreach( Menus::getSets() as $set ) {
				foreach( $set->getMenus() as $menu ) {

					$key = $set->getName().':'.$menu->getId();

					$target_menus[$key] = $set->getName().' / '.$menu->getLabel().' ('.$menu->getId().')';
				}
			}


			$target_menu = new Form_Field_Select('target_menu', 'Menu', '');
			$target_menu->setIsRequired( true );
			$target_menu->setErrorMessages([
				Form_Field_Select::ERROR_CODE_EMPTY=> 'Please select target menu',
				Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select target menu'
			]);
			$target_menu->setSelectOptions($target_menus);


			$form->addField( $target_menu );

			$form->setAction( Modules::getActionUrl('menu_item/add') );

			$this->menu_item_create_form = $form;
		}

		return $this->menu_item_create_form;
	}

	/**
	 * @return bool|Menus_Menu_Item
	 */
	public function catchCreateMenuItemForm()
	{
		$form = $this->getCreateMenuItemForm();
		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$target_menu = $form->field('target_menu')->getValue();

		[$set_name, $menu_id] = explode(':', $target_menu);

		$menu_item = new Menus_Menu_Item(
			$form->field('id')->getValue(),
			$form->field('label')->getValue()
		);

		$menu_item->setSetId( $set_name );
		$menu_item->setMenuId( $menu_id );

		$menu_item->setIndex( $form->field('index')->getValue() );
		$menu_item->setIcon( $form->field('icon')->getValue() );

		$menu_item->setSeparatorBefore( $form->field('separator_before')->getValue() );
		$menu_item->setSeparatorAfter( $form->field('separator_after')->getValue() );


		$menu_item->setURL( $form->field('URL')->getValue() );

		$menu_item->setPageId( $form->field('page_id')->getValue() );
		$menu_item->setSiteId( $form->field('site_id')->getValue() );
		$menu_item->setLocale( $form->field('locale')->getValue() );

		$menu_item->setUrlParts( Menus_Menu_Item::catchURLParts( $form ) );
		$menu_item->setGetParams( Menus_Menu_Item::catchGETparams( $form ) );

		return $menu_item;
	}

	/**
	 *
	 * @param Menus_Menu_Item $menu_item
	 */
	public function addMenuItem( Menus_Menu_Item $menu_item )
	{
		$menu_set = $menu_item->getSetId();
		$menu_id = $menu_item->getMenuId();
		$item_id = $menu_item->getId();

		if(!isset($this->menu_items[$menu_set])) {
			$this->menu_items[$menu_set] = [];
		}

		if(!isset($this->menu_items[$menu_set][$menu_id])) {
			$this->menu_items[$menu_set][$menu_id] = [];
		}

		$this->menu_items[$menu_set][$menu_id][$item_id] = $menu_item;

		$this->menu_item_create_form = null;
	}

	/**
	 * @param string $menu_set
	 * @param string $menu_id
	 *
	 * @return Menus_Menu_Item[]
	 */
	public function getMenuItemsList( $menu_set='', $menu_id='' )
	{
		if(!$menu_set) {
			return $this->menu_items;
		}

		if(!isset( $this->menu_items[$menu_set])) {
			return [];
		}

		if(!$menu_id) {
			return $this->menu_items[$menu_set];
		}

		if( !isset( $this->menu_items[$menu_set][$menu_id]) ) {
			return [];
		}


		return $this->menu_items[$menu_set][$menu_id];
	}

	/**
	 * @param string $menu_set
	 * @param string $menu_id
	 * @param string $item_id
	 *
	 * @return Menus_Menu_Item|null
	 */
	public function getMenuItem( $menu_set, $menu_id, $item_id )
	{
		if(!isset( $this->menu_items[$menu_set][$menu_id][$item_id])) {
			return null;
		}

		return $this->menu_items[$menu_set][$menu_id][$item_id];
	}

	/**
	 *
	 * @param string $menu_set
	 * @param string $menu_id
	 * @param string $item_id
	 *
	 * @return Menus_Menu_Item|null
	 */
	public function deleteMenuItem( $menu_set, $menu_id, $item_id )
	{
		if(
			!isset( $this->menu_items[$menu_set]) ||
			!isset( $this->menu_items[$menu_set][$menu_id]) ||
			!isset( $this->menu_items[$menu_set][$menu_id][$item_id])
		) {
			return null;
		}

		$old_item = $this->menu_items[$menu_set][$menu_id][$item_id];

		unset( $this->menu_items[$menu_set][$menu_id][$item_id] );

		if(!count( $this->menu_items[$menu_set][$menu_id])) {
			unset( $this->menu_items[$menu_set][$menu_id] );
		}

		if(!count( $this->menu_items[$menu_set])) {
			unset( $this->menu_items[$menu_set] );
		}

		return $old_item;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		$res = [
			'vendor'       => $this->getVendor(),
			'version'      => $this->getVersion(),
			'label'        => $this->getLabel(),
			'description'  => $this->getDescription(),
			'is_mandatory' => $this->isMandatory()
		];

		foreach( $this->getACLActions(false) as $action=>$description ) {
			if(!isset($res['ACL_actions'])) {
				$res['ACL_actions'] = [];
			}

			$res['ACL_actions'][$action] = $description;
		}

		$cleanupArray = function( $data ) use (&$cleanupArray) {
			foreach($data as $k=>$v) {
				if(!$v) {
					unset($data[$k]);
					continue;
				}

				if(is_array($v)) {
					$data[$k] = $cleanupArray($v);
					if(!$data[$k]) {
						unset($data[$k]);
					}
				}
			}

			return $data;
		};

		foreach( $this->pages as $site_id=>$pages ) {
			if(!isset($res['pages'])) {
				$res['pages'] = [];
			}

			if(!isset($res['pages'][$site_id])) {
				$res['pages'][$site_id] = [];
			}

			foreach( $pages as $page_id=>$page ) {
				$page_data = $page->toArray();
				unset($page_data['id']);
				$page_data['relative_path_fragment'] = $page->getRelativePathFragment();

				$page_data = $cleanupArray($page_data);

				$res['pages'][$site_id][$page_id] = $page_data;
			}

		}

		foreach( $this->menu_items as $namespace_id=>$menus ) {
			$namespace = Menus::getSet( $namespace_id );
			if(!$namespace) {
				continue;
			}

			if(!isset($res['menu_items'])) {
				$res['menu_items'] = [];
			}

			$namespace = $namespace->getName();

			if(!isset($res['menu_items'][$namespace])) {
				$res['menu_items'][$namespace] = [];
			}

			foreach( $menus as $menu_id=>$items ) {

				if(!isset($res['menu_items'][$namespace][$menu_id])) {
					$res['menu_items'][$namespace][$menu_id] = [];
				}

				foreach( $items as $item_id=>$item ) {
					/**
					 * @var Menus_Menu_Item $item;
					 */
					$item = $item->toArray();
					$item = $cleanupArray($item);

					$res['menu_items'][$namespace][$menu_id][$item_id] = $item;

				}

			}
		}

		return $res;
	}

	/**
	 *
	 */
	public function create()
	{
		$ok = true;
		try {
			$this->create_saveManifest();
			$this->create_mainClass();
			Application::resetOPCache();
		} catch( Exception $e ) {
			$ok = false;
			Application::handleError( $e );
		}

		return $ok;
	}

	/**
	 *
	 */
	public function create_saveManifest()
	{
		$module_dir = $this->getModuleDir();

		$data = new Data_Array($this->toArray());

		IO_File::write( $module_dir.static::getManifestFileName(), '<?php return '.$data->export() );

	}


	/**
	 *
	 */
	public function create_mainClass()
	{
		$class_name = 'Main';

		$class = new ClassCreator_Class();

		$class->setNamespace( rtrim($this->getNamespace(), '\\') );
		$class->setName( $class_name );

		$extends_ns = 'Jet';
		$extends_class = 'Application_Module';


		$class->addUse( new ClassCreator_UseClass($extends_ns, $extends_class) );
		$class->setExtends( $extends_class );

		$class->write( $this->getModuleDir().'Main.php');


	}

}