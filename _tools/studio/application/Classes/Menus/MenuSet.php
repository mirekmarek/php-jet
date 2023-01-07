<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Exception;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Input;
use Jet\Navigation_Menu;
use Jet\Http_Request;
use Jet\Navigation_Menu_Exception;
use Jet\Navigation_MenuSet;

/**
 *
 */
class Menus_MenuSet extends Navigation_MenuSet
{

	/**
	 * @var ?Form
	 */
	protected static ?Form $create_form = null;

	/**
	 * @var ?Form
	 */
	protected ?Form $__edit_form = null;

	/**
	 * @param string $name
	 */
	public function __construct( string $name = '' )
	{
		$this->translator_dictionary = false;

		if( $name ) {
			$this->setName( $name );
			$this->init();
		}
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
				$item_data['label']??'',
				$item_data['icon']
			);

			if( isset( $item_data['items'] ) ) {
				foreach( $item_data['items'] as $menu_item_id => $menu_item_data ) {
					$label = $menu_item_data['label']??'';
					$menu_item = new Menus_Menu_Item( $menu_item_id, $label );
					$menu_item->setData( $menu_item_data );

					$root_menu->addItem( $menu_item );
				}

			}

		}
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

		$menu = new Menus_Menu( $id, $label, $index, $icon );

		$this->menus[$id] = $menu;

		return $menu;
	}


	/**
	 * @return Form
	 */
	public static function getCreateForm(): Form
	{
		if( !static::$create_form ) {

			$menu_set_name = new Form_Field_Input( 'menu_set_name', 'Menu set name:' );
			$menu_set_name->setIsRequired( true );
			$menu_set_name->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY => 'Please enter menu set name',
			] );

			$fields = [
				$menu_set_name
			];

			$form = new Form( 'create_menu_set_form', $fields );


			$form->setAction( Menus::getActionUrl( 'set/add' ) );

			static::$create_form = $form;
		}

		return static::$create_form;
	}


	/**
	 * @return bool|Menus_MenuSet
	 */
	public static function catchCreateForm(): bool|Menus_MenuSet
	{
		$form = static::getCreateForm();
		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}


		$set = new Menus_MenuSet();
		$set->setName( $form->field( 'menu_set_name' )->getValue() );


		return $set;
	}

	/**
	 *
	 * @return Form
	 *
	 */
	public function getEditForm(): Form
	{
		if( !$this->__edit_form ) {

			$menu_set_name = new Form_Field_Input( 'menu_set_name', 'Menu set name:' );
			$menu_set_name->setDefaultValue( $this->getName() );
			$menu_set_name->setIsReadonly( true );
			$menu_set_name->setFieldValueCatcher( function( $name ) {
			} );

			$form = new Form( 'menu_set_edit_form', [$menu_set_name] );
			$form->setAction( Menus::getActionUrl( 'set/edit' ) );
			$this->__edit_form = $form;
		}

		return $this->__edit_form;
	}

	/**
	 * @return bool
	 */
	public function catchEditForm(): bool
	{
		$form = $this->getEditForm();

		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$form->catchFieldValues();

		$items_sort = Http_Request::POST()->getRaw( 'items_sort', [] );
		$i = 0;
		foreach( $items_sort as $item_id ) {
			$i++;
			$this->getMenu( $item_id )->setIndex( $i );
		}

		$this->sortMenus();


		return true;
	}

	/**
	 * @param string $name
	 * @param string|null|bool $translator_dictionary
	 *
	 * @return Menus_MenuSet
	 */
	public static function get( string $name, string|null|bool $translator_dictionary = null ): Menus_MenuSet
	{
		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return parent::get( name: $name, translator_dictionary: false );
	}

	/**
	 * @return Menus_MenuSet[]
	 */
	public static function getList(): array
	{
		return parent::getList();
	}

	/**
	 * @return Navigation_Menu[]
	 */
	public function getMenus(): array
	{
		return parent::getMenus();
	}


	/**
	 * @param string $id
	 *
	 * @return Navigation_Menu|null
	 */
	public function getMenu( string $id ): Navigation_Menu|null
	{
		return parent::getMenu( $id );
	}

	/**
	 *
	 */
	public function sortMenus(): void
	{
		uasort( $this->menus, function( Menus_Menu $a, Menus_Menu $b ) {
			if( $a->getIndex() == $b->getIndex() ) {
				return 0;
			}

			if( $a->getIndex() > $b->getIndex() ) {
				return 1;
			}

			return -1;

		} );

		$i = 0;
		foreach( $this->menus as $menu ) {
			$i++;
			$menu->setIndex( $i );
		}

	}


	/**
	 * @param Menus_Menu $menu
	 */
	public function appendMenu( Menus_Menu $menu ): void
	{
		$this->menus[$menu->getId()] = $menu;

		$this->sortMenus();
	}

	/**
	 * @param string $menu_id
	 *
	 * @return Menus_Menu|null
	 */
	public function deleteMenu( string $menu_id ): Menus_Menu|null
	{
		if( !isset( $this->menus[$menu_id] ) ) {
			return null;
		}
		$old_menu = $this->menus[$menu_id];
		unset( $this->menus[$menu_id] );
		$this->sortMenus();

		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return $old_menu;
	}

	/**
	 * @return bool
	 */
	public function save(): bool
	{

		$ok = true;
		try {
			$this->saveDataFile();
		} catch( Exception $e ) {
			$ok = false;
			Application::handleError( $e );
		}

		return $ok;
	}

}