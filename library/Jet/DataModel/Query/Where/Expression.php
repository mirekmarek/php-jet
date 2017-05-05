<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Query_Where_Expression
 * @package Jet
 */
class DataModel_Query_Where_Expression extends BaseObject
{

	/**
	 * Property instance
	 *
	 * @var DataModel_Definition_Property_Abstract
	 */
	protected $property;

	/**
	 * @var string
	 */
	protected $operator = '';

	/**
	 * @var mixed
	 */
	protected $value = '';


	/**
	 * @param DataModel_Definition_Property_Abstract $property
	 * @param string                                 $operator
	 * @param mixed                                  $value
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function __construct( DataModel_Definition_Property_Abstract $property, $operator, $value )
	{

		$this->property = $property;
		$this->value = $value;
		$this->_setOperator( $operator );
	}

	/**
	 * @return DataModel_Definition_Property_Abstract
	 */
	public function getProperty()
	{
		return $this->property;
	}

	/**
	 * @return string
	 */
	public function getOperator()
	{
		return $this->operator;
	}

	/**
	 * @param string $operator
	 *
	 * @throws DataModel_Query_Exception
	 */
	protected function _setOperator( $operator )
	{
		$available_operators = DataModel_Query::$_available_operators;

		if( !in_array( $operator, $available_operators ) ) {
			throw new DataModel_Query_Exception(
				'Unknown operator \''.$operator.'\'. Available operators: \''.implode(
					'\',\'', $available_operators
				).'\' ', DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
			);

		}

		$this->operator = $operator;

	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();
	}

	/**
	 *
	 * @return string
	 */
	public function toString()
	{
		$value = $this->value;

		if( is_array( $value ) ) {
			$value = '['.implode( ',', $value ).']';
		}

		return $this->property->getDataModelDefinition()->getModelName().'::'.$this->property->getName(
		).' '.$this->operator.' \''.$value.'\'';
	}

}