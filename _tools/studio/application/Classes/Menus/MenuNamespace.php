<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\BaseObject;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Navigation_Menu;
use Jet\Http_Request;

/**
 *
 */
class Menus_MenuNamespace extends BaseObject
{

	/**
	 * @var string
	 */
	protected $name = '';

	/**

	/**
	 * @var Navigation_Menu[]
	 */
	protected $menus = [];


	/**
	 * @var Form
	 */
	protected static $create_form;

	/**
	 * @var Form
	 */
	protected $__edit_form;

	/**
	 *
	 */
	public function __construct()
	{
	}



	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( $name )
	{
		$this->name = $name;
	}




	/**
	 * @return Form
	 */
	public static function getCreateForm()
	{
		if(!static::$create_form) {

			$menu_namespace_name = new Form_Field_Input('menu_namespace_name', 'Menu namespace:', '' );
			$menu_namespace_name->setIsRequired(true);
			$menu_namespace_name->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter menu namespace name',
			]);

			$fields = [
				$menu_namespace_name
			];

			$form = new Form('create_menu_namespace_form', $fields );


			$form->setAction( Menus::getActionUrl('namespace/add') );

			static::$create_form = $form;
		}

		return static::$create_form;
	}


	/**
	 * @return bool|Menus_MenuNamespace
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


		$new_menu_namespace =  new Menus_MenuNamespace();
		$new_menu_namespace->setName( $form->field('menu_namespace_name')->getValue() );


		return $new_menu_namespace;
	}

	/**
	 *
	 * @return Form
	 *
	 */
	public function getEditForm()
	{
		if(!$this->__edit_form) {
			$menu_namespace_name = new Form_Field_Input('menu_namespace_name', 'Menu namespace:', $this->getName() );
			$menu_namespace_name->setIsRequired(true);
			$menu_namespace_name->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter menu namespace name',
			]);
			$menu_namespace_name->setCatcher( function( $name ) {
				$this->setName( $name );
			} );

			$form = new Form('menu_namespace_edit_form', [
				$menu_namespace_name
			]);
			$form->setAction( Menus::getActionUrl('namespace/edit') );
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
			$this->getMenu( $item_id )->setIndex( $i );
		}

		$this->sortMenus();


		return true;
	}

	/**
	 * @return Navigation_Menu[]
	 */
	public function getMenus()
	{
		return $this->menus;
	}



	/**
	 * @param string $id
	 *
	 * @return Navigation_Menu|null
	 */
	public function getMenu( $id )
	{
		if( !isset($this->menus[$id]) ) {
			return null;
		}

		return $this->menus[$id];
	}

	/**
	 * @param string $id
	 *
	 * @return Navigation_Menu|null
	 */
	public function deleteMenu( $id )
	{
		if( !isset($this->menus[$id]) ) {
			return null;
		}

		$menu = $this->menus[$id];

		unset( $this->menus[$id] );

		$this->sortMenus();

		Project::event('menuDeleted', $menu);

		return $menu;
	}

	/**
	 *
	 */
	public function sortMenus()
	{
		uasort( $this->menus, function( Menus_MenuNamespace_Menu $a, Menus_MenuNamespace_Menu $b ) {
			if($a->getIndex()==$b->getIndex()) {
				return 0;
			}

			if($a->getIndex()>$b->getIndex()) {
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
	 * @param Menus_MenuNamespace_Menu $menu
	 */
	public function addMenu( Menus_MenuNamespace_Menu $menu )
	{
		$this->menus[$menu->getId()] = $menu;

		$this->sortMenus();
	}

}