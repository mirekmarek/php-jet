<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Application_Modules;
use Jet\BaseObject;
use Jet\Http_Request;
use Jet\SysConf_URI;

/**
 *
 */
class Modules extends BaseObject implements Application_Part
{

	/**
	 * @var null|false|Menus_Menu_Item
	 */
	protected static null|false|Menus_Menu_Item $__current_menu_item = null;

	/**
	 * @var null|false|Pages_Page
	 */
	protected static null|false|Pages_Page $__current_page = null;

	/**
	 * @var null|false|Modules_Manifest
	 */
	protected static null|false|Modules_Manifest $__current_module = null;

	/**
	 * @var Modules_Manifest[]
	 */
	protected static ?array $modules = null;


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


	/**
	 * @param string $name
	 *
	 * @return null|Modules_Manifest
	 */
	public static function getModule( string $name ): null|Modules_Manifest
	{
		static::load();

		if( !isset( static::$modules[$name] ) ) {
			return null;
		}

		return static::$modules[$name];
	}

	/**
	 * @param Modules_Manifest $module
	 */
	public static function addModule( Modules_Manifest $module ): void
	{
		static::load();

		static::$modules[$module->getName()] = $module;
	}


	/**
	 * @param string $action
	 * @param array $custom_get_params
	 *
	 * @return string
	 */
	public static function getActionUrl( string $action, array $custom_get_params = [] ): string
	{

		$get_params = [];

		if( Modules::getCurrentModuleName() ) {
			$get_params['module'] = Modules::getCurrentModuleName();
		}

		if( Modules::getCurrentPage() ) {
			$get_params['page'] = Modules::getCurrentPage()->getFullId();
		}

		if( Modules::getCurrentMenuItem() ) {
			$get_params['menu_item'] = Modules::getCurrentMenuItem()->getFullId();
		}

		if( $action ) {
			$get_params['action'] = $action;
		}

		if( $custom_get_params ) {
			foreach( $custom_get_params as $k => $v ) {
				$get_params[$k] = $v;
			}
		}

		return SysConf_URI::getBase() . 'modules.php?' . http_build_query( $get_params );
	}


	/**
	 * @return string|bool
	 */
	public static function getCurrentModuleName(): string|bool
	{
		if( static::getCurrentModule() ) {
			return static::getCurrentModule()->getName();
		}

		return false;
	}


	/**
	 * @return bool|Modules_Manifest
	 */
	public static function getCurrentModule(): bool|Modules_Manifest
	{
		if( static::$__current_module === null ) {
			$id = Http_Request::GET()->getString( 'module' );

			static::$__current_module = false;

			if(
				$id &&
				($module = static::getModule( $id ))
			) {
				static::$__current_module = $module;
			}
		}

		return static::$__current_module;
	}

	/**
	 * @return bool|Menus_Menu_Item
	 */
	public static function getCurrentMenuItem(): bool|Menus_Menu_Item
	{
		if( static::$__current_menu_item === null ) {
			static::$__current_menu_item = false;


			if(
				($module = static::getCurrentModule()) &&
				($id = Http_Request::GET()->getString( 'menu_item' ))
			) {
				$id = explode( '.', $id );

				$item = $module->getMenuItems()->getMenuItem( $id[0], $id[1], $id[2] );

				if( $item ) {
					static::$__current_menu_item = $item;
				}
			}

		}

		return static::$__current_menu_item;

	}


	/**
	 * @return bool|Pages_Page
	 */
	public static function getCurrentPage(): bool|Pages_Page
	{
		if( static::$__current_page === null ) {
			static::$__current_page = false;


			if(
				($module = static::getCurrentModule()) &&
				($id = Http_Request::GET()->getString( 'page' ))
			) {
				$id = explode( '.', $id );

				$page = $module->getPages()->getPage( $id[0], $id[1] );

				if( $page ) {
					static::$__current_page = $page;
				}
			}

		}

		return static::$__current_page;
	}

	/**
	 *
	 */
	public static function setupPageForms(): void
	{
		$page = static::getCurrentPage();

		$page->getEditForm_main()->setAction( Modules::getActionUrl( 'page/edit', ['what' => 'main'] ) );
		$page->getEditForm_content()->setAction( Modules::getActionUrl( 'page/edit', ['what' => 'content'] ) );
		$page->getEditForm_callback()->setAction( Modules::getActionUrl( 'page/edit', ['what' => 'callback'] ) );
		$page->getEditForm_static_content()->setAction( Modules::getActionUrl( 'page/edit', ['what' => 'static_content'] ) );

		$page->getContentCreateForm()->setAction( Modules::getActionUrl( 'page/content/add', ['what' => 'content'] ) );
		$page->getDeleteContentForm()->setAction( Modules::getActionUrl( 'page/content/delete', ['what' => 'content'] ) );

	}

	/**
	 * @return string
	 */
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


	/**
	 * @param string $module_name
	 *
	 * @return bool
	 */
	public static function exists( string $module_name ): bool
	{
		foreach( static::getModules() as $module ) {
			if( $module->getName() == $module_name ) {
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
	public static function createModule( string $module_name, string $module_label ): Modules_Manifest
	{
		$module = new Modules_Manifest();
		$module->setName( $module_name );
		$module->setLabel( $module_label );

		static::addModule( $module );

		return $module;
	}

	/**
	 * @return string|null
	 */
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

}