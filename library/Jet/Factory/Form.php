<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Factory_Form
{

	protected static array $field_class_names = [
		Form::TYPE_HIDDEN                 => Form_Field_Hidden::class,
		Form::TYPE_INPUT                  => Form_Field_Input::class,
		Form::TYPE_INT                    => Form_Field_Int::class,
		Form::TYPE_FLOAT                  => Form_Field_Float::class,
		Form::TYPE_RANGE                  => Form_Field_Range::class,
		Form::TYPE_DATE                   => Form_Field_Date::class,
		Form::TYPE_DATE_TIME              => Form_Field_DateTime::class,
		Form::TYPE_MONTH                  => Form_Field_Month::class,
		Form::TYPE_WEEK                   => Form_Field_Week::class,
		Form::TYPE_TIME                   => Form_Field_Time::class,
		Form::TYPE_EMAIL                  => Form_Field_Email::class,
		Form::TYPE_TEL                    => Form_Field_Tel::class,
		Form::TYPE_URL                    => Form_Field_Url::class,
		Form::TYPE_SEARCH                 => Form_Field_Search::class,
		Form::TYPE_COLOR                  => Form_Field_Color::class,
		Form::TYPE_SELECT                 => Form_Field_Select::class,
		Form::TYPE_MULTI_SELECT           => Form_Field_MultiSelect::class,
		Form::TYPE_CHECKBOX               => Form_Field_Checkbox::class,
		Form::TYPE_RADIO_BUTTON           => Form_Field_RadioButton::class,
		Form::TYPE_TEXTAREA               => Form_Field_Textarea::class,
		Form::TYPE_WYSIWYG                => Form_Field_WYSIWYG::class,
		Form::TYPE_REGISTRATION_USER_NAME => Form_Field_RegistrationUsername::class,
		Form::TYPE_REGISTRATION_EMAIL     => Form_Field_RegistrationEmail::class,
		Form::TYPE_REGISTRATION_PASSWORD  => Form_Field_RegistrationPassword::class,
		Form::TYPE_PASSWORD               => Form_Field_Password::class,
		Form::TYPE_FILE                   => Form_Field_File::class,
		Form::TYPE_FILE_IMAGE             => Form_Field_FileImage::class,
	];

	/**
	 * @var string
	 */
	protected static string $renderer_pair_class_name = Form_Renderer_Pair::class;

	/**
	 * @var string
	 */
	protected static string $renderer_single_class_name = Form_Renderer_Single::class;


	/**
	 * @param string $type
	 * @return string
	 * @throws Form_Exception
	 */
	public static function getFieldClassName( string $type ) : string
	{
		if(!isset(static::$field_class_names[$type])) {
			throw new Form_Exception(
				'Unknown field type \''.$type.'\'', Form_Exception::CODE_UNKNOWN_FIELD_TYPE
			);
		}

		return static::$field_class_names[$type];
	}

	/**
	 * @param string $type
	 * @param string $class_name
	 *
	 * @return string
	 */
	public static function setFieldClassName( string $type, string $class_name ) : string
	{
		return static::$field_class_names[$type] = $class_name;
	}

	/**
	 *
	 * @param string $type
	 * @param string $name
	 * @param string $label
	 * @param mixed $default_value
	 * @param bool $is_required
	 *
	 * @return Form_Field
	 * @throws Form_Exception
	 *
	 */
	public static function getFieldInstance( string $type,
	                                         string $name,
	                                         string $label = '',
	                                         mixed $default_value = '',
	                                         bool $is_required = false ): Form_Field
	{
		$class_name = static::getFieldClassName( $type );

		return new $class_name(
			$name, $label, $default_value, $is_required
		);
	}

	/**
	 * @return string
	 */
	public static function getRendererPairClassName(): string
	{
		return static::$renderer_pair_class_name;
	}

	/**
	 * @param string $renderer_pair_class_name
	 */
	public static function setRendererPairClassName( string $renderer_pair_class_name ): void
	{
		static::$renderer_pair_class_name = $renderer_pair_class_name;
	}

	/**
	 * @param Form $form
	 * @param Form_Field|null $field
	 *
	 * @return Form_Renderer_Pair
	 */
	public static function gerRendererPairInstance( Form $form, Form_Field $field = null ): Form_Renderer_Pair
	{
		$class_name = static::getRendererPairClassName();

		return new $class_name( $form, $field );
	}

	/**
	 * @return string
	 */
	public static function getRendererSingleClassName(): string
	{
		return static::$renderer_single_class_name;
	}

	/**
	 * @param string $renderer_single_class_name
	 */
	public static function setRendererSingleClassName( string $renderer_single_class_name ): void
	{
		static::$renderer_single_class_name = $renderer_single_class_name;
	}


	/**
	 * @param Form $form
	 * @param Form_Field|null $field
	 *
	 * @return Form_Renderer_Single
	 */
	public static function gerRendererSingleInstance( Form $form, Form_Field $field = null ): Form_Renderer_Single
	{
		$class_name = static::getRendererSingleClassName();

		return new $class_name( $form, $field );
	}

}