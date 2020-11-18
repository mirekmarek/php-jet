<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;


use Jet\DataModel;
use Jet\DataModel_Definition_Key as Jet_DataModel_Definition_Key;
use Jet\DataModel_Definition_Property_CustomData;
use Jet\DataModel_Definition_Property_DataModel;
use Jet\DataModel_Exception;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Form_Field_MultiSelect;
use Jet\Form_Field_Select;
use Jet\Tr;

/**
 *
 */
class DataModel_Definition_Key extends Jet_DataModel_Definition_Key
{
	/**
	 * @var array
	 */
	protected static $types = [
		DataModel::KEY_TYPE_INDEX => DataModel::KEY_TYPE_INDEX,
		DataModel::KEY_TYPE_PRIMARY => DataModel::KEY_TYPE_PRIMARY,
		DataModel::KEY_TYPE_UNIQUE => DataModel::KEY_TYPE_UNIQUE
	];


	/**
	 * @var Form
	 */
	protected $__edit_form;

	/**
	 * @var Form
	 */
	protected static $create_form;

	/**
	 * @param string $name
	 * @param string $type
	 * @param array  $property_names
	 *
	 * @throws DataModel_Exception
	 */
	public function __construct( $name='', $type = DataModel::KEY_TYPE_INDEX, array $property_names = [] )
	{
		$this->name = $name;
		$this->property_names = $property_names;
		$this->type = $type;
	}


	/**
	 * @return array
	 */
	public static function getTypes()
	{
		return static::$types;
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
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * @return array
	 */
	public function getPropertyNames()
	{
		return $this->property_names;
	}

	/**
	 * @param array $property_names
	 */
	public function setPropertyNames( array $property_names )
	{
		$this->property_names = $property_names;
	}

	/**
	 * @param string $property_id
	 */
	public function removeProperty( $property_id )
	{
		$i = array_search( $property_id, $this->property_names );
		if( $i===false ) {
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
	public static function checkKeyName( Form_Field_Input $field, $old_name='' )
	{
		$name = $field->getValue();

		if(!$name)	{
			$field->setError( Form_Field_Input::ERROR_CODE_EMPTY );
			return false;
		}

		if(
			!preg_match('/^[a-z0-9_]{2,}$/i', $name)
		) {
			$field->setError(Form_Field_Input::ERROR_CODE_INVALID_FORMAT);

			return false;
		}

		$exists = false;

		foreach( DataModels::getCurrentModel()->getKeys() as $k ) {
			if($k->getName()==$name) {
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
				$old_name!=$name &&
				$exists
			)
		) {
			$field->setCustomError(
				Tr::_('Key with the same name already exists'),
				'key_is_not_unique'
			);

			return false;
		}

		return true;

	}


	/**
	 * @return Form
	 */
	public static function getCreateForm()
	{
		if(!static::$create_form) {

			$properties = [];
			if(DataModels::getCurrentModel()) {
				foreach( DataModels::getCurrentModel()->getProperties() as $property ) {
					if(
						($property instanceof DataModel_Definition_Property_CustomData) ||
						($property instanceof DataModel_Definition_Property_DataModel)
					) {
						continue;
					}

					$properties[ $property->getName() ] = $property->getName();
				}
			}

			$name_field = new Form_Field_Input('name', 'Key name:', '');
			$name_field->setIsRequired(true);
			$name_field->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter key name',
				Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid key name format',
			]);
			$name_field->setValidator( function( Form_Field_Input $field ) {
				return DataModel_Definition_Key::checkKeyName( $field );
			} );


			$type_field = new Form_Field_Select('type', 'Key type:', '');
			$type_field->setSelectOptions( static::getTypes() );
			$type_field->setIsRequired(true);
			$type_field->setErrorMessages([
				Form_Field_Select::ERROR_CODE_EMPTY => 'Please select key type',
				Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select key type',
			]);

			$properties_field = new Form_Field_MultiSelect('properties', 'Properties:', '');
			$properties_field->setSelectOptions($properties);
			$properties_field->setIsRequired(true);
			$properties_field->setErrorMessages([
				Form_Field_MultiSelect::ERROR_CODE_EMPTY => 'Please select some property',
				Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE => 'Please select some property',
			]);

			$fields = [
				$name_field,
				$type_field,
				$properties_field,
			];

			static::$create_form = new Form('key_add_form', $fields);

			static::$create_form->setAction( DataModels::getActionUrl('key/add') );
		}

		return static::$create_form;
	}

	/**
	 * @return bool|DataModel_Definition_Key
	 */
	public static function catchCreateForm()
	{
		$form = static::getCreateForm();

		if(!$form->catchInput() || !$form->validate()) {
			return false;
		}

		$new_key = new DataModel_Definition_Key();

		$new_key->setName( $form->field('name')->getValue() );
		$new_key->setType( $form->field('type')->getValue() );
		$new_key->setPropertyNames( $form->field('properties')->getValue() );

		static::$create_form = null;

		DataModels::getCurrentModel()->addCustomNewKey( $new_key );

		return $new_key;

	}

	/**
	 * @return Form
	 */
	public function getEditForm()
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

				$properties[ $property->getName() ] = $property->getName();
			}

			$name_field = new Form_Field_Input('name', 'Key name:', $this->getName());
			$name_field->setIsRequired(true);
			$name_field->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter key name',
				Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid key name format',
			]);
			$name_field->setCatcher( function( $value ) {
				$this->setName( $value );
			} );
			$old_name = $this->getName();
			$name_field->setValidator( function( Form_Field_Input $field ) use ($old_name) {
				return DataModel_Definition_Key::checkKeyName( $field, $old_name );
			} );

			$type_field = new Form_Field_Select('type', 'Key type:', $this->getType());
			$type_field->setSelectOptions( static::getTypes() );
			$type_field->setIsRequired(true);
			$type_field->setErrorMessages([
				Form_Field_Select::ERROR_CODE_EMPTY => 'Please select key type',
				Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select key type',
			]);
			$type_field->setCatcher( function( $value ) {
				$this->setType( $value );
			} );

			$properties_field = new Form_Field_MultiSelect('properties', 'Properties:', $this->getPropertyNames());
			$properties_field->setSelectOptions($properties);
			$properties_field->setIsRequired(true);
			$properties_field->setErrorMessages([
				Form_Field_MultiSelect::ERROR_CODE_EMPTY => 'Please select some property',
				Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE => 'Please select some property',
			]);
			$properties_field->setCatcher( function( $value ) {
				$this->setPropertyNames( $value );
			} );

			$fields = [
				$name_field,
				$type_field,
				$properties_field,
			];

			$this->__edit_form = new Form('key_edit_form_'.$this->getName(), $fields);

			$this->__edit_form->setAction( DataModels::getActionUrl('key/edit') );

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

		return true;
	}


	/**
	 * @param ClassCreator_Class $class
	 *
	 * @return ClassCreator_Annotation
	 */
	public function createClass_getAsAnnotation( ClassCreator_Class $class )
	{

		$properties = [];

		foreach( $this->getPropertyNames() as $property_name ) {
			$properties[] = var_export( DataModels::getCurrentModel()->getProperty($property_name)->getName(), true );
		}

		$type = '';

		switch( $this->getType() ) {
			case DataModel::KEY_TYPE_INDEX: $type = 'DataModel::KEY_TYPE_INDEX'; break;
			case DataModel::KEY_TYPE_PRIMARY: $type = 'DataModel::KEY_TYPE_PRIMARY'; break;
			case DataModel::KEY_TYPE_UNIQUE: $type = 'DataModel::KEY_TYPE_UNIQUE'; break;
		}

		$k_data = [
			var_export( $this->getName(), true ),
			$properties,
			$type,
		];

		$an = new ClassCreator_Annotation('JetDataModel', 'key', $k_data);

		return $an;
	}

}
