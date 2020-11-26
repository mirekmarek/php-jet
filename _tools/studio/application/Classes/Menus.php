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
use Jet\SysConf_URI;

/**
 *
 */
class Menus extends BaseObject implements Application_Part
{


	/**
	 * @var Menus_MenuSet
	 */
	protected static $__current_menu_set;


	/**
	 * @var Menus_Menu
	 */
	protected static $__current_menu;

	/**
	 * @var Menus_Menu_Item
	 */
	protected static $__current_menu_item;


	/**
	 * @param $action
	 * @param array $custom_get_params
	 * @param string|null $custom_menu_set
	 * @param string|null $custom_menu_id
	 * @param string|null $custom_menu_item_id
	 *
	 * @return string $url
	 */
	public static function getActionUrl(
		$action,
		array $custom_get_params=[],
		$custom_menu_set=null,
		$custom_menu_id=null,
		$custom_menu_item_id=null
	)
	{

		$get_params = [];

		if(static::getCurrentMenuSetName()) {
			$get_params['set'] = static::getCurrentMenuSetName();

			if(static::getCurrentMenuId()) {
				$get_params['menu'] = static::getCurrentMenuId();

				if(static::getCurrentMenuItemId()) {
					$get_params['item'] = static::getCurrentMenuItemId();
				}
			}
		}

		if($custom_menu_set!==null) {
			$get_params['set'] = $custom_menu_set;
			if(!$custom_menu_set) {
				unset( $get_params['set'] );
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
	public static function getCurrentMenuSetName()
	{
		if(static::getCurrentMenuSet()) {
			return static::getCurrentMenuSet()->getName();
		}

		return false;
	}


	/**
	 * @return null|Menus_MenuSet
	 */
	public static function getCurrentMenuSet()
	{
		if(static::$__current_menu_set===null) {
			$id = Http_Request::GET()->getString('set');

			static::$__current_menu_set = false;

			if(
				$id &&
				($set=static::getSet($id))
			) {
				static::$__current_menu_set = $set;
			}
		}

		return static::$__current_menu_set;
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
	 * @return Menus_Menu|null
	 */
	public static function getCurrentMenu()
	{
		if(!($set = static::getCurrentMenuSet())) {
			return null;
		}



		if(static::$__current_menu===null) {
			$id = Http_Request::GET()->getString('menu');

			static::$__current_menu = false;

			if(
				$id &&
				($menu=$set->getMenu($id))
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
	 * @return Menus_Menu_Item|null
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
	 * @param string $name
	 *
	 * @return Menus_MenuSet|null
	 */
	public static function getSet( $name )
	{
		return Menus_MenuSet::get($name);
	}

	/**
	 * @return Menus_MenuSet[]
	 */
	public static function getSets()
	{
		return Menus_MenuSet::getList();
	}


	/**
	 * @param $id
	 *
	 * @return bool
	 */
	public static function menuExists( $id )
	{
		$set = static::getCurrentMenuSet();
		if(!$set) {
			return false;
		}

		$menu = $set->getMenu( $id );

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




}
