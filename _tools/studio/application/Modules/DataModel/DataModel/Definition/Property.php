<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\DataModel;

use Jet\Data_Text;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;
use Jet\Http_Request;
use Jet\Tr;
use Jet\DataModel;

/**
 *
 */
class DataModel_Definition_Property
{

	/**
	 * @var array
	 */
	protected static array $types = [
		DataModel::TYPE_STRING           => 'String',
		DataModel::TYPE_INT              => 'Integer',
		DataModel::TYPE_FLOAT            => 'Float',
		DataModel::TYPE_BOOL             => 'Bool',
		DataModel::TYPE_DATE             => 'Date',
		DataModel::TYPE_DATE_TIME        => 'Date and time',
		DataModel::TYPE_LOCALE           => 'Locale (language and country) identifier',
		DataModel::TYPE_DATA_MODEL       => 'Related DataModel',
		DataModel::TYPE_CUSTOM_DATA      => 'Custom data (serialized)',
		DataModel::TYPE_ID               => 'ID - string (max 64 chars)',
		DataModel::TYPE_ID_AUTOINCREMENT => 'ID - int, autoincrement',
	];


	/**
	 * @var ?Form
	 */
	protected static ?Form $create_form = null;

	/**
	 * @return array
	 */
	public static function getPropertyTypes(): array
	{
		$types = [];

		foreach( static::$types as $type => $label ) {
			$types[$type] = Tr::_( $label );
		}

		return $types;
	}

	/**
	 * @param string $name
	 * @param Form_Field_Input|null $field
	 *
	 * @return bool
	 */
	public static function checkPropertyNameFormat( string $name, ?Form_Field_Input $field=null ): bool
	{
		if( !$name ) {
			$field?->setError( Form_Field::ERROR_CODE_EMPTY );
			return false;
		}

		if( !preg_match( '/^[a-z0-9_]{2,}$/i', $name ) ) {
			$field?->setError( Form_Field::ERROR_CODE_INVALID_FORMAT );

			return false;
		}

		return true;
	}

	/**
	 * @param string $name
	 * @param Form_Field_Input|null $field
	 * @param string $old_name
	 *
	 * @return bool
	 */
	public static function checkPropertyName( string $name, ?Form_Field_Input $field=null, string $old_name = '' ): bool
	{
		if( !static::checkPropertyNameFormat( $name, $field ) ) {
			return false;
		}

		$exists = false;

		if( DataModels::getCurrentModel() ) {
			foreach( DataModels::getCurrentModel()->getProperties() as $p ) {
				if( $p->getName() == $name ) {
					$exists = true;
					break;
				}
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
			$field?->setError( 'property_is_not_unique' );

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
			$property_name = new Form_Field_Input( 'property_name[]', 'Property name:' );

			$property_name->setIsRequired( true );
			$property_name->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY          => 'Please enter property name',
				Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid property name format',
				'property_is_not_unique' => 'Property with the same name already exists',
			] );
			$property_name->setValidator( function( Form_Field_Input $field ) {
				return DataModel_Definition_Property::checkPropertyName( $field->getValue(), $field );
			} );

			$type = new Form_Field_Select( 'type[]', 'Type:' );
			$types = static::getPropertyTypes();
			unset( $types[DataModel::TYPE_DATA_MODEL] );

			$type->setSelectOptions( $types );
			$type->setIsRequired( true );
			$type->setErrorMessages( [
				Form_Field::ERROR_CODE_EMPTY          => 'Please select property type',
				Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select property type'
			] );

			$fields[] = $type;


			$fields = [
				$property_name,
				$type,
			];


			static::$create_form = new Form( 'create_property_form', $fields );

			static::$create_form->setAction( DataModels::getActionUrl( 'property_add' ) );

		}

		return static::$create_form;
	}

	/**
	 * @param DataModel_Class $class
	 *
	 * @return DataModel_Definition_Property_Interface[]
	 */
	public static function catchCreateForm( DataModel_Class $class ): array
	{
		$new_properties = [];

		$POST = Http_Request::POST();
		
		$names = $POST->getRaw('property_name');
		$types = $POST->getRaw('type');
		
		$all_types = static::getPropertyTypes();
		unset( $all_types[DataModel::TYPE_DATA_MODEL] );
		
		
		foreach($names as $i=>$property_name) {
			$type = $types[$i];
			
			if(!isset($all_types[$type])) {
				continue;
			}
			
			$property_name = trim($property_name);
			$property_name = Data_Text::removeAccents($property_name);
			$property_name = str_replace(' ', '_', $property_name);
			
			if(
				!$property_name ||
				!DataModel_Definition_Property::checkPropertyName( $property_name )
			) {
				continue;
			}
			
			$class_name = DataModel_Definition_Property::class.'_'.$type;

			/**
			 * @var DataModel_Definition_Property_Interface $new_property ;
			 */
			$new_property = new $class_name( $class->getFullClassName(), $property_name );
			
			$class->getDefinition()->addProperty( $new_property );
			
			$new_properties[] = $new_property;
		}

		return $new_properties;
	}


}