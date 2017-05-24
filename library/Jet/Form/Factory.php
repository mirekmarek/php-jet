<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * 
 */
class Form_Factory
{

	/**
	 * @var string
	 */
	protected static $field_class_name_prefix = __NAMESPACE__.'\Form_Field_';

	/**
	 * @var string
	 */
	protected static $renderer_pair_class_name = __NAMESPACE__.'\Form_Renderer_Pair';

	/**
	 * @var string
	 */
	protected static $renderer_single_class_name = __NAMESPACE__.'\Form_Renderer_Single';

	/**
	 * @return string
	 */
	public static function getFieldClassNamePrefix()
	{
		return static::$field_class_name_prefix;
	}

	/**
	 * @param string $field_class_name_prefix
	 */
	public static function setFieldClassNamePrefix( $field_class_name_prefix )
	{
		static::$field_class_name_prefix = $field_class_name_prefix;
	}

	/**
	 *
	 * @param string $type
	 * @param string $name
	 * @param string $label
	 * @param string $default_value
	 * @param bool   $is_required
	 *
	 * @throws Form_Exception
	 *
	 * @return Form_Field
	 */
	public static function getFieldInstance( $type, $name, $label = '', $default_value = '', $is_required = false )
	{

		if( !$type ) {
			throw new Form_Exception(
				'Unknown field type \'\'', Form_Exception::CODE_UNKNOWN_FIELD_TYPE
			);
		}

		$class_name = static::getFieldClassNamePrefix().$type;

		return new $class_name(
			$name, $label, $default_value, $is_required
		);
	}

	/**
	 * @return string
	 */
	public static function getRendererPairClassName()
	{
		return static::$renderer_pair_class_name;
	}

	/**
	 * @param string $renderer_pair_class_name
	 */
	public static function setRendererPairClassName( $renderer_pair_class_name )
	{
		static::$renderer_pair_class_name = $renderer_pair_class_name;
	}

	/**
	 * @param Form            $form
	 * @param Form_Field|null $field
	 *
	 * @return Form_Renderer_Pair
	 */
	public static function gerRendererPairInstance(  Form $form, Form_Field $field=null  )
	{
		$class_name = static::getRendererPairClassName();

		return new $class_name( $form, $field );
	}

	/**
	 * @return string
	 */
	public static function getRendererSingleClassName()
	{
		return static::$renderer_single_class_name;
	}

	/**
	 * @param string $renderer_single_class_name
	 */
	public static function setRendererSingleClassName( $renderer_single_class_name )
	{
		static::$renderer_single_class_name = $renderer_single_class_name;
	}


	/**
	 * @param Form            $form
	 * @param Form_Field|null $field
	 *
	 * @return Form_Renderer_Single
	 */
	public static function gerRendererSingleInstance(  Form $form, Form_Field $field=null  )
	{
		$class_name = static::getRendererSingleClassName();

		return new $class_name( $form, $field );
	}

}