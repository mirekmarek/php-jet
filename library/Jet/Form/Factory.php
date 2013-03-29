<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

class Form_Factory extends Factory {
	const BASE_FORM_FIELD_CLASS_NAME = "Jet\\Form_Field";
	const BASE_FORM_DECORATOR_CLASS_NAME = "Jet\\Form_Decorator";

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
		$label="",
		$default_value="",
		$is_required=false,
		$validation_regexp=null,
		array $error_messages = array() ) {

		if(!$type) {
			throw new Form_Exception(
				"Unknown field type ''",
				Form_Exception::CODE_UNKNOWN_FIELD_TYPE
			);
		}

		$class_name =  static::getClassName( self::BASE_FORM_FIELD_CLASS_NAME."_".$type );
		$instance = new $class_name(
			$name,
			$label,
			$default_value,
			$is_required,
			$validation_regexp,
			$error_messages
		);
		self::checkInstance(self::BASE_FORM_FIELD_CLASS_NAME."_".$type, $instance);
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
		$class_name =  static::getClassName( self::BASE_FORM_DECORATOR_CLASS_NAME."_".$decorator."_".$field_type );
		$instance = new $class_name($form, $field);
		self::checkInstance(self::BASE_FORM_DECORATOR_CLASS_NAME."_".$decorator."_".$field_type, $instance);
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
	                                  $label="",
	                                  $default_value="",
	                                  $required=false,
	                                  $validation_regexp=null,
	                                  array $error_messages = array() ) {
		return self::getFieldInstance($type, $name, $label, $default_value, $required, $validation_regexp, $error_messages);
	}

	/**
	 *
	 * @param string $type
	 * @param string $class_name
	 */
	public static function setFieldClassName( $type, $class_name ) {
		self::setClassName(self::BASE_FORM_FIELD_CLASS_NAME."_".$type, $class_name);
	}
}