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

class DataModel_Validation_Exception extends Exception {
	const CODE_PROPERTY_VALIDATION_ERROR = 1;

	/**
	 * @var DataModel $data_model_instance
	 */
	protected $data_model_instance;

	/**
	 * @var DataModel_Definition_Property_Abstract $property_definition_instance
	 */
	protected $property_definition_instance;

	/**
	 * @var DataModel_Validation_Error[]
	 */
	protected $errors = array();

	public function __construct( DataModel $data_model_instance, DataModel_Definition_Property_Abstract $property_definition_instance, array $errors ) {
		$this->code = static::CODE_PROPERTY_VALIDATION_ERROR;
		$this->message = "Value is not valid";

		$this->data_model_instance = $data_model_instance;
		$this->property_definition_instance = $property_definition_instance;

		foreach( $this->errors as $error ) {
			$this->addError($error);
		}
	}

	/**
	 * @param DataModel_Validation_Error $error
	 */
	protected function addError( DataModel_Validation_Error $error ) {
		$this->errors[] = $error;
	}

	/**
	 * @return \Jet\DataModel
	 */
	public function getDataModelInstance() {
		return $this->data_model_instance;
	}

	public function getErrors() {
		return $this->errors;
	}

	/**
	 * @return \Jet\DataModel_Definition_Property_Abstract
	 */
	public function getPropertyDefinitionInstance() {
		return $this->property_definition_instance;
	}


}