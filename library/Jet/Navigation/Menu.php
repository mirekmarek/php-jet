<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Navigation_Menu extends BaseObject
{
	/**
	 * @var string
	 */
	protected static $menu_config_file_name = 'menus.php';

	/**
	 * @var Navigation_Menu[]|array
	 */
	protected static $root_menus = [];

	/**
	 * @var Navigation_Menu[]|array
	 */
	protected static $all_menus = [];

	/**
	 * @var Navigation_Menu_Item[]|array
	 */
	protected static $all_menu_items;

	/**
	 * @var string
	 */
	protected $id = '';

	/**
	 * @var Navigation_Menu
	 */
	protected $parent_menu;

	/**
	 * @var string
	 */
	protected $label = '';

	/**
	 * @var string
	 */
	protected $icon = '';

	/**
	 * @var int
	 */
	protected $index = 0;

	/**
	 * @var Navigation_Menu_Item[]|Navigation_Menu[]
	 */
	protected $items = [];

	/**
	 * @return string
	 */
	public static function getMenuConfigFileName()
	{
		return self::$menu_config_file_name;
	}

	/**
	 * @param string $menu_config_file_name
	 */
	public static function setMenuConfigFileName( $menu_config_file_name )
	{
		self::$menu_config_file_name = $menu_config_file_name;
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
	public static function addRootMenu( $id, $label, $icon = '', $index = null  )
	{
		if( isset( static::$root_menus[$id] ) ) {
			throw new Navigation_Menu_Exception( 'Menu ID conflict: '.$id );
		}

		if( $index===null ) {
			$index = count( static::$root_menus )+1;
		}

		$menu = new static( $id, $label, $index, $icon );

		static::$root_menus[$id] = $menu;
		static::$all_menus[$id] = $menu;

		return $menu;
	}

	/**
	 * @param string $menu_namespace
	 * @param string|null $translator_namespace
	 */
	public static function initRootMenu( $menu_namespace, $translator_namespace=null )
	{
		$path = Config::getConfigDirPath().static::getMenuConfigFileName();

		$menu_data = require $path;

		if(isset($menu_data[$menu_namespace])) {
			static::initRootMenuByData( $menu_data[$menu_namespace], $translator_namespace );
		}
	}

	/**
	 * @param array $data
	 * @param null|string $translator_namespace
	 */
	public static function initRootMenuByData( array $data, $translator_namespace = null )
	{

		foreach( $data as $id=>$item_data ) {
			if(empty($item_data['icon'])) {
				$item_data['icon'] = '';
			}

			$root_menu = Navigation_Menu::addRootMenu(
				$id,
				Tr::_($item_data['label'], [], $translator_namespace),
				$item_data['icon']
			);

			if( isset($item_data['items']) ) {
				foreach( $item_data['items'] as $id=>$menu_item_data ) {
					$label = Tr::_($menu_item_data['label'], [], $translator_namespace);
					$menu_item = new Navigation_Menu_Item( $id, $label );
					$menu_item->setData( $menu_item_data );

					$root_menu->addItem( $menu_item );
				}

			}

		}

	}

	/**
	 * @param string $id
	 *
	 * @return Navigation_Menu|null
	 */
	public static function getMenu( $id )
	{
		if( !isset( static::$all_menus[$id] ) ) {
			return null;
		}

		return static::$all_menus[$id];
	}

	/**
	 * @param string $id
	 *
	 * @return Navigation_Menu_Item|null
	 */
	public static function getMenuItem( $id )
	{
		if( !isset( static::$all_menu_items[$id] ) ) {
			return null;
		}

		return static::$all_menu_items[$id];
	}

	/**
	 * @param bool $check_access
	 *
	 * @return Navigation_Menu[]
	 */
	public static function getRootMenus( $check_access=true )
	{
		$menus = [];

		foreach( static::$root_menus as $menu_id => $menu ) {

			if(
				$check_access &&
				!$menu->getAccessAllowed()
			) {
				continue;
			}

			$menus[] = $menu;
		}

		static::sortMenuItems($menus);

		return $menus;
	}


	/**
	 * @param Navigation_Menu[]|Navigation_Menu_Item[] $items
	 */
	public static function sortMenuItems( array &$items )
	{
		uasort(
			$items,
			function( $a, $b ) {
				/**
				 * @var Navigation_Menu|Navigation_Menu_Item $a
				 * @var Navigation_Menu|Navigation_Menu_Item $b
				 */
				return strcmp( $a->getLabel(), $b->getLabel() );
			}
		);

		uasort(
			$items,
			function( $a, $b ) {
				/**
				 * @var Navigation_Menu|Navigation_Menu_Item $a
				 * @var Navigation_Menu|Navigation_Menu_Item $b
				 */

				if( $a->getIndex()==$b->getIndex() ) {
					return 0;
				}

				return ( $a->getIndex()<$b->getIndex() ) ? -1 : 1;
			}
		);

	}


	/**
	 * menu constructor.
	 *
	 * @param string $id
	 * @param string $label
	 * @param int    $index
	 * @param string $icon
	 *
	 */
	public function __construct( $id, $label, $index, $icon = '' )
	{

		$this->id = $id;
		$this->label = $label;

		$this->index = $index;
		$this->icon = $icon;

	}


	/**
	 * @return Navigation_Menu|null
	 */
	public function getParentMenu()
	{
		return $this->parent_menu;
	}

	/**
	 * @param Navigation_Menu $parent_menu
	 */
	public function setParentMenu( Navigation_Menu $parent_menu )
	{
		$this->parent_menu = $parent_menu;
	}

	/**
	 * @param bool $absolute (optional)
	 *
	 * @return string
	 */
	public function getId( $absolute=true )
	{
		if($this->parent_menu && $absolute) {
			return $this->getParentMenu()->getId().'/'.$this->id;
		}
		return $this->id;
	}

	/**
	 * @param string $id
	 */
	public function setId( $id )
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @param string $label
	 */
	public function setLabel( $label )
	{
		$this->label = $label;
	}

	/**
	 * @return int
	 */
	public function getIndex()
	{
		return $this->index;
	}

	/**
	 * @param int $index
	 */
	public function setIndex( $index )
	{
		$this->index = $index;
	}

	/**
	 * @return string
	 */
	public function getIcon()
	{
		return $this->icon;
	}

	/**
	 * @param string $icon
	 */
	public function setIcon( $icon )
	{
		$this->icon = $icon;
	}


	/**
	 * @param Navigation_Menu_Item $item
	 *
	 * @throws Navigation_Menu_Exception
	 */
	public function addItem( Navigation_Menu_Item $item )
	{
		/**
		 * @var Navigation_Menu $this
		 */
		$item->setMenu( $this );

		$id = $item->getId();
		if(
			isset(static::$all_menu_items[$id]) ||
			isset(static::$all_menus[$id])
		) {
			throw new Navigation_Menu_Exception( 'Duplicate menu element: '.$id);
		}

		$this->items[$id] = $item;
		static::$all_menu_items[$id] = $item;
	}

	/**
	 * @param Navigation_Menu $menu
	 *
	 * @throws Navigation_Menu_Exception
	 */
	public function addMenu( Navigation_Menu $menu )
	{
		/**
		 * @var Navigation_Menu $this
		 */
		$menu->setParentMenu( $this );

		$id = $menu->getId();
		if(
			isset(static::$all_menu_items[$id]) ||
			isset(static::$all_menus[$id])
		) {
			throw new Navigation_Menu_Exception( 'Duplicate menu element: '.$id);
		}

		$this->items[$id] = $menu;
		static::$all_menus[$id] = $menu;
	}

	/**
	 * @return bool
	 */
	public function getAccessAllowed()
	{
		return (count($this->getItems())>0);
	}

	/**
	 * @param bool $check_access
	 *
	 * @return Navigation_Menu[]|Navigation_Menu_Item[]
	 */
	public function getItems( $check_access=true )
	{
		$items = [];

		foreach( $this->items as $i ) {
			if(
				$check_access &&
				!$i->getAccessAllowed()
			) {
				continue;
			}

			$items[] = $i;
		}

		static::sortMenuItems($items);

		return $items;
	}

	/**
	 * @param Navigation_Menu_Item[]|Navigation_Menu[] $items
	 */
	public function setItems( $items )
	{
		$this->items = [];

		foreach( $items as $item ) {
			if($item instanceof Navigation_Menu_Item) {
				$this->addItem( $item );
			}

			if($item instanceof Navigation_Menu) {
				$this->addMenu( $item );
			}
		}
	}

}