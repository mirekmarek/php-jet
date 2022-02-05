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
		Form::TYPE_PASSWORD               => Form_Field_Password::class,
		Form::TYPE_FILE                   => Form_Field_File::class,
		Form::TYPE_FILE_IMAGE             => Form_Field_FileImage::class,
	];
	
	protected static string $renderer_form_tag_class_name = Form_Renderer_Form_Tag::class;
	protected static string $renderer_form_message_class_name = Form_Renderer_Form_Message::class;
	protected static string $renderer_field_container_class_name = Form_Renderer_Field_Container::class;
	protected static string $renderer_field_error_class_name = Form_Renderer_Field_Error::class;
	protected static string $renderer_field_input_class_name = Form_Renderer_Field_Input::class;
	protected static string $renderer_field_label_class_name = Form_Renderer_Field_Label::class;
	protected static string $renderer_field_row_class_name = Form_Renderer_Field_Row::class;
	
	

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
	 * @return array
	 */
	public static function getFieldClassNames(): array
	{
		return static::$field_class_names;
	}
	
	/**
	 * @param array|string[] $field_class_names
	 */
	public static function setFieldClassNames( array $field_class_names ): void
	{
		static::$field_class_names = $field_class_names;
	}
	
	
	
	
	
	/**
	 * @return string
	 */
	public static function getRendererFormTagClassName(): string
	{
		return static::$renderer_form_tag_class_name;
	}
	
	/**
	 * @param string $renderer_form_tag_class_name
	 */
	public static function setRendererFormTagClassName( string $renderer_form_tag_class_name ): void
	{
		static::$renderer_form_tag_class_name = $renderer_form_tag_class_name;
	}
	
	
	/**
	 * @param Form $form
	 *
	 * @return Form_Renderer_Form_Tag
	 */
	public static function getRendererFormTagInstance( Form $form ): Form_Renderer_Form_Tag
	{
		$class_name = static::getRendererFormTagClassName();
		
		return new $class_name($form);
	}
	
	
	
	
	
	
	
	/**
	 * @return string
	 */
	public static function getRendererFormMessageClassName(): string
	{
		return static::$renderer_form_message_class_name;
	}
	
	/**
	 * @param string $renderer_form_message_class_name
	 */
	public static function setRendererFormMessageClassName( string $renderer_form_message_class_name ): void
	{
		static::$renderer_form_message_class_name = $renderer_form_message_class_name;
	}
	
	
	/**
	 * @param Form $form
	 *
	 * @return Form_Renderer_Form_Message
	 */
	public static function getRendererFormMessageInstance( Form $form ): Form_Renderer_Form_Message
	{
		$class_name = static::getRendererFormMessageClassName();
		
		return new $class_name($form);
	}
	
	
	
	/**
	 * @return string
	 */
	public static function getRendererFieldContainerClassName(): string
	{
		return static::$renderer_field_container_class_name;
	}
	
	/**
	 * @param string $renderer_field_container_class_name
	 */
	public static function setRendererFieldContainerClassName( string $renderer_field_container_class_name ): void
	{
		static::$renderer_field_container_class_name = $renderer_field_container_class_name;
	}
	
	/**
	 * @param Form_Field $field
	 *
	 * @return Form_Renderer_Field_Container
	 */
	public static function getRendererFieldContainerInstance( Form_Field $field ): Form_Renderer_Field_Container
	{
		$class_name = static::getRendererFieldContainerClassName();
		
		return new $class_name($field);
	}
	
	
	
	
	
	
	/**
	 * @return string
	 */
	public static function getRendererFieldErrorClassName(): string
	{
		return static::$renderer_field_error_class_name;
	}
	
	/**
	 * @param string $renderer_field_error_class_name
	 */
	public static function setRendererFieldErrorClassName( string $renderer_field_error_class_name ): void
	{
		static::$renderer_field_error_class_name = $renderer_field_error_class_name;
	}
	
	/**
	 * @param Form_Field $field
	 *
	 * @return Form_Renderer_Field_Error
	 */
	public static function getRendererFieldErrorInstance( Form_Field $field ): Form_Renderer_Field_Error
	{
		$class_name = static::getRendererFieldErrorClassName();
		
		return new $class_name($field);
	}
	
	
	
	
	
	
	/**
	 * @return string
	 */
	public static function getRendererFieldInputClassName(): string
	{
		return static::$renderer_field_input_class_name;
	}
	
	/**
	 * @param string $renderer_field_input_class_name
	 */
	public static function setRendererFieldInputClassName( string $renderer_field_input_class_name ): void
	{
		static::$renderer_field_input_class_name = $renderer_field_input_class_name;
	}
	
	/**
	 * @param Form_Field $field
	 *
	 * @return Form_Renderer_Field_Input
	 */
	public static function getRendererFieldInputInstance( Form_Field $field ): Form_Renderer_Field_Input
	{
		$class_name = static::getRendererFieldInputClassName();
		
		return new $class_name($field);
	}
	
	
	
	
	
	
	/**
	 * @return string
	 */
	public static function getRendererFieldLabelClassName(): string
	{
		return static::$renderer_field_label_class_name;
	}
	
	/**
	 * @param string $renderer_field_label_class_name
	 */
	public static function setRendererFieldLabelClassName( string $renderer_field_label_class_name ): void
	{
		static::$renderer_field_label_class_name = $renderer_field_label_class_name;
	}
	
	/**
	 * @param Form_Field $field
	 *
	 * @return Form_Renderer_Field_Label
	 */
	public static function getRendererFieldLabelInstance( Form_Field $field ): Form_Renderer_Field_Label
	{
		$class_name = static::getRendererFieldLabelClassName();
		
		return new $class_name($field);
	}
	
	
	
	
	
	
	
	/**
	 * @return string
	 */
	public static function getRendererFieldRowClassName(): string
	{
		return static::$renderer_field_row_class_name;
	}
	
	/**
	 * @param string $renderer_field_row_class_name
	 */
	public static function setRendererFieldRowClassName( string $renderer_field_row_class_name ): void
	{
		static::$renderer_field_row_class_name = $renderer_field_row_class_name;
	}
	
	/**
	 * @param Form_Field $field
	 *
	 * @return Form_Renderer_Field_Row
	 */
	public static function getRendererFieldRowInstance( Form_Field $field ): Form_Renderer_Field_Row
	{
		$class_name = static::getRendererFieldRowClassName();
		
		return new $class_name($field);
	}
	
}