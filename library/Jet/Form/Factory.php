<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

class Form_Factory extends Factory {
	/**
	 * @var string
	 */
	protected static $form_field_class_name_prefix = 'Jet\\Form_Field_';

	/**
	 * @var string
	 */
	protected static $form_decorator_class_name_prefix = 'Jet\\Form_Decorator_';


	/**
	 * @param string $form_field_class_name_prefix
	 */
	public static function setFormFieldClassNamePrefix($form_field_class_name_prefix) {
		static::$form_field_class_name_prefix = $form_field_class_name_prefix;
	}

	/**
	 * @return string
	 */
	public static function getFormFieldClassNamePrefix() {
		return static::$form_field_class_name_prefix;
	}

	/**
	 * @param string $form_decorator_class_name_prefix
	 */
	public static function setFormDecoratorClassNamePrefix($form_decorator_class_name_prefix) {
		static::$form_decorator_class_name_prefix = $form_decorator_class_name_prefix;
	}

	/**
	 * @return string
	 */
	public static function getFormDecoratorClassNamePrefix() {
		return static::$form_decorator_class_name_prefix;
	}

	/**
	 *
	 * @param $type
	 * @param string $name
	 * @param string $label
	 * @param string $default_value
	 * @param bool $is_required
	 * @param string $validation_regexp
	 * @param array|string $error_messages
	 *
	 * @throws Form_Exception
	 * @internal param string $required
	 * @return Form_Field_Abstract
	 */
	public static function getFieldInstance(
								$type,
								$name,
								$label='',
								$default_value='',
								$is_required=false,
								$validation_regexp=null,
								array $error_messages = array()
							) {

		if(!$type) {
			throw new Form_Exception(
				'Unknown field type \'\'',
				Form_Exception::CODE_UNKNOWN_FIELD_TYPE
			);
		}

		$default_class_name = static::$form_field_class_name_prefix.$type;

		$class_name =  static::getClassName( $default_class_name );
		$instance = new $class_name(
			$name,
			$label,
			$default_value,
			$is_required,
			$validation_regexp,
			$error_messages
		);
		//static::checkInstance( $default_class_name, $instance);
		return $instance;
	}

	/**
	 *
	 * @param $decorator (example: Dojo)
	 * @param $field_type (example: Checkbox)
	 * @param Form $form
	 * @param Form_Field_Abstract $field
	 *
	 * @return Form_Decorator_Abstract|null
	 */
	public static function getDecoratorInstance(
		$decorator,
		$field_type,
		Form $form,
		Form_Field_Abstract $field
	) {
		$default_class_name = static::$form_decorator_class_name_prefix.$decorator.'_'.$field_type;

		$class_name =  static::getClassName( $default_class_name );
		$instance = new $class_name($form, $field);
		//static::checkInstance( $default_class_name, $instance);
		return $instance;
	}


	/**
	 * Alias of getFormFieldInstance
	 *
	 * @param $type
	 * @param string $name
	 * @param string $label (optional)
	 * @param string $default_value (optional)
	 * @param bool $required (optional, default: false)
	 * @param string $validation_regexp (optional)
	 * @param array $error_messages (optional)
	 *
	 * @return Form_Field_Abstract
	 */
	public static function field( $type,
								$name,
								$label='',
								$default_value='',
								$required=false,
								$validation_regexp=null,
								array $error_messages = array()
							) {
		return static::getFieldInstance($type, $name, $label, $default_value, $required, $validation_regexp, $error_messages);
	}

	/**
	 *
	 * @param string $type
	 * @param string $class_name
	 */
	public static function setFieldClassName( $type, $class_name ) {
		static::setClassName( static::$form_field_class_name_prefix.$type, $class_name );
	}
}