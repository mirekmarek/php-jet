<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\AdminUI;

use Jet\Application_Modules;
use Jet\Application_Modules_Module_Abstract;
use JetUI\menu;
use JetUI\menu_item;
use Jet\Tr;

use JetExampleApp\Application_Modules_Module_Manifest;

/**
 *
 */
class Main extends Application_Modules_Module_Abstract {

	/**
	 * @var menu_item[]
	 */
	protected static $menu_items;
	/**
	 *
	 */
	public function initialize() {
	}

	/**
	 * @return menu_item[]
	 */
	public static function getMenuItems() {
		if(static::$menu_items!==null) {
			return static::$menu_items;
		}

		menu::addMenu('content', 'Content', 1);
		menu::addMenu('system', 'System', 3);

		static::$menu_items = [];

		foreach( Application_Modules::getActivatedModulesList() as $manifest ) {
			/**
			 * @var Application_Modules_Module_Manifest $manifest
			 */
			foreach( $manifest->getMenuItems() as $id=>$menu_data ) {

				$menu_data['id'] = $id;

				$menu_item = new menu_item(
					$menu_data['parent_menu_id'],
					$menu_data['id'],
					Tr::_($menu_data['label'], [], $manifest->getName())
				);

				if(isset($menu_data['index'])) {
					$menu_item->setIndex($menu_data['index']);
				}
				if(isset($menu_data['icon'])) {
					$menu_item->setIcon($menu_data['icon']);
				}
				if(isset($menu_data['page_id'])) {
					$menu_item->setPageId($menu_data['page_id']);
				}
				if(isset($menu_data['url_parts'])) {
					$menu_item->setUrlParts($menu_data['url_parts']);
				}
				if(isset($menu_data['URL'])) {
					$menu_item->setURL($menu_data['URL']);
				}
				if(isset($menu_data['separator_before'])) {
					$menu_item->setSeparatorBefore($menu_data['separator_before']);
				}
				if(isset($menu_data['separator_after'])) {
					$menu_item->setSeparatorAfter($menu_data['separator_after']);
				}


				if(!$menu_item->getAccessAllowed()) {
					continue;
				}

				static::$menu_items[$menu_item->getId()] = $menu_item;

				$menu = menu::getMenu( $menu_item->getParentMenuId() );
				$menu->addMenuItem( $menu_item );
			}

		}

		return static::$menu_items;
	}

}