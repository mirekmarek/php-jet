<?php
/**
 *
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
 * @subpackage DataModel_Query
 */
namespace Jet;

class DataModel_Query_Where_Expression extends Object {

	/**
	 * Property instance
	 *
	 * @var DataModel_Definition_Property_Abstract
	 */
	protected $property;

	/**
	 * @var string
	 */
	protected $operator = "";

	/**
	 * @var mixed
	 */
	protected $value = "";


	/**
	 * @param DataModel_Definition_Property_Abstract $property
	 * @param string $operator
	 * @param mixed $value
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function  __construct( DataModel_Definition_Property_Abstract $property, $operator, $value  ) {

		$this->property = $property;
		$this->value = $value;
		$this->_setOperator($operator);
	}

	/**
	 * @param $operator
	 *
	 * @throws DataModel_Query_Exception
	 */
	protected function _setOperator($operator) {
		$available_operators = DataModel_Query::$_available_operators;

		if(!in_array($operator, $available_operators)) {
			throw new DataModel_Query_Exception(
				"Unknown operator '{$operator}'. Available operators: '".implode("','", $available_operators)."' ",
				DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
			);

		}

		$this->operator = $operator;

	}

	/**
	 * @return DataModel_Definition_Property_Abstract
	 */
	public function getProperty() {
		return $this->property;
	}

	/**
	 * @return string
	 */
	public function getOperator() {
		return $this->operator;
	}

	/**
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->toString();
	}

	/**
	 *
	 * @return string
	 */
	public function toString() {
		return $this->property->getDataModelDefinition()->getModelName()."::".$this->property->getName()." ".$this->operator." '".$this->value."'";
	}

}