<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\ApplicationModules;

use Jet\BaseObject;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Select;
use Jet\IO_File;
use Jet\Navigation_Menu_Item;
use Jet\Navigation_MenuSet;
use Jet\SysConf_Jet_Modules;
use JetStudioModule\Menus\Menu_Item;


/**
 *
 */
class Modules_MenuItems extends BaseObject
{
	protected Modules_Manifest $module_manifest;
	protected ?Form $__menu_item_create_form = null;

	/**
	 * @var Navigation_Menu_Item[][][]
	 */
	protected array $menu_items = [];


	public function __construct( Modules_Manifest $module_manifest )
	{
		$this->module_manifest = $module_manifest;
		
		foreach( Navigation_MenuSet::getList() as $set_id=>$menu_set ) {
			if(!$this->module_manifest->isActivated()) {
				$menu_set->initModuleMenuItems( $this->module_manifest );
			}
			
			foreach( $menu_set->getMenus() as $menu ) {
				foreach($menu->getItems( false ) as $item) {
					if($item->getSourceModuleName()==$this->module_manifest->getName()) {
						$this->menu_items[$set_id][$menu->getId()][$item->getId(false)] = $item;
					}
				}
			}
		}
		
		
	}


	/**
	 *
	 * @param string $menu_set_name
	 * @param ?string $translator_dictionary
	 *
	 * @return Navigation_Menu_Item[]
	 */
	public function getMenuItems( string $menu_set_name, ?string $translator_dictionary = null ): array
	{
		if( !isset( $this->menu_items[$menu_set_name] ) ) {
			return [];
		}

		$res = [];
		foreach( $this->menu_items[$menu_set_name] as $menu_id => $menu_items_data ) {
			foreach( $menu_items_data as $item_id => $menu_item ) {

				$res[] = $menu_item;
			}
		}

		return $res;
	}
	
	public function getCreateMenuItemForm(): Form
	{
		if( !$this->__menu_item_create_form ) {

			$form = Menu_Item::getCreateForm();
			$form->setCustomTranslatorDictionary( 'menus' );

			$target_menus = ['' => ''];
			foreach( Navigation_MenuSet::getList() as $set ) {
				foreach( $set->getMenus() as $menu ) {

					$key = $set->getName() . ':' . $menu->getId();

					$target_menus[$key] = $set->getName() . ' / ' . $menu->getLabel() . ' (' . $menu->getId() . ')';
				}
			}


			$target_menu = new Form_Field_Select( 'target_menu', 'Menu' );
			$target_menu->setIsRequired( true );
			$target_menu->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY         => 'Please select target menu',
				Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select target menu'
			] );
			$target_menu->setSelectOptions( $target_menus );


			$form->addField( $target_menu );

			$form->setAction( Main::getActionUrl( 'menu_item_add' ) );

			$this->__menu_item_create_form = $form;
		}

		return $this->__menu_item_create_form;
	}

	public function catchCreateMenuItemForm( ?string &$set_name, ?string &$menu_id, ?string &$item_id ): bool|Navigation_Menu_Item
	{
		$form = $this->getCreateMenuItemForm();
		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$target_menu = $form->field( 'target_menu' )->getValue();

		[
			$set_name,
			$menu_id
		] = explode( ':', $target_menu );

		$menu_item = new Navigation_Menu_Item(
			$form->field( 'id' )->getValue(),
			$form->field( 'label' )->getValue()
		);
		
		$item_id = $menu_item->getId(false);

		$menu_item->setMenuId( $menu_id );

		$menu_item->setIndex( $form->field( 'index' )->getValue() );
		$menu_item->setIcon( $form->field( 'icon' )->getValue() );

		$menu_item->setSeparatorBefore( $form->field( 'separator_before' )->getValue() );
		$menu_item->setSeparatorAfter( $form->field( 'separator_after' )->getValue() );


		$menu_item->setURL( $form->field( 'URL' )->getValue() );

		$menu_item->setPageId( $form->field( 'page_id' )->getValue() );
		$menu_item->setBaseId( $form->field( 'base_id' )->getValue() );
		$menu_item->setLocale( $form->field( 'locale' )->getValue() );

		$menu_item->setUrlParts( Menu_Item::catchURLParts( $form ) );
		$menu_item->setGetParams( Menu_Item::catchGETParams( $form ) );

		$this->addMenuItem( $set_name, $menu_item );
		
		return $menu_item;
	}

	public function addMenuItem( string $menu_set, Navigation_Menu_Item $menu_item ): void
	{
		$menu_id = $menu_item->getMenuId();
		$item_id = $menu_item->getId();

		if( !isset( $this->menu_items[$menu_set] ) ) {
			$this->menu_items[$menu_set] = [];
		}

		if( !isset( $this->menu_items[$menu_set][$menu_id] ) ) {
			$this->menu_items[$menu_set][$menu_id] = [];
		}

		$this->menu_items[$menu_set][$menu_id][$item_id] = $menu_item;

		$this->__menu_item_create_form = null;
	}

	/**
	 * @param string $menu_set
	 * @param string $menu_id
	 *
	 * @return Navigation_Menu_Item[]
	 */
	public function getList( string $menu_set = '', string $menu_id = '' ): array
	{
		if( !$menu_set ) {
			return $this->menu_items;
		}

		if( !isset( $this->menu_items[$menu_set] ) ) {
			return [];
		}

		if( !$menu_id ) {
			return $this->menu_items[$menu_set];
		}

		if( !isset( $this->menu_items[$menu_set][$menu_id] ) ) {
			return [];
		}


		return $this->menu_items[$menu_set][$menu_id];
	}
	
	public function getMenuItem( string $menu_set, string $menu_id, string $item_id ): Navigation_Menu_Item|null
	{
		if( !isset( $this->menu_items[$menu_set][$menu_id][$item_id] ) ) {
			return null;
		}

		return $this->menu_items[$menu_set][$menu_id][$item_id];
	}
	
	public function deleteMenuItem( string $menu_set, string $menu_id, string $item_id ): Navigation_Menu_Item|null
	{
		if(
			!isset( $this->menu_items[$menu_set] ) ||
			!isset( $this->menu_items[$menu_set][$menu_id] ) ||
			!isset( $this->menu_items[$menu_set][$menu_id][$item_id] )
		) {
			return null;
		}

		$old_item = $this->menu_items[$menu_set][$menu_id][$item_id];

		unset( $this->menu_items[$menu_set][$menu_id][$item_id] );

		if( !count( $this->menu_items[$menu_set][$menu_id] ) ) {
			unset( $this->menu_items[$menu_set][$menu_id] );
		}

		if( !count( $this->menu_items[$menu_set] ) ) {
			unset( $this->menu_items[$menu_set] );
		}

		return $old_item;
	}
	
	public function save() : void
	{
		$dir = $this->module_manifest->getModuleDir().SysConf_Jet_Modules::getMenuItemsDir().'/';
		
		foreach($this->menu_items as $menu_set=>$menus) {
			$file = $dir.$menu_set.'.php';
			$data = [];
			
			foreach($menus as $menu_id=>$items) {
				$data[$menu_id] = [];
				
				foreach($items as $item) {
					$data[$menu_id][$item->getId(false)] = $item->toArray();
				}
			}
			
			IO_File::writeDataAsPhp( $file, $data );
		}
		
	}
}