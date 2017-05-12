<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\AdminUI;

use Jet\Application_Modules;
use Jet\Application_Module;
use Jet\Navigation_Menu;
use Jet\Navigation_Menu_Item;
use Jet\Navigation_Breadcrumb;
use Jet\Tr;
use Jet\Mvc;

use JetUI\UI;

use JetExampleApp\Mvc_Page;
use JetExampleApp\Application_Module_Manifest;

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

		Navigation_Menu::addRootMenu( 'content', 'Content', 1 );
		Navigation_Menu::addRootMenu( 'system', 'System', 3 );


		foreach( Application_Modules::getActivatedModulesList() as $manifest ) {
			/**
			 * @var Application_Module_Manifest $manifest
			 */
			foreach( $manifest->getMenuItems() as $id => $menu_data ) {

				$menu_data['id'] = $id;
				$menu_data['label'] = Tr::_( $menu_data['label'], [], $manifest->getName() );

				$menu_id = $menu_data['menu_id'];
				unset($menu_data['menu_id']);

				$menu_item = new Navigation_Menu_Item(
					$menu_data['id'],
					$menu_data['label']
				);

				$menu_item->setData( $menu_data );


				$menu = Navigation_Menu::getMenu( $menu_id );
				$menu->addItem( $menu_item );
			}

		}

		static::$menu_items_init = true;

	}

}