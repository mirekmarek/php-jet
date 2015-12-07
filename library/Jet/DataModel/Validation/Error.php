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
 * @subpackage DataModel_Validation
 */
namespace Jet;

class DataModel_Validation_Error extends Object {
	const CODE_REQUIRED = 'required';
	const CODE_INVALID_VALUE = 'invalid_value';
	const CODE_INVALID_FORMAT = 'invalid_format';
	const CODE_OUT_OF_RANGE = 'out_of_range';


	/**
	 *
	 * @var int
	 */
	protected $code = 0;

	/**
	 *
	 * @var string
	 */
	protected $message = '';

	/**
	 *
	 * @var DataModel_Definition_Property_Abstract
	 */
	protected $property = '';

	/**
	 *
	 * @var mixed
	 */
	protected $property_value = null;


	/**
	 *
	 * @param int $code
	 * @param DataModel_Definition_Property_Abstract $property
	 * @param mixed $property_value
	 */
	public function  __construct( $code,  DataModel_Definition_Property_Abstract $property, $property_value ) {
		$this->code = $code;
		$this->property = $property;
		$this->property_value = $property_value;

		$this->message = $property->getErrorMessage($code);

	}

	/**
	 * @return int
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * @return DataModel_Definition_Property_Abstract
	 */
	public function getProperty() {
		return $this->property;
	}

	/**
	 * @return mixed
	 */
	public function getPropertyValue() {
		return $this->property_value;
	}

	/**
	 * @return string
	 */
	public function toString() {
		/**
		 * @var DataModel_Definition_Model_Abstract $model_definition
		 */
		$model_definition = $this->property->getDataModelDefinition();
		return $model_definition->getClassName().'::'.$this->property->getName().' : ('.$this->code.') '.$this->message;
	}

	/**
	 * @return string
	 */
	public function  __toString() {
		return $this->toString();
	}

}