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
 * @package DataModel
 * @subpackage DataModel_Definition
 */
namespace Jet;

class DataModel_Definition_Property_Locale extends DataModel_Definition_Property_Abstract {
	/**
	 * @var string
	 */
	protected $_type = DataModel::TYPE_LOCALE;

	/**
	 * @var null
	 */
	protected $default_value = null;

	/**
	 * @var string
	 */
	protected $form_field_type = "Select";

	/**
	 * @param mixed $value
	 */
	public function checkValueType( &$value ) {
		if(!is_object($value)) {
			$value = new Locale( $value );
		} else {
			if(!$value instanceof  Locale) {
				$value = new Locale();
			}
		}
	}

	/**
	 * Converts property form jsonSerialize
	 *
	 * Locale to string
	 *
	 * @param mixed $property_value
	 * @return string
	 */
	public function getValueForJsonSerialize( $property_value ) {
		if(!$property_value) {
			return $property_value;
		}
		return (string)$property_value;
	}
}