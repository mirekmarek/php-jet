<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\BaseObject;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Select;
use Jet\IO_Dir;
use Jet\IO_File;
use Jet\SysConf_Jet_Modules;


/**
 *
 */
class Modules_MenuItems extends BaseObject
{
	protected Modules_Manifest $module_manifest;

	protected ?Form $menu_item_create_form = null;

	/**
	 * @var Menus_Menu_Item[][][]
	 */
	protected array $menu_items = [];


	public function __construct( Modules_Manifest $module_manifest )
	{
		$this->module_manifest = $module_manifest;
		$this->read();
	}


	/**
	 */
	protected function read(): void
	{
		$dir = $this->module_manifest->getModuleDir().SysConf_Jet_Modules::getMenuItemsDir().'/';
		if(!IO_Dir::isReadable($dir)) {
			return;
		}

		foreach( Menus::getSets() as $set_id => $set ) {
			$path = $dir.$set_id.'.php';

			if(!IO_File::isReadable($path)) {
				continue;
			}

			$menus = require $path;
			foreach( $menus as $menu_id => $items ) {
				foreach( $items as $item_id => $item_data ) {
					$item = new Menus_Menu_Item( $item_id, $item_data['label'] ?? '' );
					$item->setData( $item_data );
					$item->setSetId( $set_id );
					$item->setMenuId( $menu_id );

					$this->menu_items[$set_id][$menu_id][$item_id] = $item;
				}
			}
		}
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
					$data[$menu_id][$item->getId()] = $item->toArray();
				}
			}

			IO_File::writeDataAsPhp( $file, $data );
		}
	}


	/**
	 *
	 * @param string $menu_set_name
	 * @param ?string $translator_dictionary
	 *
	 * @return Menus_Menu_Item[]
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


	/**
	 * @return Form
	 */
	public function getCreateMenuItemForm(): Form
	{
		if( !$this->menu_item_create_form ) {

			$form = Menus_Menu_Item::getCreateForm();
			$form->setCustomTranslatorDictionary( 'menus' );

			$target_menus = ['' => ''];
			foreach( Menus::getSets() as $set ) {
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

			$form->setAction( Modules::getActionUrl( 'menu_item/add' ) );

			$this->menu_item_create_form = $form;
		}

		return $this->menu_item_create_form;
	}

	/**
	 * @return bool|Menus_Menu_Item
	 */
	public function catchCreateMenuItemForm(): bool|Menus_Menu_Item
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

		$menu_item = new Menus_Menu_Item(
			$form->field( 'id' )->getValue(),
			$form->field( 'label' )->getValue()
		);

		$menu_item->setSetId( $set_name );
		$menu_item->setMenuId( $menu_id );

		$menu_item->setIndex( $form->field( 'index' )->getValue() );
		$menu_item->setIcon( $form->field( 'icon' )->getValue() );

		$menu_item->setSeparatorBefore( $form->field( 'separator_before' )->getValue() );
		$menu_item->setSeparatorAfter( $form->field( 'separator_after' )->getValue() );


		$menu_item->setURL( $form->field( 'URL' )->getValue() );

		$menu_item->setPageId( $form->field( 'page_id' )->getValue() );
		$menu_item->setBaseId( $form->field( 'base_id' )->getValue() );
		$menu_item->setLocale( $form->field( 'locale' )->getValue() );

		$menu_item->setUrlParts( Menus_Menu_Item::catchURLParts( $form ) );
		$menu_item->setGetParams( Menus_Menu_Item::catchGETParams( $form ) );

		return $menu_item;
	}

	/**
	 *
	 * @param Menus_Menu_Item $menu_item
	 */
	public function addMenuItem( Menus_Menu_Item $menu_item ): void
	{
		$menu_set = $menu_item->getSetId();
		$menu_id = $menu_item->getMenuId();
		$item_id = $menu_item->getId();

		if( !isset( $this->menu_items[$menu_set] ) ) {
			$this->menu_items[$menu_set] = [];
		}

		if( !isset( $this->menu_items[$menu_set][$menu_id] ) ) {
			$this->menu_items[$menu_set][$menu_id] = [];
		}

		$this->menu_items[$menu_set][$menu_id][$item_id] = $menu_item;

		$this->menu_item_create_form = null;
	}

	/**
	 * @param string $menu_set
	 * @param string $menu_id
	 *
	 * @return Menus_Menu_Item[]
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

	/**
	 * @param string $menu_set
	 * @param string $menu_id
	 * @param string $item_id
	 *
	 * @return Menus_Menu_Item|null
	 */
	public function getMenuItem( string $menu_set, string $menu_id, string $item_id ): Menus_Menu_Item|null
	{
		if( !isset( $this->menu_items[$menu_set][$menu_id][$item_id] ) ) {
			return null;
		}

		return $this->menu_items[$menu_set][$menu_id][$item_id];
	}

	/**
	 *
	 * @param string $menu_set
	 * @param string $menu_id
	 * @param string $item_id
	 *
	 * @return Menus_Menu_Item|null
	 */
	public function deleteMenuItem( string $menu_set, string $menu_id, string $item_id ): Menus_Menu_Item|null
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
}