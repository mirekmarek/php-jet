<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\Application_Modules_Handler_Default;
use Jet\Application_Module_Manifest;
use Jet\Application_Modules;
use Jet\BaseObject;
use Jet\Http_Request;
use Jet\ClassParser;
use Jet\Exception;
use Jet\IO_Dir;
use Jet\IO_File;
use Jet\SysConf_URI;

/**
 *
 */
class Modules extends BaseObject implements Application_Part
{

	/**
	 * @var null|false|Menus_Menu_Item
	 */
	protected static $__current_menu_item;

	/**
	 * @var null|false|Pages_Page
	 */
	protected static $__current_page;

	/**
	 * @var null|false|Modules_Manifest
	 */
	protected static $__current_module;

	/**
	 * @var Modules_Manifest[]
	 */
	protected static $modules;



	/**
	 * @return Modules_Manifest[]
	 */
	public static function load()
	{
		if(static::$modules===null) {
			static::$modules = Application_Modules::allModulesList();
		}

		return static::$modules;
	}



	/**
	 *
	 * @return bool
	 */
	public static function setInstalledAndActivatedList()
	{

		$installed = [];
		$activated = [];

		foreach( static::getModules() as $module ) {
			if( $module->getIsInstalled() ) {
				$installed[] = $module->getName();

				if( $module->getIsActive() ) {
					$activated[] = $module->getName();
				}
			}

		}


		/**
		 * @var Application_Modules_Handler_Default $handler
		 */
		$handler = Application_Modules::getHandler();

		$ok = true;
		try {

			IO_File::write(
				$handler->getInstalledModulesListFilePath(),
				'<?php'.PHP_EOL.' return '.var_export( $installed, true ).';'.PHP_EOL
			);

			IO_File::write(
				$handler->getActivatedModulesListFilePath(),
				'<?php'.PHP_EOL.' return '.var_export( $activated, true ).';'.PHP_EOL
			);


		} catch( Exception $e ) {
			$ok = false;

			Application::handleError( $e );
		}

		return $ok;
	}

	
	
	/**
	 * @return Modules_Manifest[]
	 */
	public static function getModules()
	{
		static::load();

		uasort( static::$modules, function(
			Modules_Manifest $a,
			Modules_Manifest $b
		) {
			return strcmp( $a->getName(), $b->getName() );
		} );

		return static::$modules;
	}


	/**
	 * @param string $name
	 *
	 * @return null|Modules_Manifest
	 */
	public static function getModule( $name )
	{
		static::load();

		if(!isset( static::$modules[$name])) {
			return null;
		}

		return static::$modules[$name];
	}

	/**
	 * @param Modules_Manifest $module
	 */
	public static function addModule(Modules_Manifest $module )
	{
		static::load();

		static::$modules[$module->getName()] = $module;
	}




	/**
	 * @param $action
	 * @param array $custom_get_params
	 *
	 * @return string $url
	 */
	public static function getActionUrl( $action, array $custom_get_params=[] )
	{

		$get_params = [];

		if(Modules::getCurrentModuleName()) {
			$get_params['module'] = Modules::getCurrentModuleName();
		}

		if(Modules::getCurrentPage()) {
			$get_params['page'] = Modules::getCurrentPage()->getFullId();
		}

		if(Modules::getCurrentMenuItem()) {
			$get_params['menu_item'] = Modules::getCurrentMenuItem()->getFullId();
		}

		if($action) {
			$get_params['action'] = $action;
		}

		if($custom_get_params) {
			foreach( $custom_get_params as $k=>$v ) {
				$get_params[$k] = $v;
			}
		}

		return SysConf_URI::BASE().'modules.php?'.http_build_query($get_params);
	}


	/**
	 * @return string|bool
	 */
	public static function getCurrentModuleName()
	{
		if(static::getCurrentModule()) {
			return static::getCurrentModule()->getName();
		}

		return false;
	}


	/**
	 * @return null|Modules_Manifest
	 */
	public static function getCurrentModule()
	{
		if(static::$__current_module===null) {
			$id = Http_Request::GET()->getString('module');

			static::$__current_module = false;

			if(
				$id &&
				($module=static::getModule($id))
			) {
				static::$__current_module = $module;
			}
		}

		return static::$__current_module;
	}

	/**
	 * @return false|Menus_Menu_Item
	 */
	public static function getCurrentMenuItem()
	{
		if(static::$__current_menu_item===null) {
			static::$__current_menu_item = false;


			if(
				($module = static::getCurrentModule()) &&
				($id = Http_Request::GET()->getString('menu_item'))
			) {
				$id = explode('.', $id);

				$item = $module->getMenuItem( $id[0], $id[1], $id[2] );

				if($item) {
					static::$__current_menu_item = $item;
				}
			}

		}

		return static::$__current_menu_item;

	}


	/**
	 * @return false|Pages_Page
	 */
	public static function getCurrentPage()
	{
		if(static::$__current_page===null) {
			static::$__current_page = false;


			if(
				($module = static::getCurrentModule()) &&
				($id = Http_Request::GET()->getString('page'))
			) {
				$id = explode('.', $id);

				$page = $module->getPage( $id[0], $id[1] );

				if($page) {
					static::$__current_page = $page;
				}
			}

		}

		return static::$__current_page;
	}

	/**
	 *
	 */
	public static function setupPageForms()
	{
		$page = static::getCurrentPage();

		$page->getEditForm_main()->setAction( Modules::getActionUrl('page/edit', ['what'=>'main']) );
		$page->getEditForm_content()->setAction( Modules::getActionUrl('page/edit', ['what'=>'content']) );
		$page->getEditForm_callback()->setAction( Modules::getActionUrl('page/edit', ['what'=>'callback']) );
		$page->getEditForm_static_content()->setAction( Modules::getActionUrl('page/edit', ['what'=>'static_content']) );

		$page->getContentCreateForm()->setAction( Modules::getActionUrl('page/content/add', ['what'=>'content']) );
		$page->getDeleteContentForm()->setAction(Modules::getActionUrl('page/content/delete', ['what'=>'content']));

	}

	/**
	 * @return string
	 */
	public static function getCurrentPage_whatToEdit()
	{
		if(!static::getCurrentPage()) {
			return '';
		}
		return Http_Request::GET()->getString('what', 'main', [ 'main', 'content', 'static_content', 'callback' ]);
	}



	/**
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public static function exists( $module_name )
	{
		foreach( static::getModules() as $module ) {
			if($module->getName()==$module_name) {
				return true;
			}
		}

		return false;
	}




	/**
	 * @param string $module_name
	 * @param string $module_label
	 *
	 * @return Modules_Manifest
	 */
	public static function createModule( $module_name, $module_label )
	{
		$module = new Modules_Manifest();
		$module->setName( $module_name );
		$module->setLabel( $module_label );

		$controller = new Modules_Module_Controller();
		$controller->setName('Main');
		$controller->setActions(['default'=>'']);

		$module->addController( $controller );

		static::addModule( $module );

		return $module;
	}

	/**
	 *
	 */
	public static function installAllModules()
	{
		foreach( static::getModules() as $module ) {
			$module->setIsInstalled(true);
			$module->setIsActive(true);
		}

		static::save();
		static::setInstalledAndActivatedList();
	}




	/**
	 * @param Application_Module_Manifest $c_module
	 *
	 * @return Menus_Menu_Item[]
	 */
	protected static function readMenuItemsFromExistingModule( Application_Module_Manifest $c_module )
	{
		$res = [];
		foreach( $c_module->getMenuItemsRaw() as $namespace_name=>$menu_data ) {

			$namespace = null;
			foreach( Menus::getSets() as $ns ) {
				if($ns->getName()==$namespace_name) {
					$namespace = $ns;

					break;
				}
			}

			if(!$namespace) {
				continue;
			}

			foreach( $menu_data as $menu_id=>$items ) {
				foreach( $items as $item_id=>$item ) {
					$i = Menus_Menu_Item::fromArray( $item_id, $item );
					$i->setNamespaceId( $namespace->getInternalId() );
					$i->setMenuId( $menu_id );

					$res[] = $i;

				}
			}
		}

		return $res;
	}

	/**
	 * @param Application_Module_Manifest $c_module
	 *
	 * @return Pages_Page[]
	 */
	protected static function readPagesFromExistingModule( Application_Module_Manifest $c_module )
	{

		$pages = [];

		foreach( $c_module->getPagesRaw() as $site_id=>$page_list ) {
			foreach( $page_list as $page_id=>$page_data ) {
				if(isset($page_data['contents'])) {
					foreach($page_data['contents'] as $i=>$content) {
						if(
							!empty($content['controller_action']) &&
							empty($content['module_name'])
						) {
							$page_data['contents'][$i]['module_name'] = $c_module->getName();
						}
					}
				}

				$page = Pages_Page::fromArray( $site_id, $page_id, $page_data );

				$pages[] = $page;
			}
		}

		return $pages;

	}


	/**
	 * @param $dir
	 * @param $main_constants
	 *
	 * @return Modules_Module_Controller[]
	 */
	protected static function getControllerList( $dir, array $main_constants )
	{
		$controllers = [];

		$dirs = IO_Dir::getList( $dir, '*', true, false );

		foreach( $dirs as $path=>$name ) {
			foreach( static::getControllerList( $path, $main_constants ) as $controller ) {
				$controllers[] = $controller;
			}
		}

		$files = IO_Dir::getList( $dir, '*.php', false, true );

		foreach( $files as $path=>$name ) {

			$parser = new ClassParser( IO_File::read($path) );

			foreach( $parser->classes as $class ) {

				if(
					substr($class->name, 0, 11) != 'Controller_' ||
					!str_contains( $class->extends, 'Controller' ) ||
					str_contains( $class->extends, 'Controller_Router' )
				) {
					continue;
				}

				$ACL_ACTIONS_MAP = [];



				$controller = new Modules_Module_Controller();
				$controller->setName( substr($class->name, 11) );
				$controller->setExtendsClass( $class->extends );

				foreach( $class->methods as $method ) {
					if(substr($method->name, -7)!='_Action') {
						continue;
					}

					$action_name = substr($method->name, 0, -7);

					$action = new Modules_Module_Controller_Action();
					$action->setControllerAction( $action_name );

					if(isset($ACL_ACTIONS_MAP[$action_name])) {
						$ACL_action = $ACL_ACTIONS_MAP[$action_name];

						if(!$ACL_action) {
							$ACL_action = 'false';
						}

						$action->setACLAction( $ACL_action );
					}


					$controller->addAction( $action );
				}

				$controllers[] = $controller;

			}
		}

		return $controllers;
	}

	/**
	 * @return string|null
	 */
	public static function getCurrentWhatToEdit()
	{
		if(!static::getCurrentModule()) {
			return null;
		}
		if(static::getCurrentMenuItem()):
			return 'menu_item';
		elseif(static::getCurrentPage()):
			return 'page';
		else:
			return 'module';
		endif;
	}

}