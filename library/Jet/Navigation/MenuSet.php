<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Navigation_MenuSet extends BaseObject
{
	/**
	 * @var string
	 */
	protected static $menus_dir_path;

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var string
	 */
	protected $config_file_path = '';

	/**
	 * @var string|null
	 */
	protected $translator_namespace = '';

	/**
	 * @var Navigation_Menu[]
	 */
	protected $menus = [];

	/**
	 * @var Navigation_Menu_Item[]
	 */
	protected $all_menu_items;


	/**
	 * @var Navigation_MenuSet[]
	 */
	protected static $_sets = [];


	/**
	 * @return string
	 */
	public static function getMenusDirPath()
	{
		if(!self::$menus_dir_path) {
			self::$menus_dir_path = SysConf_PATH::MENUS();
		}

		return self::$menus_dir_path;
	}

	/**
	 * @param string $menus_path
	 */
	public static function setMenusDirPath( $menus_path )
	{
		self::$menus_dir_path = $menus_path;
	}

	/**
	 * @param string $name
	 * @param string|null $translator_namespace
	 *
	 * @return Navigation_MenuSet
	 */
	public static function get($name, $translator_namespace=null)
	{
		if(!isset(static::$_sets[$name])) {
			static::$_sets[$name] = new static($name, $translator_namespace);
		}

		return static::$_sets[$name];
	}


	/**
	 * @param string $name
	 * @param string|null $translator_namespace
	 */
	public function __construct( $name, $translator_namespace=null )
	{
		$this->name = $name;
		$this->config_file_path = static::getMenusDirPath().$name.'.php';
		$this->translator_namespace = $translator_namespace;
		$this->init();
	}

	/**
	 *
	 */
	protected function init()
	{
		$menu_data = require $this->config_file_path;

		foreach( $menu_data as $id=>$item_data ) {
			if(empty($item_data['icon'])) {
				$item_data['icon'] = '';
			}

			$root_menu = $this->addMenu(
				$id,
				Tr::_($item_data['label'], [], $this->translator_namespace),
				$item_data['icon']
			);

			if( isset($item_data['items']) ) {
				foreach( $item_data['items'] as $menu_item_id=>$menu_item_data ) {
					$label = Tr::_($menu_item_data['label'], [], $this->translator_namespace);
					$menu_item = new Navigation_Menu_Item( $menu_item_id, $label );
					$menu_item->setData( $menu_item_data );

					$root_menu->addItem( $menu_item );
				}

			}

		}

		$this->initModuleMenuItems();
	}


	/**
	 *
	 */
	protected function initModuleMenuItems()
	{
		foreach( Application_Modules::activatedModulesList() as $manifest ) {
			foreach( $manifest->getMenuItems( $this->name ) as $menu_item ) {

				$m = $this->getMenu( $menu_item->getMenuId() );

				if( $m ) {
					$m->addItem( $menu_item );
				}
			}

		}
	}






	/**
	 * @param string   $id
	 *
	 * @param string   $label
	 * @param string   $icon
	 * @param int|null $index
	 *
	 * @throws Navigation_Menu_Exception
	 *
	 * @return Navigation_Menu
	 */
	public function addMenu( $id, $label, $icon = '', $index = null  )
	{
		if( isset( $this->menus[$id] ) ) {
			throw new Navigation_Menu_Exception( 'Menu ID conflict: '.$id.' Menu set:'.$this->name );
		}

		if( $index===null ) {
			$index = count( $this->menus )+1;
		}

		$menu = new Navigation_Menu( $id, $label, $index, $icon );

		$this->menus[$id] = $menu;

		return $menu;
	}




	/**
	 * @param string $id
	 *
	 * @return Navigation_Menu|null
	 */
	public function getMenu( $id )
	{
		if( !isset( $this->menus[$id] ) ) {
			return null;
		}

		return $this->menus[$id];
	}

	/**
	 * @return Navigation_Menu[]
	 */
	public function getMenus()
	{
		return $this->menus;
	}




}