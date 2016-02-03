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

class Form_Factory {

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
	 *
	 * @return Form_Field_Abstract
	 */
	public static function getFieldInstance(
								$type,
								$name,
								$label='',
								$default_value='',
								$is_required=false,
								$validation_regexp=null,
								array $error_messages = []
							) {

		if(!$type) {
			throw new Form_Exception(
				'Unknown field type \'\'',
				Form_Exception::CODE_UNKNOWN_FIELD_TYPE
			);
		}

		$class_name = JET_FORM_FIELD_CLASS_NAME_PREFIX.$type;

		return new $class_name(
			$name,
			$label,
			$default_value,
			$is_required,
			$validation_regexp,
			$error_messages
		);
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
		$class_name = JET_FORM_DECORATOR_CLASS_NAME_PREFIX.$decorator.'_'.$field_type;

		return new $class_name($form, $field);
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
								array $error_messages = []
							) {
		return static::getFieldInstance($type, $name, $label, $default_value, $required, $validation_regexp, $error_messages);
	}

}