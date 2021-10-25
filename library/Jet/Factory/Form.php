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

	/**
	 * @var string
	 */
	protected static string $field_class_name_prefix = __NAMESPACE__ . '\Form_Field_';

	/**
	 * @var string
	 */
	protected static string $renderer_pair_class_name = Form_Renderer_Pair::class;

	/**
	 * @var string
	 */
	protected static string $renderer_single_class_name = Form_Renderer_Single::class;

	/**
	 * @return string
	 */
	public static function getFieldClassNamePrefix(): string
	{
		return static::$field_class_name_prefix;
	}

	/**
	 * @param string $field_class_name_prefix
	 */
	public static function setFieldClassNamePrefix( string $field_class_name_prefix ): void
	{
		static::$field_class_name_prefix = $field_class_name_prefix;
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

		if( !$type ) {
			throw new Form_Exception(
				'Unknown field type \'\'', Form_Exception::CODE_UNKNOWN_FIELD_TYPE
			);
		}

		$class_name = static::getFieldClassNamePrefix() . $type;

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