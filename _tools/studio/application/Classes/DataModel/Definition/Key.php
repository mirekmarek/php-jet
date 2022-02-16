<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;


use Jet\DataModel;
use Jet\DataModel_Definition_Key as Jet_DataModel_Definition_Key;
use Jet\DataModel_Definition_Property_CustomData;
use Jet\DataModel_Definition_Property_DataModel;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Input;
use Jet\Form_Field_MultiSelect;
use Jet\Form_Field_Select;

/**
 *
 */
class DataModel_Definition_Key extends Jet_DataModel_Definition_Key
{
	/**
	 * @var array
	 */
	protected static array $types = [
		DataModel::KEY_TYPE_INDEX   => DataModel::KEY_TYPE_INDEX,
		DataModel::KEY_TYPE_PRIMARY => DataModel::KEY_TYPE_PRIMARY,
		DataModel::KEY_TYPE_UNIQUE  => DataModel::KEY_TYPE_UNIQUE
	];


	/**
	 * @var ?Form
	 */
	protected ?Form $__edit_form = null;

	/**
	 * @var ?Form
	 */
	protected static ?Form $create_form = null;

	/**
	 * @param string $name
	 * @param string $type
	 * @param array $property_names
	 */
	public function __construct( string $name = '', string $type = DataModel::KEY_TYPE_INDEX, array $property_names = [] )
	{
		$this->name = $name;
		$this->property_names = $property_names;
		$this->type = $type;
	}


	/**
	 * @return array
	 */
	public static function getTypes(): array
	{
		return static::$types;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ): void
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType( string $type ): void
	{
		$this->type = $type;
	}

	/**
	 * @return array
	 */
	public function getPropertyNames(): array
	{
		return $this->property_names;
	}

	/**
	 * @param array $property_names
	 */
	public function setPropertyNames( array $property_names ): void
	{
		$this->property_names = $property_names;
	}

	/**
	 * @param string $property_id
	 */
	public function removeProperty( string $property_id ): void
	{
		$i = array_search( $property_id, $this->property_names );
		if( $i === false ) {
			return;
		}

		unset( $this->property_names[$i] );
		$this->property_names = array_values( $this->property_names );
	}

	/**
	 * @param Form_Field_Input $field
	 * @param string $old_name
	 *
	 * @return bool
	 */
	public static function checkKeyName( Form_Field_Input $field, string $old_name = '' ): bool
	{
		$name = $field->getValue();

		if( !$name ) {
			$field->setError( Form_Field::ERROR_CODE_EMPTY );
			return false;
		}

		if(
		!preg_match( '/^[a-z0-9_]{2,}$/i', $name )
		) {
			$field->setError( Form_Field::ERROR_CODE_INVALID_FORMAT );

			return false;
		}

		$exists = false;

		foreach( DataModels::getCurrentModel()->getKeys() as $k ) {
			if( $k->getName() == $name ) {
				$exists = true;
				break;
			}
		}

		if(
			(
				!$old_name &&
				$exists
			)
			||
			(
				$old_name &&
				$old_name != $name &&
				$exists
			)
		) {
			$field->setError( 'key_is_not_unique' );

			return false;
		}

		return true;

	}


	/**
	 * @return Form
	 */
	public static function getCreateForm(): Form
	{
		if( !static::$create_form ) {

			$properties = [];
			if( DataModels::getCurrentModel() ) {
				foreach( DataModels::getCurrentModel()->getProperties() as $property ) {
					if(
						($property instanceof DataModel_Definition_Property_CustomData) ||
						($property instanceof DataModel_Definition_Property_DataModel)
					) {
						continue;
					}

					$properties[$property->getName()] = $property->getName();
				}
			}

			$name_field = new Form_Field_Input( 'name', 'Key name:' );
			$name_field->setIsRequired( true );
			$name_field->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY          => 'Please enter key name',
				Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid key name format',
				'key_is_not_unique'                         => 'Key with the same name already exists',
			] );
			$name_field->setValidator( function( Form_Field_Input $field ) {
				return DataModel_Definition_Key::checkKeyName( $field );
			} );


			$type_field = new Form_Field_Select( 'type', 'Key type:' );
			$type_field->setSelectOptions( static::getTypes() );
			$type_field->setIsRequired( true );
			$type_field->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY         => 'Please select key type',
				Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select key type',
			] );

			$properties_field = new Form_Field_MultiSelect( 'properties', 'Properties:' );
			$properties_field->setSelectOptions( $properties );
			$properties_field->setIsRequired( true );
			$properties_field->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY         => 'Please select some property',
				Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select some property',
			] );

			$fields = [
				$name_field,
				$type_field,
				$properties_field,
			];

			static::$create_form = new Form( 'key_add_form', $fields );

			static::$create_form->setAction( DataModels::getActionUrl( 'key/add' ) );
		}

		return static::$create_form;
	}

	/**
	 * @return bool|DataModel_Definition_Key
	 */
	public static function catchCreateForm(): bool|DataModel_Definition_Key
	{
		$form = static::getCreateForm();

		if( !$form->catchInput() || !$form->validate() ) {
			return false;
		}

		$new_key = new DataModel_Definition_Key();

		$new_key->setName( $form->field( 'name' )->getValue() );
		$new_key->setType( $form->field( 'type' )->getValue() );
		$new_key->setPropertyNames( $form->field( 'properties' )->getValue() );

		static::$create_form = null;

		DataModels::getCurrentModel()->addCustomNewKey( $new_key );

		return $new_key;

	}

	/**
	 * @return Form
	 */
	public function getEditForm(): Form
	{
		if( !$this->__edit_form ) {
			$properties = [];
			foreach( DataModels::getCurrentModel()->getProperties() as $property ) {
				if(
					($property instanceof DataModel_Definition_Property_CustomData) ||
					($property instanceof DataModel_Definition_Property_DataModel)
				) {
					continue;
				}

				$properties[$property->getName()] = $property->getName();
			}

			$name_field = new Form_Field_Input( 'name', 'Key name:' );
			$name_field->setDefaultValue( $this->getName() );
			$name_field->setIsRequired( true );
			$name_field->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY          => 'Please enter key name',
				Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid key name format',
				'key_is_not_unique'                         => 'Key with the same name already exists',
			] );
			$name_field->setFieldValueCatcher( function( $value ) {
				$this->setName( $value );
			} );
			$old_name = $this->getName();
			$name_field->setValidator( function( Form_Field_Input $field ) use ( $old_name ) {
				return DataModel_Definition_Key::checkKeyName( $field, $old_name );
			} );

			$type_field = new Form_Field_Select( 'type', 'Key type:' );
			$type_field->setDefaultValue( $this->getType() );
			
			$type_field->setSelectOptions( static::getTypes() );
			$type_field->setIsRequired( true );
			$type_field->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY         => 'Please select key type',
				Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select key type',
			] );
			$type_field->setFieldValueCatcher( function( $value ) {
				$this->setType( $value );
			} );

			$properties_field = new Form_Field_MultiSelect( 'properties', 'Properties:' );
			$properties_field->setDefaultValue( $this->getPropertyNames() );
			$properties_field->setSelectOptions( $properties );
			$properties_field->setIsRequired( true );
			$properties_field->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY         => 'Please select some property',
				Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select some property',
			] );
			$properties_field->setFieldValueCatcher( function( $value ) {
				$this->setPropertyNames( $value );
			} );

			$fields = [
				$name_field,
				$type_field,
				$properties_field,
			];

			$this->__edit_form = new Form( 'key_edit_form_' . $this->getName(), $fields );

			$this->__edit_form->setAction( DataModels::getActionUrl( 'key/edit' ) );

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

		return true;
	}


	/**
	 * @param ClassCreator_Class $class
	 *
	 * @return array
	 */
	public function createClass_getAsAttribute( ClassCreator_Class $class ): array
	{

		$properties = [];

		foreach( $this->getPropertyNames() as $property_name ) {
			$properties[] = DataModels::getCurrentModel()->getProperty( $property_name )->getName();
		}

		$type = match ($this->getType()) {
			DataModel::KEY_TYPE_INDEX => 'DataModel::KEY_TYPE_INDEX',
			DataModel::KEY_TYPE_PRIMARY => 'DataModel::KEY_TYPE_PRIMARY',
			DataModel::KEY_TYPE_UNIQUE => 'DataModel::KEY_TYPE_UNIQUE',
		};

		return [
			'name'           => $this->getName(),
			'property_names' => $properties,
			'type'           => $type,
		];

	}

}
