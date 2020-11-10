<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
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
class Menus_MenuNamespace_Menu extends Navigation_Menu
{


	/**
	 * @var Form
	 */
	protected static $create_form;

	/**
	 * @var Form
	 */
	protected $__edit_form;

	/**
	 * @var string
	 */
	protected $namespace_name = '';

	/**
	 * @var Menus_MenuNamespace_Menu_Item[]
	 */
	protected $items = [];

	/**
	 * @return string
	 */
	public function getNamespaceName()
	{
		return $this->namespace_name;
	}

	/**
	 * @param string $namespace_name
	 */
	public function setNamespaceName( $namespace_name )
	{
		$this->namespace_name = $namespace_name;
	}


	/**
	 * @return Form
	 */
	public static function getCreateForm()
	{
		if(!static::$create_form) {

			$label = new Form_Field_Input('label', 'Menu label:', '' );
			$label->setIsRequired(true);
			$label->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter menu label',
			]);
			

			$id = new Form_Field_Input('id', 'Menu identifier:', '' );
			$id->setIsRequired(true);
			$id->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter menu identifier',
			]);
			$id->setValidator( function( Form_Field $field ) {
				if(!$field->getValue()) {
					$field->setError(Form_Field::ERROR_CODE_EMPTY);
					return false;
				}

				$id = Project::generateIdentifier( $field->getValue(), function( $id ) {
					return Menus::menuExists( $id );
				} );
				$field->setValue( $id );

				return true;
			} );

			$icon = new Form_Field_Input('icon', 'Icon:', '' );
			$index = new Form_Field_Int('index', 'Index:', 0 );


			$fields = [
				$label,
				$id,
				$icon,
				$index
			];

			$form = new Form('create_menu_form', $fields );


			$form->setAction( Menus::getActionUrl('menu/add') );

			static::$create_form = $form;
		}

		return static::$create_form;
	}

	/**
	 * @return bool|Menus_MenuNamespace_Menu
	 */
	public static function catchCreateForm()
	{
		$form = static::getCreateForm();
		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$menu_item = new Menus_MenuNamespace_Menu(
			$form->field('id')->getValue(),
			$form->field('label')->getValue(),
			$form->field('index')->getValue(),
			$form->field('icon')->getValue()
		);


		return $menu_item;
	}


	/**
	 *
	 * @return Form
	 *
	 */
	public function getEditForm()
	{
		if(!$this->__edit_form) {
			$id = new Form_Field_Input('id', 'Menu identifier:', $this->getId() );
			$id->setIsReadonly(true);

			$label = new Form_Field_Input('label', 'Menu label:', $this->getLabel() );
			$label->setIsRequired(true);
			$label->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter menu label',
			]);
			$label->setCatcher( function( $value ) {
				$this->setLabel( $value );
			} );


			$icon = new Form_Field_Input('icon', 'Icon:', $this->getIcon() );
			$icon->setCatcher( function( $value ) {
				$this->setIcon( $value );
			} );

			$index = new Form_Field_Int('index', 'Index:', $this->getIndex() );
			$index->setCatcher( function( $value ) {
				$this->setIndex( $value );
			} );


			$form = new Form('menu_edit_form', [
				$id,
				$label,
				$icon,
				$index
			]);
			$form->setAction( Menus::getActionUrl('menu/edit') );
			$this->__edit_form = $form;
		}

		return $this->__edit_form;
	}


	/**
	 * @return bool
	 */
	public function catchEditForm()
	{
		$form = $this->getEditForm();

		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$form->catchData();


		$items_sort = Http_Request::POST()->getRaw('items_sort', []);
		$i = 0;
		foreach( $items_sort as $item_id ) {
			$i++;
			$this->getItem( $item_id )->setIndex( $i );
		}

		$this->sortItems();

		return true;
	}




	/**
	 * @return Navigation_Menu_Item[]
	 */
	public function getMenuItems()
	{
		return $this->items;
	}



	/**
	 * @param string $id
	 *
	 * @return Navigation_Menu_Item|null
	 */
	public function getItem( $id )
	{
		if( !isset($this->items[$id]) ) {
			return null;
		}

		return $this->items[$id];
	}

	/**
	 * @param string $id
	 *
	 * @return Navigation_Menu_Item|null
	 */
	public function deleteMenuItem( $id )
	{
		if( !isset($this->items[$id]) ) {
			return null;
		}

		$menu = $this->items[$id];

		unset( $this->items[$id] );

		$this->sortItems();

		return $menu;
	}

	/**
	 *
	 */
	public function sortItems()
	{
		uasort( $this->items, function( Menus_MenuNamespace_Menu_Item $a, Menus_MenuNamespace_Menu_Item $b ) {
			if($a->getIndex()==$b->getIndex()) {
				return 0;
			}

			if($a->getIndex()>$b->getIndex()) {
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
	public function addMenuItem( Navigation_Menu_Item $item )
	{
		$this->items[$item->getId()] = $item;

		$this->sortItems();
	}

	/**
	 * @param string $menu_id
	 * @param array $data
	 *
	 * @return Menus_MenuNamespace_Menu
	 */
	public static function fromArray( $menu_id, array $data )
	{
		$label = $data['label'];
		$index = isset($data['index']) ? $data['index'] : 0;

		$menu = new Menus_MenuNamespace_Menu( $menu_id, $label, $index );

		if( isset($data['icon']) ) {
			$menu->setIcon( $data['icon'] );
		}

		if( isset($data['items']) ) {
			foreach( $data['items'] as $item_id=>$item_data ) {
				$menu->items[$item_id] = Menus_MenuNamespace_Menu_Item::fromArray( $item_id, $item_data );
			}
		}

		return $menu;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{

		$menu = [
			'label' => $this->getLabel(),
			'icon' => $this->getIcon(),
			'index' => $this->getIndex()
		];

		if($this->getItems()) {
			$menu['items'] = [];

			foreach($this->getItems() as $item) {
				/**
				 * @var Menus_MenuNamespace_Menu_Item $item
				 */
				$item_id = $item->getId();

				$menu_item = $item->toArray();


				$menu['items'][$item_id] = $menu_item;

			}
		}

		return $menu;

	}

}