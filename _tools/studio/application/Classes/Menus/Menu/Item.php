<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

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

/**
 *
 */
class Menus_Menu_Item extends Navigation_Menu_Item
{

	const URL_PARTS_COUNT = 5;
	const GET_PARAMS_COUNT = 5;


	/**
	 * @var string
	 */
	protected string $set_id = '';


	/**
	 * @var ?Form
	 */
	protected static ?Form $create_form = null;

	/**
	 * @var ?Form
	 */
	protected ?Form $__edit_form = null;

	/**
	 * @return string
	 */
	public function getSetId(): string
	{
		return $this->set_id;
	}

	/**
	 * @param string $set_id
	 */
	public function setSetId( string $set_id ): void
	{
		$this->set_id = $set_id;
	}


	/**
	 * @return string
	 */
	public function getFullId(): string
	{
		return $this->getSetId() . '.' . $this->getMenuId() . '.' . $this->getId();
	}


	/**
	 * @return Form
	 */
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

				$id = Project::generateIdentifier( $field->getValue(), function( $id ) {
					Menus::menuItemExists( $id );
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
			foreach( Bases::getBases() as $base ) {
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
			];


			for( $c = 0; $c < static::URL_PARTS_COUNT; $c++ ) {
				$URL_part = new Form_Field_Input( '/URL_parts/' . $c, '' );
				$fields[] = $URL_part;
			}


			for( $c = 0; $c < static::GET_PARAMS_COUNT; $c++ ) {

				$GET_param_key = new Form_Field_Input( '/GET_params/' . $c . '/key', '' );
				$fields[] = $GET_param_key;

				$GET_param_value = new Form_Field_Input( '/GET_params/' . $c . '/value', '' );
				$fields[] = $GET_param_value;
			}


			$form = new Form( 'create_menu_item_form', $fields );


			$form->setAction( Menus::getActionUrl( 'item/add' ) );

			static::$create_form = $form;
		}

		return static::$create_form;
	}

	/**
	 * @return bool|Menus_Menu_Item
	 */
	public static function catchCreateForm(): bool|Menus_Menu_Item
	{
		$form = static::getCreateForm();
		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}

		$menu_item = new Menus_Menu_Item(
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

		$menu_item->setUrlParts( static::catchURLParts( $form ) );
		$menu_item->setGetParams( static::catchGETParams( $form ) );

		return $menu_item;
	}


	/**
	 *
	 * @return Form
	 *
	 */
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
			foreach( Bases::getBases() as $base ) {
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
			];

			$URL_parts = $this->getUrlParts();
			for( $c = 0; $c < static::URL_PARTS_COUNT; $c++ ) {
				$URL_part_value = $URL_parts[$c] ?? '';

				$URL_part = new Form_Field_Input( '/URL_parts/' . $c, '' );
				$URL_part->setDefaultValue( $URL_part_value );
				$fields[] = $URL_part;
			}


			$GET_params = $this->getGetParams();
			$GET_params_keys = array_keys( $GET_params );
			$GET_params_values = array_values( $GET_params );
			for( $c = 0; $c < static::GET_PARAMS_COUNT; $c++ ) {
				$key = $GET_params_keys[$c] ?? '';
				$value = $GET_params_values[$c] ?? '';

				$GET_param_key = new Form_Field_Input( '/GET_params/' . $c . '/key', '' );
				$GET_param_key->setDefaultValue( $key );
				$fields[] = $GET_param_key;

				$GET_param_value = new Form_Field_Input( '/GET_params/' . $c . '/value', '' );
				$GET_param_value->setDefaultValue( $value );
				$fields[] = $GET_param_value;
			}


			$form = new Form( 'menu_item_edit_form', $fields );
			$form->setAction( Menus::getActionUrl( 'item/edit' ) );
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

		$this->setUrlParts( static::catchURLParts( $form ) );
		$this->setGetParams( static::catchGETParams( $form ) );

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

	/**
	 * @param Form $form
	 * @param string $field_prefix
	 *
	 * @return array
	 */
	public static function catchURLParts( Form $form, string $field_prefix = '' ): array
	{
		$URL_parts = [];
		for( $c = 0; $c < static::URL_PARTS_COUNT; $c++ ) {

			$URL_part = $form->getField( $field_prefix . '/URL_parts/' . $c )->getValue();
			if( $URL_part ) {
				$URL_parts[] = $URL_part;
			}
		}

		return $URL_parts;
	}


	/**
	 * @param Form $form
	 * @param string $field_prefix
	 *
	 * @return array
	 */
	public static function catchGETParams( Form $form, string $field_prefix = '' ): array
	{
		$GET_params = [];
		for( $c = 0; $c < static::GET_PARAMS_COUNT; $c++ ) {

			$GET_param_key = $form->field( $field_prefix . '/GET_params/' . $c . '/key' )->getValue();
			$GET_param_value = $form->field( $field_prefix . '/GET_params/' . $c . '/value' )->getValue();

			if( $GET_param_key && $GET_param_value ) {
				$GET_params[$GET_param_key] = $GET_param_value;
			}
		}

		return $GET_params;
	}


	/**
	 * @return array
	 */
	public function toArray(): array
	{
		$menu_item = [
			'label'            => $this->getLabel(),
			'icon'             => $this->getIcon(),
			'index'            => $this->getIndex(),
			'separator_before' => $this->getSeparatorBefore(),
			'separator_after'  => $this->getSeparatorAfter(),

		];

		if( $this->getUrl() ) {
			$menu_item['URL'] = $this->getUrl();
		} else {
			$menu_item['page_id'] = $this->getPageId();
			$menu_item['base_id'] = $this->getBaseId();
			$menu_item['locale'] = (string)$this->getLocale();
			$menu_item['url_parts'] = $this->getUrlParts();
			$menu_item['get_params'] = $this->getGetParams();
		}

		foreach($menu_item as $key=>$value) {
			if(
				$value==='' ||
				$value===false ||
				$value===[]
			) {
				unset($menu_item[$key]);
			}
		}

		return $menu_item;
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