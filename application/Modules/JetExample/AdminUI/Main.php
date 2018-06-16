<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\AdminUI;

use Jet\Application_Modules;
use Jet\Application_Module;
use Jet\Application_Module_Manifest;
use Jet\Navigation_Menu;
use Jet\Navigation_Menu_Item;
use Jet\Navigation_Breadcrumb;
use Jet\Tr;
use Jet\Mvc;

use Jet\UI;

use Jet\Mvc_Page;

/**
 *
 */
class Main extends Application_Module
{

	/**
	 * @var bool
	 */
	protected static $menu_items_init;

	/**
	 *
	 */
	public static function initBreadcrumb()
	{
		/**
		 * @var Mvc_Page $page
		 */
		$page = Mvc::getCurrentPage();

		Navigation_Breadcrumb::reset();

		Navigation_Breadcrumb::addURL(
			UI::icon( $page->getIcon() ).'&nbsp;&nbsp;'.$page->getBreadcrumbTitle(),
			$page->getURL()
		);

	}

	/**
	 *
	 */
	public static function initMenuItems()
	{
		if( static::$menu_items_init ) {
			return;
		}

		Navigation_Menu::addRootMenu( 'content', Tr::_('Content'), 1 );
		Navigation_Menu::addRootMenu( 'system', Tr::_('System'), 3 );


		foreach( Application_Modules::activatedModulesList() as $manifest ) {
			/**
			 * @var Application_Module_Manifest $manifest
			 */
			foreach( $manifest->getAdminMenuItems() as $menu_item_definition ) {


				$menu_item = new Navigation_Menu_Item(
					$menu_item_definition->getItemId(),
					Tr::_( $menu_item_definition->getLabel(), [], $manifest->getName() )
				);

				$data = $menu_item_definition->asArray();
				unset($data['item_id']);
				unset($data['menu_id']);

				$menu_item->setData( $data );


				$menu = Navigation_Menu::getMenu( $menu_item_definition->getMenuId() );
				$menu->addItem( $menu_item );
			}

		}

		static::$menu_items_init = true;

	}

}