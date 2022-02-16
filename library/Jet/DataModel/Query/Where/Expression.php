<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class DataModel_Query_Where_Expression extends BaseObject
{

	/**
	 *
	 * @var DataModel_Definition_Property|DataModel_Query_Select_Item|null
	 */
	protected DataModel_Definition_Property|DataModel_Query_Select_Item|null $property = null;

	/**
	 * @var string
	 */
	protected string $operator = '';

	/**
	 * @var mixed
	 */
	protected mixed $value = '';


	/**
	 * @param DataModel_Definition_Property $property
	 * @param string $operator
	 * @param mixed $value
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function __construct( DataModel_Definition_Property $property, string $operator, mixed $value )
	{

		$this->property = $property;
		$this->value = $value;
		$this->_setOperator( $operator );
	}

	/**
	 * @return DataModel_Definition_Property|DataModel_Query_Select_Item
	 */
	public function getProperty(): DataModel_Definition_Property|DataModel_Query_Select_Item
	{
		return $this->property;
	}

	/**
	 * @return string
	 */
	public function getOperator(): string
	{
		return $this->operator;
	}

	/**
	 * @param string $operator
	 *
	 * @throws DataModel_Query_Exception
	 */
	protected function _setOperator( string $operator ): void
	{

		if( !in_array( $operator, DataModel_Query::AVAILABLE_OPERATORS ) ) {
			throw new DataModel_Query_Exception(
				'Unknown operator \'' . $operator . '\'. Available operators: \'' . implode( '\',\'', DataModel_Query::AVAILABLE_OPERATORS ) . '\' ',
				DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
			);

		}

		$this->operator = $operator;

	}

	/**
	 * @return mixed
	 */
	public function getValue(): mixed
	{
		return $this->value;
	}

	/**
	 *
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->toString();
	}

	/**
	 *
	 * @return string
	 */
	public function toString(): string
	{
		$value = $this->value;

		if( is_array( $value ) ) {
			$value = '[' . implode( ',', $value ) . ']';
		}

		return $this->property->getDataModelDefinition()->getModelName() . '::' . $this->property->getName() . ' ' . $this->operator . ' \'' . $value . '\'';
	}

}