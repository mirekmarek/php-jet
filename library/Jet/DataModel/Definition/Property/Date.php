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
 * @package DataModel
 * @subpackage DataModel_Definition
 */
namespace Jet;

class DataModel_Definition_Property_Date extends DataModel_Definition_Property_Abstract {
	/**
	 * @var string
	 */
	protected $_type = DataModel::TYPE_DATE;

	/**
	 * @var null
	 */
	protected $default_value = null;

	/**
	 * @var string
	 */
	protected $form_field_type = "Date";

	/**
	 * @param DateTime|string $value
	 */
	public function checkValueType( &$value ) {

		if($value==="") {
			$value = null;
		}

		if($value===null) {
			return;
		}

		if(!is_object($value)) {
			$value = new DateTime( $value." 00:00:00" );
		} else {
			if(!$value instanceof  DateTime) {
				$value = new DateTime();
			}
		}
	}


	/**
	 * Converts property form jsonSerialize
	 *
	 * DateTime to string
	 *
	 * @param DateTime $property_value
	 * @return mixed
	 */
	public function getValueForJsonSerialize( $property_value ) {
		/**
		 * @var DateTime $property_value
		 */
		if(!$property_value) {
			return $property_value;
		}

		return (string)$property_value;
	}

}