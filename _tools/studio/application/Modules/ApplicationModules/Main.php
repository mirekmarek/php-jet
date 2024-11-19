<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\ApplicationModules;

use Jet\Application_Modules;
use Jet\Factory_Application;
use Jet\Http_Request;
use Jet\IO_Dir;
use Jet\IO_File;
use Jet\MVC_Page_Interface;
use Jet\Navigation_Menu_Item;
use JetStudio\JetStudio;
use JetStudio\JetStudio_Module;
use JetStudio\JetStudio_Module_Manifest;
use JetStudio\JetStudio_Module_Service_ApplicationModules;

/**
 *
 */
class Main extends JetStudio_Module implements JetStudio_Module_Service_ApplicationModules
{
	protected static null|false|Navigation_Menu_Item $current_menu_item = null;
	protected static null|false|MVC_Page_Interface $current_page = null;
	protected static null|false|Modules_Manifest $current_module = null;
	/**
	 * @var Modules_Manifest[]
	 */
	protected static ?array $modules = null;
	
	public function __construct( JetStudio_Module_Manifest $manifest )
	{
		$this->manifest = $manifest;
		
		Factory_Application::setModuleManifestClassName( Modules_Manifest::class );
	}
	
	public function getEditModuleURL( string $module_name ): string
	{
		return $this->manifest->getURL().'?module='.rawurlencode( $module_name );
	}
	
	/**
	 * @return Modules_Manifest[]
	 */
	public static function load(): array
	{
		if( static::$modules === null ) {
			static::$modules = Application_Modules::allModulesList();
		}
		
		return static::$modules;
	}
	
	
	/**
	 * @return Modules_Manifest[]
	 */
	public static function getModules(): array
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
	
	
	public static function getModule( string $name ): null|Modules_Manifest
	{
		static::load();
		
		if( !isset( static::$modules[$name] ) ) {
			return null;
		}
		
		return static::$modules[$name];
	}
	
	public static function addModule( Modules_Manifest $module ): void
	{
		static::load();
		
		static::$modules[$module->getName()] = $module;
	}
	
	public static function getActionUrl( string $action, array $custom_get_params = [] ): string
	{
		
		$get_params = [];
		
		if( static::getCurrentModuleName() ) {
			$get_params['module'] = static::getCurrentModuleName();
		}
		
		if( static::getCurrentPage() ) {
			$get_params['page'] = static::getCurrentPage()->getKey();
		}
		
		if( static::getCurrentMenuItem() ) {
			$get_params['menu_item'] = static::getCurrentMenuItem()->getId( true );
		}
		
		if( $action ) {
			$get_params['action'] = $action;
		}
		
		if( $custom_get_params ) {
			foreach( $custom_get_params as $k => $v ) {
				$get_params[$k] = $v;
			}
		}
		
		return JetStudio::getModuleManifest('ApplicationModules')->getURL() . '?' . http_build_query( $get_params );
	}
	
	public static function getCurrentModuleName(): string|bool
	{
		if( static::getCurrentModule() ) {
			return static::getCurrentModule()->getName();
		}
		
		return false;
	}
	
	public static function getCurrentModule(): bool|Modules_Manifest
	{
		if( static::$current_module === null ) {
			$id = Http_Request::GET()->getString( 'module' );
			
			static::$current_module = false;
			
			if(
				$id &&
				($module = static::getModule( $id ))
			) {
				static::$current_module = $module;
			}
		}
		
		return static::$current_module;
	}
	
	public static function getCurrentMenuItem(): bool|Navigation_Menu_Item
	{
		if( static::$current_menu_item === null ) {
			static::$current_menu_item = false;
			
			
			if(
				($module = static::getCurrentModule()) &&
				($set_id = Http_Request::GET()->getString( 'set' )) &&
				($menu_id = Http_Request::GET()->getString( 'menu' )) &&
				($item_id = Http_Request::GET()->getString( 'item' ))
			) {
				$item = $module->getMenuItems()->getMenuItem( $set_id, $menu_id, $item_id );
				
				if( $item ) {
					static::$current_menu_item = $item;
				}
			}
			
		}
		
		return static::$current_menu_item;
		
	}
	
	public static function getCurrentPage(): bool|MVC_Page_Interface
	{
		if( static::$current_page === null ) {
			static::$current_page = false;
			
			
			if(
				($module = static::getCurrentModule()) &&
				($page_id = Http_Request::GET()->getString( 'page' )) &&
				($base_id = Http_Request::GET()->getString( 'base' ))
			) {
				$page = $module->getPages()->getPage( $base_id, $page_id );
				
				if( $page ) {
					static::$current_page = $page;
				}
			}
			
		}
		
		return static::$current_page;
	}
	
	public static function setupPageForms(): void
	{
		$page = static::getCurrentPage();
		
		$page->getEditForm_main()->setAction( static::getActionUrl( 'page/edit', ['what' => 'main'] ) );
		$page->getEditForm_content()->setAction( static::getActionUrl( 'page/edit', ['what' => 'content'] ) );
		$page->getEditForm_callback()->setAction( static::getActionUrl( 'page/edit', ['what' => 'callback'] ) );
		$page->getEditForm_static_content()->setAction( static::getActionUrl( 'page/edit', ['what' => 'static_content'] ) );
		
		$page->getContentCreateForm()->setAction( static::getActionUrl( 'page/content/add', ['what' => 'content'] ) );
		$page->getDeleteContentForm()->setAction( static::getActionUrl( 'page/content/delete', ['what' => 'content'] ) );
		
	}
	
	public static function getCurrentPage_whatToEdit(): string
	{
		if( !static::getCurrentPage() ) {
			return '';
		}
		return Http_Request::GET()->getString( 'what', 'main', [
			'main',
			'content',
			'static_content',
			'callback'
		] );
	}
	
	
	public static function exists( string $module_name ): bool
	{
		foreach( static::getModules() as $module ) {
			if( $module->getName() == $module_name ) {
				return true;
			}
		}
		
		return false;
	}
	
	public static function createModule( string $module_name, string $module_label ): Modules_Manifest
	{
		$module = new Modules_Manifest();
		$module->setName( $module_name );
		$module->setLabel( $module_label );
		
		static::addModule( $module );
		
		return $module;
	}
	
	public static function getCurrentWhatToEdit(): string|null
	{
		if( !static::getCurrentModule() ) {
			return null;
		}
		if( static::getCurrentMenuItem() ):
			return 'menu_item';
		elseif( static::getCurrentPage() ):
			return 'page';
		else:
			return 'module';
		endif;
	}
	
	public static function clone( Modules_Manifest $old, Modules_Manifest $new ): void
	{
		IO_Dir::copy(
			$old->getModuleDir(),
			$new->getModuleDir()
		);
		
		$old_ns = rtrim( $old->getNamespace(), '\\');
		$new_ns = rtrim( $new->getNamespace(), '\\');
		
		$replaceNs = null;
		$replaceNs = function( string $dir ) use ( &$replaceNs, $old_ns, $new_ns ) {
			$files = IO_Dir::getFilesList( $dir, '*.ph*' );
			
			foreach($files as $path=>$name) {
				$script = IO_File::read( $path );
				
				$script = str_replace($old_ns, $new_ns, $script);
				
				IO_File::write( $path, $script );
			}
			
			$dirs = IO_Dir::getSubdirectoriesList( $dir );
			
			foreach($dirs as $path=>$name) {
				$replaceNs( $path );
			}
		};
		
		$replaceNs( $new->getModuleDir() );
		
		$new->create_saveManifest();
		
	}
	
}