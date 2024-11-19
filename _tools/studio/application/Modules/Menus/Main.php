<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\Menus;

use Jet\Http_Request;
use JetStudio\JetStudio;
use JetStudio\JetStudio_Module;
use JetStudio\JetStudio_Module_Manifest;
use JetStudio\JetStudio_Module_Service_Menus;

class Main extends JetStudio_Module implements JetStudio_Module_Service_Menus
{
	protected static bool $plugin_mode = false;
	protected string $plugin_output = '';
	protected static MenuSet|null|bool $current_menu_set = null;
	protected static Menu|null|bool $current_menu = null;
	protected static Menu_Item|null|bool $current_menu_item = null;

	public function __construct( JetStudio_Module_Manifest $manifest )
	{
		$this->manifest = $manifest;
	}
	
	public function editMenuItem( string $set_id, string $menu_id, string $item_id ): string
	{
		static::$plugin_mode = true;
		
		static::$current_menu_set = MenuSet::get( $set_id );
		static::$current_menu = static::$current_menu_set->getMenu( $menu_id );
		static::$current_menu_item = static::$current_menu->getItem( $item_id );
		
		$this->getView()->setVar('plugin_mode', true);
		
		$this->handle();
		
		return $this->plugin_output;
	}
	
	public function output( string $view_script ) : void
	{
		if(!static::$plugin_mode) {
			parent::output( $view_script );
			return;
		}
		
		$this->plugin_output = $this->getView()->render( $view_script );
		
	}
	
	public static function getActionUrl(
		string $action,
		array $custom_get_params = [],
		?string $custom_menu_set = null,
		?string $custom_menu_id = null,
		?string $custom_menu_item_id = null
	) : string
	{
		
		$get_params = [];
		
		if( static::getCurrentMenuSetName() ) {
			$get_params['set'] = static::getCurrentMenuSetName();
			
			if( static::getCurrentMenuId() ) {
				$get_params['menu'] = static::getCurrentMenuId();
				
				if( static::getCurrentMenuItemId() ) {
					$get_params['item'] = static::getCurrentMenuItemId();
				}
			}
		}
		
		if( $custom_menu_set !== null ) {
			$get_params['set'] = $custom_menu_set;
			if( !$custom_menu_set ) {
				unset( $get_params['set'] );
			}
		}
		
		if( $custom_menu_id !== null ) {
			$get_params['menu'] = $custom_menu_id;
			
			if( !$custom_menu_id ) {
				unset( $get_params['menu'] );
			}
		}
		
		if( $custom_menu_item_id !== null ) {
			$get_params['item'] = $custom_menu_item_id;
			
			if( !$custom_menu_item_id ) {
				unset( $get_params['item'] );
			}
		}
		
		
		if( $action ) {
			$get_params['menus_action'] = $action;
		}
		
		if( $custom_get_params ) {
			foreach( $custom_get_params as $k => $v ) {
				$get_params[$k] = $v;
			}
		}
		
		if(static::$plugin_mode) {
			return Http_Request::currentURL(set_GET_params: $get_params);
		}
		
		return JetStudio::getModuleManifest('Menus')->getURL().'?'.http_build_query( $get_params );
	}
	
	
	public static function getCurrentMenuSetName(): string|bool
	{
		if( static::getCurrentMenuSet() ) {
			return static::getCurrentMenuSet()->getName();
		}
		
		return false;
	}
	
	
	public static function getCurrentMenuSet(): bool|MenuSet
	{
		if( static::$current_menu_set === null ) {
			$id = Http_Request::GET()->getString( 'set' );
			
			static::$current_menu_set = false;
			
			if(
				$id &&
				($set = static::getSet( $id ))
			) {
				static::$current_menu_set = $set;
			}
		}
		
		return static::$current_menu_set;
	}
	
	
	public static function getCurrentMenuId(): string|bool
	{
		if( static::getCurrentMenu() ) {
			return static::getCurrentMenu()->getId();
		}
		
		return false;
	}
	
	public static function getCurrentMenu(): Menu|bool
	{
		if( !($set = static::getCurrentMenuSet()) ) {
			return false;
		}
		
		
		if( static::$current_menu === null ) {
			$id = Http_Request::GET()->getString( 'menu' );
			
			static::$current_menu = false;
			
			if(
				$id &&
				($menu = $set->getMenu( $id ))
			) {
				/** @noinspection PhpFieldAssignmentTypeMismatchInspection */
				static::$current_menu = $menu;
			}
		}
		
		return static::$current_menu;
	}
	
	public static function getCurrentMenuItemId(): string|bool
	{
		if( static::getCurrentMenuItem() ) {
			return static::getCurrentMenuItem()->getId();
		}
		
		return false;
	}
	
	public static function getCurrentMenuItem(): Menu_Item|bool
	{
		if( !($menu = static::getCurrentMenu()) ) {
			return false;
		}
		
		
		if( static::$current_menu_item === null ) {
			$id = Http_Request::GET()->getString( 'item' );
			
			static::$current_menu_item = false;
			
			if(
				$id &&
				($item = $menu->getItem( $id ))
			) {
				static::$current_menu_item = $item;
			}
		}
		
		return static::$current_menu_item;
	}
	
	public static function getSet( string $name ): MenuSet|null
	{
		if(!MenuSet::exists($name)) {
			return null;
		}
		
		return MenuSet::get( $name );
	}
	
	public static function getSets(): array
	{
		return MenuSet::getList();
	}
	
	public static function menuExists( string $id ): bool
	{
		$set = static::getCurrentMenuSet();
		if( !$set ) {
			return false;
		}
		
		$menu = $set->getMenu( $id );
		
		if( !$menu ) {
			return false;
		}
		
		return true;
	}
	
	public static function menuItemExists( string $id ): bool
	{
		$menu = static::getCurrentMenu();
		if( !$menu ) {
			return false;
		}
		
		$item = $menu->getItem( $id );
		
		if( !$item ) {
			return false;
		}
		
		return true;
	}
	
}
