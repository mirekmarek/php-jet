<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\Form_Field;
use Jet\Form_Field_Checkbox;
use Jet\Form_Field_Int;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Navigation_Menu_Item;

/**
 *
 */
class Menus_Menu_Item extends Navigation_Menu_Item
{

	const URL_PARTS_COUNT = 5;
	const GET_PARAMS_COUNT = 5;



	/**
	 * @var Form
	 */
	protected static $create_form;

	/**
	 * @var Form
	 */
	protected $__edit_form;




	/**
	 * @return Form
	 */
	public static function getCreateForm()
	{
		if(!static::$create_form) {

			$label = new Form_Field_Input('label', 'Menu item label:', '' );
			$label->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter menu item label',
			]);


			$id = new Form_Field_Input('id', 'Menu item identifier:', '' );
			$id->setIsRequired(true);
			$id->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter menu item identifier',
			]);
			$id->setValidator( function( Form_Field $field ) {
				if(!$field->getValue()) {
					$field->setError(Form_Field::ERROR_CODE_EMPTY);
					return false;
				}

				$id = Project::generateIdentifier( $field->getValue(), function( $id ) {
					Menus::menuItemExists( $id );
				} );
				$field->setValue( $id );

				return true;
			} );
			
			$icon = new Form_Field_Input('icon', 'Icon:', '' );
			$index = new Form_Field_Int('index', 'Index:', 0 );

			$separator_before = new Form_Field_Checkbox('separator_before', 'Separator before', false);
			$separator_after = new Form_Field_Checkbox('separator_after', 'Separator after', false);


			$URL = new Form_Field_Input('URL', 'URL:', '' );

			$page_id = new Form_Field_Input('page_id', 'Page ID:', '' );
			$site_id = new Form_Field_Input('site_id', 'Site ID:', '' );
			$locale = new Form_Field_Input('locale', 'Locale:', '' );


			$fields = [
				$label,
				$id,
				$icon,
				$index,

				$separator_before,
				$separator_after,

				$URL,

				$page_id,
				$site_id,
				$locale,
			];


			for( $c=0; $c<static::URL_PARTS_COUNT; $c++) {
				$URL_part = new Form_Field_Input('/URL_parts/'.$c, '', '');
				$fields[] = $URL_part;
			}


			for( $c=0; $c<static::GET_PARAMS_COUNT; $c++) {

				$GET_param_key = new Form_Field_Input('/GET_params/'.$c.'/key', '', '');
				$fields[] = $GET_param_key;

				$GET_param_value = new Form_Field_Input('/GET_params/'.$c.'/value', '', '');
				$fields[] = $GET_param_value;
			}


			$form = new Form('create_menu_item_form', $fields );


			$form->setAction( Menus::getActionUrl('item/add') );

			static::$create_form = $form;
		}

		return static::$create_form;
	}

	/**
	 * @return bool|Menus_Menu_Item
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

		$menu_item = new Menus_Menu_Item(
			$form->field('id')->getValue(),
			$form->field('label')->getValue()
		);

		$menu_item->setIndex( $form->field('index')->getValue() );
		$menu_item->setIcon( $form->field('icon')->getValue() );

		$menu_item->setSeparatorBefore( $form->field('separator_before')->getValue() );
		$menu_item->setSeparatorAfter( $form->field('separator_after')->getValue() );


		$menu_item->setURL( $form->field('URL')->getValue() );

		$menu_item->setPageId( $form->field('page_id')->getValue() );
		$menu_item->setSiteId( $form->field('site_id')->getValue() );
		$menu_item->setLocale( $form->field('locale')->getValue() );

		$menu_item->setUrlParts( static::catchURLParts( $form ) );
		$menu_item->setGetParams( static::catchGETparams( $form ) );

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
			$id = new Form_Field_Input('id', 'Menu item identifier:', $this->getId() );
			$id->setIsReadonly(true);

			$label = new Form_Field_Input('label', 'Menu item label:', $this->getLabel() );
			$label->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter menu item label',
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


			$separator_before = new Form_Field_Checkbox('separator_before', 'Separator before', $this->getSeparatorBefore());
			$separator_before->setCatcher( function($value) {
				$this->setSeparatorBefore( $value );
			} );

			$separator_after = new Form_Field_Checkbox('separator_after', 'Separator after', $this->getSeparatorAfter());
			$separator_after->setCatcher( function($value) {
				$this->setSeparatorAfter( $value );
			} );


			$URL = new Form_Field_Input('URL', 'URL:', $this->getUrl() );
			$URL->setCatcher( function( $value ) {
				$this->setURL( $value );
			} );


			$page_id = new Form_Field_Input('page_id', 'Page ID:', $this->getPageId() );
			$page_id->setCatcher( function( $value ) {
				$this->setPageId( $value );
			} );

			$site_id = new Form_Field_Input('site_id', 'Site ID:', $this->getSiteId() );
			$site_id->setCatcher( function( $value ) {
				$this->setSiteId( $value );
			} );

			$locale = new Form_Field_Input('locale', 'Locale:', $this->getLocale() );
			$locale->setCatcher( function( $value ) {
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
				$site_id,
				$locale,
			];

			$URL_parts = $this->getUrlParts();
			for( $c=0; $c<static::URL_PARTS_COUNT; $c++) {
				$URL_part_value = isset($URL_parts[$c]) ? $URL_parts[$c] : '';

				$URL_part = new Form_Field_Input('/URL_parts/'.$c, '', $URL_part_value);
				$fields[] = $URL_part;
			}


			$GET_params = $this->getGetParams();
			$GET_params_keys = array_keys($GET_params);
			$GET_params_values = array_values($GET_params);
			for( $c=0; $c<static::GET_PARAMS_COUNT; $c++) {
				$key = isset($GET_params_keys[$c]) ? $GET_params_keys[$c] : '';
				$value = isset($GET_params_values[$c]) ? $GET_params_values[$c] : '';

				$GET_param_key = new Form_Field_Input('/GET_params/'.$c.'/key', '', $key);
				$fields[] = $GET_param_key;

				$GET_param_value = new Form_Field_Input('/GET_params/'.$c.'/value', '', $value);
				$fields[] = $GET_param_value;
			}



			$form = new Form('menu_item_edit_form', $fields);
			$form->setAction( Menus::getActionUrl('item/edit') );
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

		$this->setUrlParts( static::catchURLParts( $form ) );
		$this->setGetParams( static::catchGETparams( $form ) );

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
	public function getId( $absolute=true )
	{
		return $this->id;
	}
	
	/**
	 * @param Form $form
	 * @param string $field_prefix
	 *
	 * @return array
	 */
	public static function catchURLParts( Form $form, $field_prefix='' )
	{
		$URL_parts = [];
		for( $c=0; $c<static::URL_PARTS_COUNT; $c++) {

			$URL_part = $form->getField($field_prefix.'/URL_parts/'.$c)->getValue();
			if($URL_part) {
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
	public static function catchGETparams( Form $form, $field_prefix='' )
	{
		$GET_params = [];
		for( $c=0; $c<static::GET_PARAMS_COUNT; $c++) {

			$GET_param_key = $form->field($field_prefix.'/GET_params/'.$c.'/key')->getValue();
			$GET_param_value = $form->field($field_prefix.'/GET_params/'.$c.'/value')->getValue();

			if($GET_param_key && $GET_param_value ) {
				$GET_params[$GET_param_key] = $GET_param_value;
			}
		}

		return $GET_params;
	}



	/**
	 * @return array
	 */
	public function toArray()
	{
		$menu_item = [
			'label' => $this->getLabel(),
			'icon' => $this->getIcon(),
			'index' => $this->getIndex(),
			'separator_before' => $this->getSeparatorBefore(),
			'separator_after' => $this->getSeparatorAfter(),

		];

		if($this->getUrl()) {
			$menu_item['URL'] = $this->getUrl();
		} else {
			$menu_item['page_id'] = $this->getPageId();
			$menu_item['site_id'] = $this->getSiteId();
			$menu_item['locale'] = (string)$this->getLocale();
			$menu_item['url_parts'] = $this->getUrlParts();
			$menu_item['get_params'] = $this->getGetParams();
		}

		return $menu_item;
	}
	
}