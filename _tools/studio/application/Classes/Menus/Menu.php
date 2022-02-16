<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\Form_Field;
use Jet\Form_Field_Int;
use Jet\Http_Request;
use Jet\Navigation_Menu;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Navigation_Menu_Item;

/**
 *
 */
class Menus_Menu extends Navigation_Menu
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
	 * @var Menus_Menu_Item[]
	 */
	protected array $items = [];


	/**
	 * @return Form
	 */
	public static function getCreateForm(): Form
	{
		if( !static::$create_form ) {

			$label = new Form_Field_Input( 'label', 'Menu label:' );
			$label->setIsRequired( true );
			$label->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY => 'Please enter menu label',
			] );


			$id = new Form_Field_Input( 'id', 'Menu identifier:' );
			$id->setIsRequired( true );
			$id->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY => 'Please enter menu identifier',
			] );
			$id->setValidator( function( Form_Field $field ) {
				if( !$field->getValue() ) {
					$field->setError( Form_Field::ERROR_CODE_EMPTY );
					return false;
				}

				$id = Project::generateIdentifier( $field->getValue(), function( $id ) {
					return Menus::menuExists( $id );
				} );
				$field->setValue( $id );

				return true;
			} );

			$icon = new Form_Field_Input( 'icon', 'Icon:' );
			$index = new Form_Field_Int( 'index', 'Index:' );
			$index->setDefaultValue( 0 );


			$fields = [
				$label,
				$id,
				$icon,
				$index
			];

			$form = new Form( 'create_menu_form', $fields );


			$form->setAction( Menus::getActionUrl( 'menu/add' ) );

			static::$create_form = $form;
		}

		return static::$create_form;
	}

	/**
	 * @return bool|Menus_Menu
	 */
	public static function catchCreateForm(): bool|Menus_Menu
	{
		$form = static::getCreateForm();
		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		return new Menus_Menu(
			$form->field( 'id' )->getValue(),
			$form->field( 'label' )->getValue(),
			$form->field( 'index' )->getValue(),
			$form->field( 'icon' )->getValue()
		);
	}


	/**
	 *
	 * @return Form
	 *
	 */
	public function getEditForm(): Form
	{
		if( !$this->__edit_form ) {
			$id = new Form_Field_Input( 'id', 'Menu identifier:' );
			$id->setDefaultValue( $this->getId() );
			$id->setIsReadonly( true );

			$label = new Form_Field_Input( 'label', 'Menu label:' );
			$label->setDefaultValue( $this->getLabel() );
			$label->setIsRequired( true );
			$label->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY => 'Please enter menu label',
			] );
			$label->setFieldValueCatcher( function( $value ) {
				$this->setLabel( $value );
			} );


			$icon = new Form_Field_Input( 'icon', 'Icon:' );
			$icon->setDefaultValue( $this->getIcon() );
			$icon->setFieldValueCatcher( function( $value ) {
				$this->setIcon( $value );
			} );

			$index = new Form_Field_Int( 'index', 'Index:' );
			$index->setDefaultValue( $this->getIndex() );
			$index->setFieldValueCatcher( function( $value ) {
				$this->setIndex( $value );
			} );


			$form = new Form( 'menu_edit_form', [
				$id,
				$label,
				$icon,
				$index
			] );
			$form->setAction( Menus::getActionUrl( 'menu/edit' ) );
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
			$this->getItem( $item_id )->setIndex( $i );
		}

		//$this->sortItems();

		return true;
	}


	/**
	 * @return Navigation_Menu_Item[]
	 */
	public function getMenuItems(): array
	{
		return $this->items;
	}


	/**
	 * @param string $id
	 *
	 * @return Menus_Menu_Item|null
	 */
	public function getItem( string $id ): Menus_Menu_Item|null
	{
		if( !isset( $this->items[$id] ) ) {
			return null;
		}

		return $this->items[$id];
	}

	/**
	 * @param string $id
	 */
	public function deleteMenuItem( string $id ): void
	{
		if( !isset( $this->items[$id] ) ) {
			return;
		}

		unset( $this->items[$id] );

		//$this->sortItems();
	}

	/**
	 *
	 */
	public function sortItems(): void
	{

		uasort( $this->items, function( Menus_Menu_Item $a, Menus_Menu_Item $b ) {
			if( $a->getIndex() == $b->getIndex() ) {
				return 0;
			}

			if( $a->getIndex() > $b->getIndex() ) {
				return 1;
			}

			return -1;

		} );

		$i = 0;
		foreach( $this->items as $menu ) {
			$i++;
			$menu->setIndex( $i );
		}

	}


	/**
	 * @param Navigation_Menu_Item $item
	 *
	 */
	public function addMenuItem( Navigation_Menu_Item $item ): void
	{
		$this->items[$item->getId()] = $item;

		$this->sortItems();
	}


	/**
	 * @param bool $check_access
	 *
	 * @return Menus_Menu_Item[]
	 */
	public function getItems( bool $check_access = true ): array
	{
		return $this->items;
	}

}