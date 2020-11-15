<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\BaseObject;
use Jet\Http_Request;
use Jet\Form;
use Jet\Exception;
use Jet\Data_Array;
use Jet\Navigation_Menu;
use Jet\IO_File;
use Jet\SysConf_PATH;
use Jet\SysConf_URI;

/**
 *
 */
class Menus extends BaseObject implements Application_Part
{

	/**
	 * @var Menus_MenuNamespace[]
	 */
	protected static $menu_namespaces;

	/**
	 * @var Menus_MenuNamespace
	 */
	protected static $__current_menu_namespace;


	/**
	 * @var Menus_MenuNamespace_Menu
	 */
	protected static $__current_menu;

	/**
	 * @var Menus_MenuNamespace_Menu_Item
	 */
	protected static $__current_menu_item;


	/**
	 * @param $action
	 * @param array $custom_get_params
	 * @param string|null $custom_menu_namespace_id
	 * @param string|null $custom_menu_id
	 * @param string|null $custom_menu_item_id
	 *
	 * @return string $url
	 */
	public static function getActionUrl(
		$action,
		array $custom_get_params=[],
		$custom_menu_namespace_id=null,
		$custom_menu_id=null,
		$custom_menu_item_id=null
	)
	{

		$get_params = [];

		if(static::getCurrentMenuNamespaceName()) {
			$get_params['namespace'] = static::getCurrentMenuNamespaceName();

			if(static::getCurrentMenuId()) {
				$get_params['menu'] = static::getCurrentMenuId();

				if(static::getCurrentMenuItemId()) {
					$get_params['item'] = static::getCurrentMenuItemId();
				}
			}
		}

		if($custom_menu_namespace_id!==null) {
			$get_params['namespace'] = $custom_menu_namespace_id;
			if(!$custom_menu_namespace_id) {
				unset( $get_params['namespace'] );
			}
		}

		if($custom_menu_id!==null) {
			$get_params['menu'] = $custom_menu_id;

			if(!$custom_menu_id) {
				unset($get_params['menu']);
			}
		}

		if($custom_menu_item_id!==null) {
			$get_params['item'] = $custom_menu_item_id;

			if(!$custom_menu_item_id) {
				unset($get_params['item']);
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

		return SysConf_URI::BASE().'menus.php?'.http_build_query($get_params);
	}


	/**
	 * @return string|bool
	 */
	public static function getCurrentMenuNamespaceName()
	{
		if(static::getCurrentMenuNamespace()) {
			return static::getCurrentMenuNamespace()->getName();
		}

		return false;
	}


	/**
	 * @return null|Menus_MenuNamespace
	 */
	public static function getCurrentMenuNamespace()
	{
		if(static::$__current_menu_namespace===null) {
			$id = Http_Request::GET()->getString('namespace');

			static::$__current_menu_namespace = false;

			if(
				$id &&
				($namespace=static::getMenuNamespace($id))
			) {
				static::$__current_menu_namespace = $namespace;
			}
		}

		return static::$__current_menu_namespace;
	}


	/**
	 * @return string|bool
	 */
	public static function getCurrentMenuId()
	{
		if(static::getCurrentMenu()) {
			return static::getCurrentMenu()->getId();
		}

		return false;
	}


	/**
	 * @return Menus_MenuNamespace_Menu|null
	 */
	public static function getCurrentMenu()
	{
		if(!($namespace = static::getCurrentMenuNamespace())) {
			return null;
		}



		if(static::$__current_menu===null) {
			$id = Http_Request::GET()->getString('menu');

			static::$__current_menu = false;

			if(
				$id &&
				($menu=$namespace->getMenu($id))
			) {
				static::$__current_menu = $menu;
			}
		}

		return static::$__current_menu;
	}



	/**
	 * @return string|bool
	 */
	public static function getCurrentMenuItemId()
	{
		if(static::getCurrentMenuItem()) {
			return static::getCurrentMenuItem()->getId();
		}

		return false;
	}


	/**
	 * @return Menus_MenuNamespace_Menu_Item|null
	 */
	public static function getCurrentMenuItem()
	{
		if(!($menu = static::getCurrentMenu())) {
			return null;
		}



		if(static::$__current_menu_item===null) {
			$id = Http_Request::GET()->getString('item');

			static::$__current_menu_item = false;

			if(
				$id &&
				($item=$menu->getItem($id))
			) {
				static::$__current_menu_item = $item;
			}
		}

		return static::$__current_menu_item;
	}

	
	/**
	 * @return Menus_MenuNamespace[]
	 */
	public static function load()
	{
		if(static::$menu_namespaces===null) {
			static::$menu_namespaces = [];


			$target_path = ProjectConf_PATH::CONFIG().Navigation_Menu::getMenuConfigFileName();

			if( IO_File::isReadable($target_path) ) {
				static::load();

				/** @noinspection PhpIncludeInspection */
				$data = require $target_path;

				foreach( $data as $ns_name=>$menus ) {

					$namespace = new Menus_MenuNamespace();
					$namespace->setName( $ns_name );

					static::addMenuNamespace( $namespace );


					foreach( $menus as $menu_id=>$menu_data ) {
						$menu = Menus_MenuNamespace_Menu::fromArray( $menu_id, $menu_data );

						$namespace->addMenu( $menu );
					}

				}
			}
		}

		return static::$menu_namespaces;
	}

	/**
	 * @param Form|null $form
	 *
	 * @return bool
	 */
	public static function save( Form $form=null )
	{
		static::load();

		$ok = true;
		try {

			foreach( static::$menu_namespaces as $id=>$namespace ) {
				Project::writeProjectEntity('menus', $id, $namespace );
			}

		} catch( Exception $e ) {
			$ok = false;

			Application::handleError( $e, $form );
		}

		return $ok;
	}

	/**
	 * @param Menus_MenuNamespace $namespace
	 */
	public static function addMenuNamespace( Menus_MenuNamespace $namespace )
	{
		static::load();
		static::$menu_namespaces[$namespace->getName()] = $namespace;
	}

	/**
	 * @param string $id
	 *
	 * @return Menus_MenuNamespace|bool
	 */
	public static function deleteMenuNamespace( $id )
	{
		static::load();
		if( !isset(static::$menu_namespaces[$id]) ) {
			return false;
		}

		$namespace = static::$menu_namespaces[$id];

		try {

			Project::deleteProjectEntity('menus', $id );

		} catch( Exception $e ) {
			Application::handleError( $e );

			return false;
		}

		unset( static::$menu_namespaces[$id] );

		Project::event('menuNamespaceDeleted', $namespace);

		return $namespace;
	}

	/**
	 * @param string $id
	 *
	 * @return Menus_MenuNamespace|null
	 */
	public static function getMenuNamespace( $id )
	{
		static::load();
		if( !isset(static::$menu_namespaces[$id]) ) {
			return null;
		}

		return static::$menu_namespaces[$id];
	}

	/**
	 * @return Menus_MenuNamespace[]
	 */
	public static function getMenuNamespaces()
	{
		static::load();
		return static::$menu_namespaces;
	}


	/**
	 * @param $id
	 *
	 * @return bool
	 */
	public static function menuExists( $id )
	{
		$namespace = static::getCurrentMenuNamespace();
		if(!$namespace) {
			return false;
		}

		$menu = $namespace->getMenu( $id );

		if(!$menu) {
			return false;
		}

		return true;
	}


	/**
	 * @param $id
	 *
	 * @return bool
	 */
	public static function menuItemExists( $id )
	{
		$menu = static::getCurrentMenu();
		if(!$menu) {
			return false;
		}

		$item = $menu->getItem( $id );

		if(!$item) {
			return false;
		}

		return true;
	}

	/**
	 *
	 */
	public static function generate()
	{
		$namespaces = Menus::getMenuNamespaces();

		$res = [];

		foreach( $namespaces as $ns ) {
			$ns_name = $ns->getName();
			$res[$ns_name] = [];

			foreach( $ns->getMenus() as $menu ) {
				$menu_id = $menu->getId();
				/**
				 * @var Menus_MenuNamespace_Menu $menu
				 */

				$res[$ns_name][$menu_id] = $menu->toArray();

			}
		}

		$res = new Data_Array($res);


		$target_path = SysConf_PATH::APPLICATION().Navigation_Menu::getMenuConfigFileName();

		IO_File::write( $target_path, '<?php return '.$res->export() );

	}



}
