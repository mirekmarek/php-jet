<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Color;
use Jet\Form_Field_Date;
use Jet\Form_Field_Email;
use Jet\Form_Field_File;
use Jet\Form_Field_Float;
use Jet\Form_Field_Input;
use Jet\Form_Field_Int;
use Jet\Form_Field_Month;
use Jet\Form_Field_MultiSelect;
use Jet\Form_Field_Password;
use Jet\Form_Field_RadioButton;
use Jet\Form_Field_RegistrationEmail;
use Jet\Form_Field_RegistrationPassword;
use Jet\Form_Field_RegistrationUsername;
use Jet\Form_Field_Search;
use Jet\Form_Field_Select;
use Jet\Form_Field_Checkbox;
use Jet\Form_Field_Tel;
use Jet\Form_Field_Textarea;
use Jet\Form_Field_Time;
use Jet\Form_Field_Url;
use Jet\Form_Field_Week;
use Jet\Form_Field_WYSIWYG;
use Jet\Tr;
use Jet\DataModel;

/**
 *
 */
class DataModel_Definition_Property {

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
	 * @var array
	 */
	protected static array $form_error_codes = [
		Form_Field::ERROR_CODE_EMPTY => [
			'label' => Form_Field::ERROR_CODE_EMPTY
		],
		Form_Field::ERROR_CODE_INVALID_FORMAT => [
			'label' => Form_Field::ERROR_CODE_INVALID_FORMAT
		],
		Form_Field_Int::ERROR_CODE_OUT_OF_RANGE => [
			'label' => Form_Field_Int::ERROR_CODE_OUT_OF_RANGE
		],
		Form_Field_Select::ERROR_CODE_INVALID_VALUE => [
			'label' => Form_Field_Select::ERROR_CODE_INVALID_VALUE
		],
		Form_Field_File::ERROR_CODE_DISALLOWED_FILE_TYPE => [
			'label' => Form_Field_File::ERROR_CODE_DISALLOWED_FILE_TYPE
		],
		Form_Field_File::ERROR_CODE_FILE_IS_TOO_LARGE => [
			'label' => Form_Field_File::ERROR_CODE_FILE_IS_TOO_LARGE
		],
		Form_Field_RegistrationUsername::ERROR_CODE_USER_ALREADY_EXISTS => [
			'label' => Form_Field_RegistrationUsername::ERROR_CODE_USER_ALREADY_EXISTS
		],
		Form_Field_RegistrationPassword::ERROR_CODE_CHECK_EMPTY => [
			'label' => Form_Field_RegistrationPassword::ERROR_CODE_CHECK_EMPTY
		],
		Form_Field_RegistrationPassword::ERROR_CODE_CHECK_NOT_MATCH => [
			'label' => Form_Field_RegistrationPassword::ERROR_CODE_CHECK_NOT_MATCH
		],
		Form_Field_RegistrationPassword::ERROR_CODE_WEAK_PASSWORD => [
			'label' => Form_Field_RegistrationPassword::ERROR_CODE_WEAK_PASSWORD
		]
	];

	/**
	 * @var array
	 */
	protected static array $form_field_types = [
		'false' => [
			'label' => '- No form field -',
			'required_options' => [],
			'required_error_messages' => [],
		],

		Form::TYPE_HIDDEN  => [
			'label' => Form::TYPE_HIDDEN,
			'type' => 'Form::TYPE_HIDDEN',
			'required_options' => [
				'form_field_creator_method_name',
			],
			'required_error_messages' => [],
		],

		Form::TYPE_INPUT  => [
			'label' => Form::TYPE_INPUT,
			'type' => 'Form::TYPE_INPUT',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_field_validation_regexp',
				'form_setter_name',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field::ERROR_CODE_EMPTY,
				Form_Field::ERROR_CODE_INVALID_FORMAT,
			],
		],

		Form::TYPE_TEXTAREA  => [
			'label' => Form::TYPE_TEXTAREA,
			'type' => 'Form::TYPE_TEXTAREA',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_setter_name',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field_Textarea::ERROR_CODE_EMPTY
			],
		],
		Form::TYPE_WYSIWYG  => [
			'label' => Form::TYPE_WYSIWYG,
			'type' => 'Form::TYPE_WYSIWYG',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_setter_name',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field_WYSIWYG::ERROR_CODE_EMPTY
			],
		],
		Form::TYPE_INT  => [
			'label' => Form::TYPE_INT,
			'type' => 'Form::TYPE_INT',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_field_min_value',
				'form_field_max_value',
				'form_setter_name',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field_Int::ERROR_CODE_EMPTY,
				Form_Field_Int::ERROR_CODE_OUT_OF_RANGE,
			],
		],
		Form::TYPE_FLOAT  => [
			'label' => Form::TYPE_FLOAT,
			'type' => 'Form::TYPE_FLOAT',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_field_min_value',
				'form_field_max_value',
				'form_setter_name',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field_Float::ERROR_CODE_EMPTY,
				Form_Field_Float::ERROR_CODE_OUT_OF_RANGE,
			],
		],
		Form::TYPE_RANGE  => [
			'label' => Form::TYPE_RANGE,
			'type' => 'Form::TYPE_RANGE',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_field_min_value',
				'form_field_max_value',
				'form_setter_name',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field_Int::ERROR_CODE_EMPTY,
				Form_Field_Int::ERROR_CODE_OUT_OF_RANGE,
			],
		],

		Form::TYPE_DATE => [
			'label' => Form::TYPE_DATE,
			'type' => 'Form::TYPE_DATE',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_setter_name',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field_Date::ERROR_CODE_EMPTY,
				Form_Field_Date::ERROR_CODE_INVALID_FORMAT,
			],
		],
		Form::TYPE_DATE_TIME  => [
			'label' => Form::TYPE_DATE_TIME,
			'type' => 'Form::TYPE_DATE_TIME',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_setter_name',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field_Time::ERROR_CODE_EMPTY,
				Form_Field_Time::ERROR_CODE_INVALID_FORMAT,
			],
		],
		Form::TYPE_MONTH  => [
			'label' => Form::TYPE_MONTH,
			'type' => 'Form::TYPE_MONTH',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_setter_name',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field_Month::ERROR_CODE_EMPTY,
				Form_Field_Month::ERROR_CODE_INVALID_FORMAT,
			],
		],
		Form::TYPE_WEEK  => [
			'label' => Form::TYPE_WEEK,
			'type' => 'Form::TYPE_WEEK',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_setter_name',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field_Week::ERROR_CODE_EMPTY,
				Form_Field_Week::ERROR_CODE_INVALID_FORMAT,
			],
		],
		Form::TYPE_TIME  => [
			'label' => Form::TYPE_TIME,
			'type' => 'Form::TYPE_TIME',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_setter_name',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field_Time::ERROR_CODE_EMPTY,
				Form_Field_Time::ERROR_CODE_INVALID_FORMAT,
			],
		],

		Form::TYPE_EMAIL  => [
			'label' => Form::TYPE_EMAIL,
			'type' => 'Form::TYPE_EMAIL',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_setter_name',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field_Email::ERROR_CODE_EMPTY,
				Form_Field_Email::ERROR_CODE_INVALID_FORMAT,
			],
		],
		Form::TYPE_TEL  => [
			'label' => Form::TYPE_TEL,
			'type' => 'Form::TYPE_TEL',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_setter_name',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field_Tel::ERROR_CODE_EMPTY,
				Form_Field_Tel::ERROR_CODE_INVALID_FORMAT,
			],
		],

		Form::TYPE_URL  => [
			'label' => Form::TYPE_URL,
			'type' => 'Form::TYPE_URL',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_setter_name',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field_Url::ERROR_CODE_EMPTY,
				Form_Field_Url::ERROR_CODE_INVALID_FORMAT,
			],
		],
		Form::TYPE_SEARCH  => [
			'label' => Form::TYPE_SEARCH,
			'type' => 'Form::TYPE_SEARCH',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_setter_name',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field_Search::ERROR_CODE_EMPTY,
				Form_Field_Search::ERROR_CODE_INVALID_FORMAT,
			],
		],

		Form::TYPE_COLOR  => [
			'label' => Form::TYPE_COLOR,
			'type' => 'Form::TYPE_COLOR',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_setter_name',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field_Color::ERROR_CODE_EMPTY,
				Form_Field_Color::ERROR_CODE_INVALID_FORMAT,
			],
		],

		Form::TYPE_SELECT  => [
			'label' => Form::TYPE_SELECT,
			'type' => 'Form::TYPE_SELECT',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_setter_name',
				'form_field_get_select_options_callback',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field_Select::ERROR_CODE_EMPTY,
				Form_Field_Select::ERROR_CODE_INVALID_VALUE,
			],
		],
		Form::TYPE_MULTI_SELECT  => [
			'label' => Form::TYPE_MULTI_SELECT,
			'type' => 'Form::TYPE_MULTI_SELECT',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_setter_name',
				'form_field_get_select_options_callback',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field_MultiSelect::ERROR_CODE_EMPTY,
				Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE,
			],
		],

		Form::TYPE_CHECKBOX  => [
			'label' => Form::TYPE_CHECKBOX,
			'type' => 'Form::TYPE_CHECKBOX',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_setter_name',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field_Checkbox::ERROR_CODE_EMPTY
			],
		],
		Form::TYPE_RADIO_BUTTON  => [
			'label' => Form::TYPE_RADIO_BUTTON,
			'type' => 'Form::TYPE_RADIO_BUTTON',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_setter_name',
				'form_field_get_select_options_callback',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field_RadioButton::ERROR_CODE_EMPTY,
				Form_Field_RadioButton::ERROR_CODE_INVALID_VALUE,
			],
		],


		Form::TYPE_PASSWORD  => [
			'label' => Form::TYPE_PASSWORD,
			'type' => 'Form::TYPE_PASSWORD',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_setter_name',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field_Password::ERROR_CODE_EMPTY,
			],
		],

		Form::TYPE_FILE  => [
			'label' => Form::TYPE_FILE,
			'type' => 'Form::TYPE_FILE',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_setter_name',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field_File::ERROR_CODE_DISALLOWED_FILE_TYPE,
				Form_Field_File::ERROR_CODE_FILE_IS_TOO_LARGE,
			],
		],
		Form::TYPE_FILE_IMAGE  => [
			'label' => Form::TYPE_FILE_IMAGE,
			'type' => 'Form::TYPE_FILE_IMAGE',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_setter_name',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field_File::ERROR_CODE_DISALLOWED_FILE_TYPE,
				Form_Field_File::ERROR_CODE_FILE_IS_TOO_LARGE,
			],
		],


		Form::TYPE_REGISTRATION_USER_NAME  => [
			'label' => Form::TYPE_REGISTRATION_USER_NAME,
			'type' => 'Form::TYPE_REGISTRATION_USER_NAME',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_setter_name',
				'form_field_validation_regexp',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field_RegistrationUsername::ERROR_CODE_EMPTY,
				Form_Field_RegistrationUsername::ERROR_CODE_INVALID_FORMAT,
				Form_Field_RegistrationUsername::ERROR_CODE_USER_ALREADY_EXISTS,
			],
		],

		Form::TYPE_REGISTRATION_EMAIL  => [
			'label' => Form::TYPE_REGISTRATION_EMAIL,
			'type' => 'Form::TYPE_REGISTRATION_EMAIL',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_setter_name',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field_RegistrationEmail::ERROR_CODE_EMPTY,
				Form_Field_RegistrationEmail::ERROR_CODE_INVALID_FORMAT,
				Form_Field_RegistrationEmail::ERROR_CODE_USER_ALREADY_EXISTS,
			],
		],

		Form::TYPE_REGISTRATION_PASSWORD  => [
			'label' => Form::TYPE_REGISTRATION_PASSWORD,
			'type' => 'Form::TYPE_REGISTRATION_PASSWORD',
			'required_options' => [
				'form_field_is_required',
				'form_field_label',
				'form_setter_name',
				'form_field_creator_method_name',
			],
			'required_error_messages' => [
				Form_Field_RegistrationPassword::ERROR_CODE_EMPTY,
				Form_Field_RegistrationPassword::ERROR_CODE_CHECK_EMPTY,
				Form_Field_RegistrationPassword::ERROR_CODE_CHECK_NOT_MATCH,
				Form_Field_RegistrationPassword::ERROR_CODE_WEAK_PASSWORD,
			],
		],

	];



	/**
	 * @var ?Form
	 */
	protected static ?Form $create_form = null;

	/**
	 * @return array
	 */
	public static function getPropertyTypes() : array
	{
		$types = [];

		foreach( static::$types as $type=>$label ) {
			$types[$type] = Tr::_($label);
		}

		return $types;
	}

	/**
	 * @return array
	 */
	public static function getFormErrorCodes() : array
	{
		return static::$form_error_codes;
	}

	/**
	 * @return array
	 */
	public static function getFormFieldTypes() : array
	{
		return static::$form_field_types;
	}

	/**
	 * @param Form_Field_Input $field
	 *
	 * @return bool
	 */
	public static function checkPropertyNameFormat( Form_Field_Input $field ) : bool
	{
		$name = $field->getValue();

		if( !$name ) {
			$field->setError( Form_Field_Input::ERROR_CODE_EMPTY );
			return false;
		}

		if( !preg_match( '/^[a-z0-9_]{2,}$/i', $name ) ) {
			$field->setError( Form_Field_Input::ERROR_CODE_INVALID_FORMAT );

			return false;
		}

		return true;
	}

	/**
	 * @param Form_Field_Input $field
	 * @param string $old_name
	 *
	 * @return bool
	 */
	public static function checkPropertyName( Form_Field_Input $field, string $old_name='' ) : bool
	{
		if(!static::checkPropertyNameFormat($field)) {
			return false;
		}
		$name = $field->getValue();

		$exists = false;

		if(DataModels::getCurrentModel()) {
			foreach( DataModels::getCurrentModel()->getProperties() as $p ) {
				if($p->getName()==$name) {
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
				$old_name!=$name &&
				$exists
			)
		) {
			$field->setCustomError(
				Tr::_('Property with the same name already exists'),
				'property_is_not_unique'
			);

			return false;
		}

		return true;

	}


	/**
	 * @return Form
	 */
	public static function getCreateForm() : Form
	{
		if(!static::$create_form) {
			$property_name = new Form_Field_Input('property_name', 'Property name:', '');

			$property_name->setIsRequired(true);
			$property_name->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter property name',
				Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid property name format'
			]);
			$property_name->setValidator( function( Form_Field_Input $field ) {
				return DataModel_Definition_Property::checkPropertyName( $field );
			} );

			$type = new Form_Field_Select('type', 'Type:', '');
			$types = static::getPropertyTypes();
			unset( $types[DataModel::TYPE_DATA_MODEL] );

			$type->setSelectOptions( $types );
			$type->setIsRequired( true );
			$type->setErrorMessages([
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please select property type',
				Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select property type'
			]);

			$fields[] = $type;


			$fields = [
				$property_name,
				$type,
			];


			static::$create_form = new Form('create_property_form', $fields );

			static::$create_form->setAction( DataModels::getActionUrl('property/add') );

		}

		return static::$create_form;
	}

	/**
	 * @param DataModel_Class $class
	 *
	 * @return bool|DataModel_Definition_Property_Interface
	 */
	public static function catchCreateForm( DataModel_Class $class ) : bool|DataModel_Definition_Property_Interface
	{
		$form = static::getCreateForm();
		if(
			!$form->catchInput() ||
			!$form->validate()
		) {
			return false;
		}



		$property_name = $form->field('property_name')->getValue();
		$type = $form->field('type')->getValue();


		$class_name = __NAMESPACE__.'\\DataModel_Definition_Property_'.$type;

		/**
		 * @var DataModel_Definition_Property_Interface $new_property;
		 */
		$new_property = new $class_name( $class->getFullClassName(), $property_name );

		$class->getDefinition()->addProperty( $new_property );

		static::$create_form = null;

		return $new_property;
	}


}