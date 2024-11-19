<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
	protected string $name = '';

	/**
	 * @var string
	 */
	protected string $config_file_path = '';

	/**
	 * @var string|null
	 */
	protected string|null $translator_dictionary = '';

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
	 * @param string $name
	 *
	 * @return bool
	 */
	public static function exists( string $name ) : bool
	{
		$file_path = SysConf_Path::getMenus() . $name . '.php';
		return IO_File::exists($file_path);
	}

	/**
	 * @param string $name
	 * @param string|null|bool $translator_dictionary
	 *
	 * @return Navigation_MenuSet
	 */
	public static function get( string $name, string|null|bool $translator_dictionary = null ): Navigation_MenuSet
	{
		if( !isset( static::$_sets[$name] ) ) {
			static::$_sets[$name] = new static( $name, $translator_dictionary );
		}

		return static::$_sets[$name];
	}

	/**
	 * @return Navigation_MenuSet[]
	 */
	public static function getList(): iterable
	{
		$files = IO_Dir::getList( SysConf_Path::getMenus(), '*.php', false );

		foreach( $files as $name ) {
			$name = pathinfo( $name )['filename'];
			static::get( $name );
		}

		return static::$_sets;
	}


	/**
	 * @param string $name
	 * @param string|null|bool $translator_dictionary
	 */
	public function __construct( string $name, string|null|bool $translator_dictionary = null )
	{
		$this->setName( $name );
		$this->translator_dictionary = $translator_dictionary;
		$this->init();
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ): void
	{
		$this->name = $name;
		$this->config_file_path = SysConf_Path::getMenus() . $name . '.php';
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $text
	 * @return string
	 */
	protected function _( string $text ): string
	{
		if( $this->translator_dictionary === false ) {
			return $text;
		}

		return Tr::_( $text, [], $this->translator_dictionary );
	}

	/**
	 *
	 */
	protected function init(): void
	{
		$menu_data = require $this->config_file_path;

		foreach( $menu_data as $id => $item_data ) {
			if( empty( $item_data['icon'] ) ) {
				$item_data['icon'] = '';
			}

			$root_menu = $this->addMenu(
				$id,
				$this->_( $item_data['label']??'' ),
				$item_data['icon']
			);

			if( isset( $item_data['items'] ) ) {
				foreach( $item_data['items'] as $menu_item_id => $menu_item_data ) {
					$label = $this->_( $menu_item_data['label']??'' );
					$menu_item = new Navigation_Menu_Item( $menu_item_id, $label );
					$menu_item->setData( $menu_item_data );

					$root_menu->addItem( $menu_item );
				}

			}

		}

		$this->initModulesMenuItems();
	}


	/**
	 *
	 */
	protected function initModulesMenuItems(): void
	{
		foreach( Application_Modules::activatedModulesList() as $manifest ) {
			$this->initModuleMenuItems(  $manifest);
		}
	}
	
	public function initModuleMenuItems( Application_Module_Manifest $module_manifest ) : void
	{
		$items_file_path = $module_manifest->getModuleDir().SysConf_Jet_Modules::getMenuItemsDir().'/'.$this->name.'.php';
		if(!IO_File::isReadable($items_file_path)) {
			return;
		}
		
		
		$translator_dictionary = $module_manifest->getName();
		
		Translator::setCurrentDictionaryTemporary( $translator_dictionary, function() use ($items_file_path, $module_manifest) {
			$menu_data = require $items_file_path;
			
			foreach($menu_data as $menu_id=>$menu_items_data) {
				$menu = $this->getMenu( $menu_id );
				if( !$menu ) {
					continue;
				}
				
				foreach( $menu_items_data as $item_id => $menu_item_data ) {
					$label = '';
					
					if( !empty( $menu_item_data['label'] ) ) {
						$label = Tr::_( $menu_item_data['label'] );
					}
					
					$menu_item = new Navigation_Menu_Item( $item_id, $label );
					$menu_item->setMenuId( $menu_id );
					$menu_item->setData( $menu_item_data );
					$menu_item->setSourceModuleName( $module_manifest->getName() );
					
					$menu->addItem( $menu_item );
				}
			}
			
		} );
		
	}

	/**
	 * @param string $id
	 *
	 * @param string $label
	 * @param string $icon
	 * @param int|null $index
	 *
	 * @return Navigation_Menu
	 * @throws Navigation_Menu_Exception
	 *
	 */
	public function addMenu( string $id, string $label, string $icon = '', int|null $index = null ): Navigation_Menu
	{
		if( isset( $this->menus[$id] ) ) {
			throw new Navigation_Menu_Exception( 'Menu ID conflict: ' . $id . ' Menu set:' . $this->name );
		}

		if( $index === null ) {
			$index = count( $this->menus ) + 1;
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
	public function getMenu( string $id ): Navigation_Menu|null
	{
		if( !isset( $this->menus[$id] ) ) {
			return null;
		}

		return $this->menus[$id];
	}

	/**
	 * @return Navigation_Menu[]
	 */
	public function getMenus(): array
	{
		return $this->menus;
	}


	/**
	 *
	 */
	public function saveDataFile(): void
	{
		$res = [];
		
		$per_module = [];

		foreach( $this->menus as $menu ) {
			$menu_id = $menu->getId();

			$res[$menu_id] = $menu->toArray();
			
			foreach($menu->getItems() as $item) {
				if(!$item->getSourceModuleName()) {
					continue;
				}
				
				$module = $item->getSourceModuleName();
				
				if(!isset($per_module[$module])) {
					$per_module[$module] = [];
				}
				
				if(!isset($per_module[$module][$menu_id])) {
					$per_module[$module][$menu_id] = [];
				}
				
				$per_module[$module][$menu_id][$item->getId(false)] = $item->toArray();
			}
		}

		IO_File::writeDataAsPhp( $this->config_file_path, $res );
		
		foreach($per_module as $module_name=>$menus) {
			$module_manifest = Application_Modules::moduleManifest( $module_name );
			
			$items_file_path = $module_manifest->getModuleDir().SysConf_Jet_Modules::getMenuItemsDir().'/'.$this->name.'.php';
			
			IO_File::writeDataAsPhp( $items_file_path, $menus );
		}
	}

}