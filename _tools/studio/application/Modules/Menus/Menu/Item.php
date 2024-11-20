<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\Menus;

use Jet\Form_Field;
use Jet\Form_Field_Checkbox;
use Jet\Form_Field_Int;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;
use Jet\Locale;
use Jet\MVC;
use Jet\MVC_Page_Interface;
use Jet\Navigation_Menu_Item;
use JetStudio\Form_Field_Array;
use JetStudio\Form_Field_AssocArray;
use JetStudio\JetStudio;

/**
 *
 */
class Menu_Item extends Navigation_Menu_Item
{
	
	public const URL_PARTS_COUNT = 5;
	public const GET_PARAMS_COUNT = 5;

	protected static ?Form $create_form = null;
	protected ?Form $__edit_form = null;
	
	public static function getCreateForm(): Form
	{
		if( !static::$create_form ) {

			$label = new Form_Field_Input( 'label', 'Menu item label:' );
			$label->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY => 'Please enter menu item label',
			] );


			$id = new Form_Field_Input( 'id', 'Menu item identifier:' );
			$id->setIsRequired( true );
			$id->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY => 'Please enter menu item identifier',
			] );
			$id->setValidator( function( Form_Field $field ) {
				if( !$field->getValue() ) {
					$field->setError( Form_Field::ERROR_CODE_EMPTY );
					return false;
				}

				$id = JetStudio::generateIdentifier( $field->getValue(), function( $id ) {
					Main::menuItemExists( $id );
				} );
				$field->setValue( $id );

				return true;
			} );

			$icon = new Form_Field_Input( 'icon', 'Icon:' );
			$index = new Form_Field_Int( 'index', 'Index:' );
			$index->setDefaultValue(0);

			$separator_before = new Form_Field_Checkbox( 'separator_before', 'Separator before' );
			$separator_after = new Form_Field_Checkbox( 'separator_after', 'Separator after' );


			$URL = new Form_Field_Input( 'URL', 'URL:' );

			$page_id = new Form_Field_Input( 'page_id', 'Page ID:' );


			$bases = ['' => ''];
			foreach( MVC::getBases() as $base ) {
				$bases[$base->getId()] = $base->getName();
			}
			$base_id = new Form_Field_Select( 'base_id', 'Base:' );
			$base_id->setSelectOptions( $bases );
			$base_id->setIsRequired( false );
			$base_id->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY         => 'Please select base',
				Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select base',
			] );
			
			$locale = new Form_Field_Input( 'locale', 'Locale:' );
			
			
			$GET_params = new Form_Field_AssocArray('GET_params', 'GET parameter:');
			$GET_params->setAssocChar('=');
			$GET_params->setNewRowsCount(static::URL_PARTS_COUNT);
			
			$URL_parts = new Form_Field_Array('URL_parts', 'Custom URL parts:');
			$URL_parts->setNewRowsCount( static::URL_PARTS_COUNT );
			
			
			$fields = [
				$label,
				$id,
				$icon,
				$index,

				$separator_before,
				$separator_after,

				$URL,

				$page_id,
				$base_id,
				$locale,
				
				$GET_params,
				$URL_parts
			];
			


			$form = new Form( 'create_menu_item_form', $fields );


			$form->setAction( Main::getActionUrl( 'item_add' ) );

			static::$create_form = $form;
		}

		return static::$create_form;
	}
	
	public static function catchCreateForm(): bool|Menu_Item
	{
		$form = static::getCreateForm();
		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$menu_item = new Menu_Item(
			$form->field( 'id' )->getValue(),
			$form->field( 'label' )->getValue()
		);

		$menu_item->setIndex( $form->field( 'index' )->getValue() );
		$menu_item->setIcon( $form->field( 'icon' )->getValue() );

		$menu_item->setSeparatorBefore( $form->field( 'separator_before' )->getValue() );
		$menu_item->setSeparatorAfter( $form->field( 'separator_after' )->getValue() );


		$menu_item->setURL( $form->field( 'URL' )->getValue() );

		$menu_item->setPageId( $form->field( 'page_id' )->getValue() );
		$menu_item->setBaseId( $form->field( 'base_id' )->getValue() );
		$menu_item->setLocale( $form->field( 'locale' )->getValue() );

		$menu_item->setUrlParts( $form->field( 'URL_parts' )->getValue() );
		$menu_item->setGetParams( $form->field( 'GET_params' )->getValue() );

		return $menu_item;
	}


	public function getEditForm(): Form
	{
		if( !$this->__edit_form ) {
			$id = new Form_Field_Input( 'id', 'Menu item identifier:' );
			$id->setDefaultValue( $this->getId() );
			$id->setIsReadonly( true );

			$label = new Form_Field_Input( 'label', 'Menu item label:' );
			$label->setDefaultValue( $this->getLabel() );
			$label->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY => 'Please enter menu item label',
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


			$separator_before = new Form_Field_Checkbox( 'separator_before', 'Separator before' );
			$separator_before->setDefaultValue( $this->getSeparatorBefore() );
			$separator_before->setFieldValueCatcher( function( $value ) {
				$this->setSeparatorBefore( $value );
			} );

			$separator_after = new Form_Field_Checkbox( 'separator_after', 'Separator after' );
			$separator_after->setDefaultValue( $this->getSeparatorAfter() );
			$separator_after->setFieldValueCatcher( function( $value ) {
				$this->setSeparatorAfter( $value );
			} );


			$URL = new Form_Field_Input( 'URL', 'URL:' );
			$URL->setDefaultValue( $this->getUrl() );
			$URL->setFieldValueCatcher( function( $value ) {
				$this->setURL( $value );
			} );


			$page_id = new Form_Field_Input( 'page_id', 'Page ID:' );
			$page_id->setDefaultValue( $this->getPageId() );
			$page_id->setFieldValueCatcher( function( $value ) {
				$this->setPageId( $value );
			} );

			$bases = ['' => ''];
			foreach( MVC::getBases() as $base ) {
				$bases[$base->getId()] = $base->getName();
			}
			$base_id = new Form_Field_Select( 'base_id', 'Base:' );
			$base_id->setDefaultValue( $this->getBaseId() );
			$base_id->setSelectOptions( $bases );
			$base_id->setIsRequired( false );
			$base_id->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY         => 'Please select base',
				Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select base',
			] );

			$base_id->setFieldValueCatcher( function( $value ) {
				$this->setBaseId( $value );
			} );

			$locale = new Form_Field_Input( 'locale', 'Locale:' );
			$locale->setDefaultValue( $this->getLocale() );
			$locale->setFieldValueCatcher( function( $value ) {
				$this->setLocale( $value );
			} );
			
			$GET_params = new Form_Field_AssocArray('GET_params', 'GET parameter:');
			$GET_params->setAssocChar('=');
			$GET_params->setNewRowsCount(static::URL_PARTS_COUNT);
			$GET_params->setDefaultValue( $this->get_params );
			$GET_params->setFieldValueCatcher(function($value) {
				$this->setGetParams( $value );
			});
			
			$URL_parts = new Form_Field_Array('URL_parts', 'Custom URL parts:');
			$URL_parts->setNewRowsCount( static::URL_PARTS_COUNT );
			$URL_parts->setDefaultValue( $this->getUrlParts() );
			$URL_parts->setFieldValueCatcher(function($value) {
				$this->setUrlParts( $value );
			});
			

			$fields = [
				$id,
				$label,
				$icon,
				$index,

				$separator_before,
				$separator_after,

				$URL,

				$page_id,
				$base_id,
				$locale,
				
				$GET_params,
				$URL_parts
			];

			
			$form = new Form( 'menu_item_edit_form', $fields );
			$form->setAction( Main::getActionUrl( 'item_edit' ) );
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
			!$form->catch()
		) {
			return false;
		}
		
		$form_name = $form->getName();

		$this->__edit_form = null;
		$this->getEditForm();
		$this->__edit_form->setName( $form_name );

		return true;
	}

	/**
	 * @param bool $absolute (optional)
	 *
	 * @return string
	 */
	public function getId( bool $absolute = true ): string
	{
		return $this->id;
	}
	
	
	public function getTargetPage(): MVC_Page_Interface|null
	{
		$locale = $this->locale;
		if(!$locale) {
			$base = MVC::getBase($this->base_id);
			if($base) {
				if($base->getHasLocale( Locale::getCurrentLocale() )) {
					$locale = Locale::getCurrentLocale();
				} else {
					$locale = $base->getDefaultLocale();
				}
			}
		}
		
		return MVC::getPage( $this->page_id, $locale, $this->base_id );
	}
	
	public function getTitle(): string
	{
		if( $this->label ) {
			return $this->label.' ('.$this->id.')';
		}
		
		$page = $this->getTargetPage();
		
		if( !$page ) {
			return $this->page_id.':'.$this->locale.':'.$this->base_id.' ('.$this->id.')';
		}
		
		return $page->getMenuTitle().' ('.$this->id.')';
		
	}


	/**
	 * @return string
	 */
	public function getLabel(): string
	{
		return $this->label;
	}

	/**
	 * @return string
	 */
	public function getIcon(): string
	{
		return $this->icon;
	}

	/**
	 * @return string
	 */
	public function getUrl(): string
	{
		return $this->URL ? : '';
	}



}