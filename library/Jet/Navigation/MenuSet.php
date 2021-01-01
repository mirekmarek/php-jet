<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	 * @var string|null
	 */
	protected static string|null $menus_dir_path = null;

	/**
	 * @var string
	 */
	protected string $name = '';

	/**
	 * @var string
	 */
	protected string $config_file_path = '';

	/**
	 * @var string|null
	 */
	protected string|null $translator_namespace = '';

	/**
	 * @var Navigation_Menu[]
	 */
	protected array $menus = [];

	/**
	 * @var Navigation_Menu_Item[]
	 */
	protected array $all_menu_items;


	/**
	 * @var Navigation_MenuSet[]
	 */
	protected static array $_sets = [];


	/**
	 * @return string
	 */
	public static function getMenusDirPath() : string
	{
		if(!self::$menus_dir_path) {
			self::$menus_dir_path = SysConf_PATH::MENUS();
		}

		return self::$menus_dir_path;
	}

	/**
	 * @param string $menus_path
	 */
	public static function setMenusDirPath( string $menus_path ) : void
	{
		self::$menus_dir_path = $menus_path;
	}

	/**
	 * @param string $name
	 * @param string|null|bool $translator_namespace
	 *
	 * @return Navigation_MenuSet
	 */
	public static function get(string $name, string|null|bool $translator_namespace=null) : Navigation_MenuSet
	{
		if(!isset(static::$_sets[$name])) {
			static::$_sets[$name] = new static($name, $translator_namespace);
		}

		return static::$_sets[$name];
	}

	/**
	 * @return Navigation_MenuSet[]
	 */
	public static function getList() : iterable
	{
		$files = IO_Dir::getList(static::getMenusDirPath(), '*.php', false );

		foreach($files as $path=>$name) {
			$name = pathinfo($name)['filename'];
			static::get($name);
		}

		return static::$_sets;
	}


	/**
	 * @param string $name
	 * @param string|null|bool $translator_namespace
	 */
	public function __construct( string $name, string|null|bool $translator_namespace=null )
	{
		$this->setName($name);
		$this->translator_namespace = $translator_namespace;
		$this->init();
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ) : void
	{
		$this->name = $name;
		$this->config_file_path = static::getMenusDirPath().$name.'.php';
	}

	/**
	 * @return string
	 */
	public function getName() : string
	{
		return $this->name;
	}

	/**
	 * @param string $text
	 * @return string
	 */
	protected function _( string $text ) : string
	{
		if($this->translator_namespace===false) {
			return $text;
		}

		return Tr::_($text, [], $this->translator_namespace);
	}

	/**
	 *
	 */
	protected function init() : void
	{
		$menu_data = require $this->config_file_path;

		foreach( $menu_data as $id=>$item_data ) {
			if(empty($item_data['icon'])) {
				$item_data['icon'] = '';
			}

			$root_menu = $this->addMenu(
				$id,
				$this->_($item_data['label']),
				$item_data['icon']
			);

			if( isset($item_data['items']) ) {
				foreach( $item_data['items'] as $menu_item_id=>$menu_item_data ) {
					$label = $this->_($menu_item_data['label']);
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
	protected function initModuleMenuItems() : void
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
	public function addMenu( string $id, string $label, string $icon = '', int|null $index = null  ) : Navigation_Menu
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
	public function getMenu( string $id ) : Navigation_Menu|null
	{
		if( !isset( $this->menus[$id] ) ) {
			return null;
		}

		return $this->menus[$id];
	}

	/**
	 * @return Navigation_Menu[]
	 */
	public function getMenus() : array
	{
		return $this->menus;
	}




}