<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
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
		Form_Field::TYPE_HIDDEN       => Form_Field_Hidden::class,
		Form_Field::TYPE_CSRF_PROTECTION => Form_Field_CSRFProtection::class,
		Form_Field::TYPE_INPUT        => Form_Field_Input::class,
		Form_Field::TYPE_INT          => Form_Field_Int::class,
		Form_Field::TYPE_FLOAT        => Form_Field_Float::class,
		Form_Field::TYPE_RANGE        => Form_Field_Range::class,
		Form_Field::TYPE_DATE         => Form_Field_Date::class,
		Form_Field::TYPE_DATE_TIME    => Form_Field_DateTime::class,
		Form_Field::TYPE_MONTH        => Form_Field_Month::class,
		Form_Field::TYPE_WEEK         => Form_Field_Week::class,
		Form_Field::TYPE_TIME         => Form_Field_Time::class,
		Form_Field::TYPE_EMAIL        => Form_Field_Email::class,
		Form_Field::TYPE_TEL          => Form_Field_Tel::class,
		Form_Field::TYPE_URL          => Form_Field_Url::class,
		Form_Field::TYPE_SEARCH       => Form_Field_Search::class,
		Form_Field::TYPE_COLOR        => Form_Field_Color::class,
		Form_Field::TYPE_SELECT       => Form_Field_Select::class,
		Form_Field::TYPE_MULTI_SELECT => Form_Field_MultiSelect::class,
		Form_Field::TYPE_CHECKBOX     => Form_Field_Checkbox::class,
		Form_Field::TYPE_RADIO_BUTTON => Form_Field_RadioButton::class,
		Form_Field::TYPE_TEXTAREA     => Form_Field_Textarea::class,
		Form_Field::TYPE_WYSIWYG      => Form_Field_WYSIWYG::class,
		Form_Field::TYPE_PASSWORD     => Form_Field_Password::class,
		Form_Field::TYPE_FILE         => Form_Field_File::class,
		Form_Field::TYPE_FILE_IMAGE   => Form_Field_FileImage::class,
	];
	
	protected static array $field_renderer_class_names = [
		Form_Field::TYPE_HIDDEN       => [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_Common::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		],
		Form_Field::TYPE_CSRF_PROTECTION       => [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_Common::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		],
		Form_Field::TYPE_INPUT        => [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_Common::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		],
		Form_Field::TYPE_INT          => [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_Number::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		],
		Form_Field::TYPE_FLOAT        => [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_Number::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		],
		Form_Field::TYPE_RANGE        => [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_Number::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		],
		Form_Field::TYPE_DATE         => [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_Common::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		],
		Form_Field::TYPE_DATE_TIME    => [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_Common::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		],
		Form_Field::TYPE_MONTH        => [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_Common::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		],
		Form_Field::TYPE_WEEK         => [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_Common::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		],
		Form_Field::TYPE_TIME         => [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_Common::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		],
		Form_Field::TYPE_EMAIL        => [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_Common::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		],
		Form_Field::TYPE_TEL          => [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_Common::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		],
		Form_Field::TYPE_URL          => [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_Common::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		],
		Form_Field::TYPE_SEARCH       => [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_Common::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		],
		Form_Field::TYPE_COLOR        => [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_Common::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		],
		Form_Field::TYPE_SELECT       => [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_Select::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		],
		Form_Field::TYPE_MULTI_SELECT => [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_MultiSelect::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		],
		Form_Field::TYPE_CHECKBOX     => [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_Checkbox::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		],
		Form_Field::TYPE_RADIO_BUTTON => [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_RadioButton::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		],
		Form_Field::TYPE_TEXTAREA     => [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_Textarea::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		],
		Form_Field::TYPE_WYSIWYG      => [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_WYSIWYG::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		],
		Form_Field::TYPE_PASSWORD     => [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_Common::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		],
		Form_Field::TYPE_FILE         => [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_File::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		],
		Form_Field::TYPE_FILE_IMAGE   => [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_File::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		],
	];
	
	
	protected static string $renderer_form_tag_class_name = Form_Renderer_Form::class;
	protected static string $renderer_form_message_class_name = Form_Renderer_Form_Message::class;
	
	
	
	/**
	 * @param string $type
	 * @return string
	 * @throws Form_Exception
	 */
	public static function getFieldClassName( string $type ): string
	{
		if( !isset( static::$field_class_names[$type] ) ) {
			throw new Form_Exception(
				'Unknown field type \'' . $type . '\'', Form_Exception::CODE_UNKNOWN_FIELD_TYPE
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
	public static function setFieldClassName( string $type, string $class_name ): string
	{
		return static::$field_class_names[$type] = $class_name;
	}
	
	/**
	 *
	 * @param string $type
	 * @param string $name
	 * @param string $label
	 *
	 * @return Form_Field
	 * @throws Form_Exception
	 *
	 */
	public static function getFieldInstance( string $type,
	                                         string $name,
	                                         string $label = '' ): Form_Field
	{
		$class_name = static::getFieldClassName( $type );
		
		return new $class_name( name: $name, label: $label );
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
	 * @return Form_Renderer_Form
	 */
	public static function getRendererFormTagInstance( Form $form ): Form_Renderer_Form
	{
		$class_name = static::getRendererFormTagClassName();
		
		return new $class_name( $form );
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
		
		return new $class_name( $form );
	}
	
	
	/**
	 * @param string $field_type
	 * @param string $element
	 * @return string
	 */
	public static function getRendererFieldClassName( string $field_type, string $element ): string
	{
		if(!isset(static::$field_renderer_class_names[$field_type][$element])) {
			throw new Form_Exception('Renderer for element '.$element.' of field '.$field_type.' is not defined');
		}
		return static::$field_renderer_class_names[$field_type][$element];
	}
	
	
	/**
	 * @param string $field_type
	 * @param string $element
	 * @param string $class_name
	 */
	public static function setRendererFieldClassName( string $field_type, string $element, string $class_name ): void
	{
		static::$field_renderer_class_names[$field_type][$element] = $class_name;
	}
	
	/**
	 * @param Form_Field $field
	 * @param string $element
	 * @return Form_Renderer
	 */
	public static function getRendererFieldInstance( Form_Field $field, string $element ): Form_Renderer
	{
		$class_name = static::getRendererFieldClassName( $field->getType(), $element );
		
		return new $class_name( $field );
	}
	
	/**
	 * @param string $field_type
	 * @param string $field_class_name
	 * @param array $renderers
	 */
	public static function registerNewFieldType( string $field_type, string $field_class_name, array $renderers = [] ) : void
	{
		
		$default_renderers = [
			'field'     => Form_Renderer_Field::class,
			'container' => Form_Renderer_Field_Container::class,
			'error'     => Form_Renderer_Field_Error::class,
			'help'      => Form_Renderer_Field_Help::class,
			'input'     => Form_Renderer_Field_Input_Common::class,
			'label'     => Form_Renderer_Field_Label::class,
			'row'       => Form_Renderer_Field_Row::class,
		];
		
		foreach($default_renderers as $element => $class_name) {
			if(!isset($renderers[$element])) {
				$renderers[$element] = $class_name;
			}
		}
		
		static::$field_class_names[$field_type] = $field_class_name;
		static::$field_renderer_class_names[$field_type] = $renderers;
	}
	
	/**
	 * @return array
	 */
	public static function getRegisteredFieldTypes() : array
	{
		return array_keys( static::$field_class_names );
	}
}