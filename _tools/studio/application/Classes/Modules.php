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
use Jet\Form;
use Jet\Exception;
use Jet\IO_Dir;
use Jet\IO_File;
use Jet\SysConf_URI;

class Modules extends BaseObject implements Application_Part
{

	/**
	 * @var null|Modules_Manifest
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
	 * @param Form $form
	 *
	 * @return bool
	 */
	public static function save( Form $form=null )
	{
		static::load();

		$ok = true;
		try {

			foreach( static::$modules as $id=>$module ) {
				$module->save();
			}

		} catch( Exception $e ) {
			$ok = false;

			Application::handleError( $e, $form );
		}

		return $ok;
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
				'<?php'.JET_EOL.' return '.var_export( $installed, true ).';'.JET_EOL
			);

			IO_File::write(
				$handler->getActivatedModulesListFilePath(),
				'<?php'.JET_EOL.' return '.var_export( $activated, true ).';'.JET_EOL
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
	 * @param string $id
	 *
	 * @return null|Modules_Manifest
	 */
	public static function getModule( $id )
	{
		static::load();

		if(!isset(static::$modules[$id])) {
			return null;
		}

		return static::$modules[$id];
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
	 * @param string $custom_module_id
	 *
	 * @return string $url
	 */
	public static function getActionUrl( $action, array $custom_get_params=[], $custom_module_id=null )
	{

		$get_params = [];

		if(Modules::getCurrentModuleName()) {
			$get_params['module'] = Modules::getCurrentModuleName();
		}

		if($custom_module_id!==null) {
			$get_params['module'] = $custom_module_id;
			if(!$custom_module_id) {
				unset( $get_params['module'] );
			}
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
	 * @return Project_Namespace[]
	 */
	public static function getNamespaces()
	{
		$namespaces = [];


		foreach( Modules::getModules() as $module ) {
			$ns = new Project_Namespace( Project_Namespace::MODULE_NS_PREFIX.$module->getName(), 'Module '.$module->getName() );
			$ns->setNamespace( Project::getApplicationModuleNamespace().'\\'.str_replace('.', '\\', $module->getName()) );
			$ns->setRootDirPath( $module->getModuleDir() );

			$namespaces[$ns->getId()] = $ns;
		}

		return $namespaces;

	}

	/**
	 * @param Menus_MenuNamespace $namespace
	 */
	public static function event_menuNamespaceDeleted( Menus_MenuNamespace $namespace )
	{
		$updated = false;
		foreach( static::getModules() as $module ) {
			if( $module->event_menuNamespaceDeleted( $namespace ) ) {
				$updated = true;
			}
		}

		if( $updated ) {
			Modules::save();
		}
	}

	/**
	 * @param Menus_MenuNamespace_Menu $menu
	 */
	public static function event_menuDeleted( Menus_MenuNamespace_Menu $menu )
	{
		$updated = false;
		foreach( static::getModules() as $module ) {
			if( $module->event_menuDeleted( $menu ) ) {
				$updated = true;
			}
		}

		if( $updated ) {
			Modules::save();
		}
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
	 *
	 */
	public static function synchronize()
	{
		static::load();

		$all_modules = Application_Modules::getHandler()->allModulesList();

		$updated = false;

		foreach( $all_modules as $c_module ) {
			$module = null;

			foreach( static::$modules as $e_module ) {
				if($c_module->getName()==$e_module->getName()) {
					$module = $e_module;
					break;
				}
			}

			if( !$module ) {
				$module = new Modules_Manifest();

				$module->setName( $c_module->getName() );
				$module->setAPIVersion( $c_module->getAPIVersion() );
				$module->setLabel( $c_module->getLabel() );
				$module->setVendor( $c_module->getVendor() );
				$module->setVersion( $c_module->getVersion() );
				$module->setDescription( $c_module->getDescription() );
				$module->setACLActions( $c_module->getACLActions( false ) );
				$module->setIsMandatory( $c_module->isMandatory() );

				static::addModule( $module );

				$main_constants = static::getMainClassConstants( $module );

				foreach( static::readPagesFromExistingModule( $c_module ) as $page ) {
					$module->addPage( $page->getSiteId(), $page );
				}

				foreach( static::getControllerList( $module->getModuleDir(), $main_constants ) as $controller) {
					$module->addController( $controller );
				}

				foreach( static::readMenuItemsFromExistingModule( $c_module ) as $menu ) {
					$module->addMenuItem( $menu );
				}


				$updated = true;
			} else {

				$main_constants = static::getMainClassConstants( $module );

				foreach( static::readPagesFromExistingModule( $c_module ) as $page ) {
					if( !$module->getPage( $page->getSiteId(), $page->getId() ) ) {
						$module->addPage( $page->getSiteId(), $page );
						$updated = true;
					}
				}

				foreach( static::getControllerList( $module->getModuleDir(), $main_constants ) as $controller) {
					$e_controller = null;

					foreach( $module->getControllers() as $c ) {
						if($c->getName()==$controller->getName()) {
							$e_controller = $c;

							break;
						}
					}

					if(!$e_controller) {
						$module->addController( $controller );

						$updated = true;
					} else {
						foreach( $controller->getActions() as $action ) {
							$e_action = null;

							foreach( $e_controller->getActions() as $e_a ) {
								if($e_a->getControllerAction()==$action->getControllerAction()) {
									$e_action = $action;
									break;
								}
							}

							if(!$e_action) {
								$e_controller->addAction( $action );

								$updated = true;
							}
						}
					}
				}

				foreach( static::readMenuItemsFromExistingModule( $c_module ) as $menu_item ) {
					$exist = null;

					foreach( $module->getMenuItemsList( $menu_item->getNamespaceId(), $menu_item->getMenuId() ) as $i ) {
						if($i->getId()==$menu_item->getId()) {
							$exist = $i;
							break;
						}

					}

					if(!$exist) {
						$module->addMenuItem( $menu_item );

						$updated = true;
					}
				}

			}
		}

		if($updated) {
			static::save();
		}
	}


	/**
	 * @param Application_Module_Manifest $c_module
	 *
	 * @return Menus_MenuNamespace_Menu_Item[]
	 */
	protected static function readMenuItemsFromExistingModule( Application_Module_Manifest $c_module )
	{
		$res = [];
		foreach( $c_module->getMenuItemsRaw() as $namespace_name=>$menu_data ) {

			$namespace = null;
			foreach( Menus::getMenuNamespaces() as $ns ) {
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
					$i = Menus_MenuNamespace_Menu_Item::fromArray( $item_id, $item );
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
	 * @param Modules_Manifest $module
	 *
	 * @return array
	 */
	protected static function getMainClassConstants( Modules_Manifest $module )
	{
		$main_constants = [];

		$main_script_path = $module->getModuleDir().'Main.php';
		if( IO_File::exists($main_script_path) ) {
			$parser = new ClassParser( IO_File::read($main_script_path) );
			if($parser->classes['Main']) {
				$main_class = $parser->classes['Main'];

				foreach( $main_class->constants as $constant ) {
					$main_constants[ $constant->name ] = eval('return '.$constant->value);
				}
			}
		}

		return $main_constants;
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
					strpos($class->extends, 'Controller')===false ||
					strpos($class->extends, 'Controller_Router')!==false
				) {
					continue;
				}

				$ACL_ACTIONS_MAP = [];


				if(isset($class->constants['ACL_ACTIONS_MAP'])) {

					$value = $class->constants['ACL_ACTIONS_MAP']->value;

					foreach( $main_constants as $c_n=>$c_v ) {
						$value = str_replace( 'Main::'.$c_n, var_export( $c_v, true ), $value );
					}

					$ACL_ACTIONS_MAP = eval( 'return '.$value );

				}

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

}