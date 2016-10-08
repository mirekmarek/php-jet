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
								$is_required=false
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
			$is_required
		);
	}

}