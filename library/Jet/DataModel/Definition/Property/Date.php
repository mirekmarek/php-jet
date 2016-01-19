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
	protected $form_field_type = Form::TYPE_DATE;

	/**
	 * @param Data_DateTime|string $value
	 */
	public function checkValueType( &$value ) {

		if($value==='') {
			$value = null;
		}

		if($value===null) {
			return;
		}

		if(!is_object($value)) {
			$value = new Data_DateTime( $value.' 00:00:00' );
		} else {
			if(!$value instanceof  Data_DateTime) {
				$value = new Data_DateTime();
			}
		}
	}


    /**
     * Converts property form jsonSerialize
     *
     * Example: Locale to string
     *
     * @param DataModel $data_model_instance
     * @param mixed &$property
     *
     * @return mixed
     */
    public function getValueForJsonSerialize( DataModel $data_model_instance, &$property ) {
		/**
		 * @var Data_DateTime $property_value
		 */
		if(!$property) {
			return $property;
		}

		return (string)$property;
	}

}