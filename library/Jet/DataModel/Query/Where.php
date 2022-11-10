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
class DataModel_Query_Where extends BaseObject implements BaseObject_Interface_IteratorCountable
{
	use DataModel_Query_Where_Trait;

	/**
	 * @var DataModel_Query_Where_Expression[]|DataModel_Query_Where[]|string
	 */
	protected array $expressions = [];


	/**
	 *
	 * @param DataModel_Query $query
	 * @param array $where
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function __construct( DataModel_Query $query, array $where = [] )
	{
		$this->query = $query;

		foreach( $where as $key => $val ) {
			if( is_int( $key ) ) {
				$this->_determineLogicalOperatorOrSubExpressions( $val );

				continue;
			}


			$operator = $this->_determineOperator( $key );

			$property = $query->getPropertyAndSetRelation( $key );

			$this->addExpression( $property, $operator, $val );
		}
	}

	/**
	 *
	 * @param DataModel_Definition_Property $property_definition
	 * @param string $operator (DataModel_Query::O_OR,  DataModel_Query::O_AND, ... )
	 * @param mixed $value
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function addExpression( DataModel_Definition_Property $property_definition, string $operator, mixed $value ): void
	{
		if( $this->expressions ) {
			$previous = $this->expressions[count( $this->expressions ) - 1];

			if(
				$previous !== DataModel_Query::L_O_AND &&
				$previous !== DataModel_Query::L_O_OR
			) {

				throw new DataModel_Query_Exception(
					'Previous part of the query must be AND or OR. ' . $previous . ' given. Current where dump:' . $this->toString(), DataModel_Query_Exception::CODE_QUERY_NONSENSE
				);
			}
		}

		$this->expressions[] = new DataModel_Query_Where_Expression( $property_definition, $operator, $value );
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		$result = [];

		foreach( $this as $expression ) {
			if( is_object( $expression ) ) {
				$result[] = $expression->toString();
			} else {
				$result[] = (string)$expression;
			}
		}

		return '( ' . implode( ' ', $result ) . ' )';
	}

	/**
	 * @return bool
	 */
	public function getIsEmpty(): bool
	{
		return (count( $this->expressions ) == 0);
	}

	/**
	 *
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function addAND(): void
	{
		if( !$this->expressions ) {
			return;
		}

		$previous = $this->expressions[count( $this->expressions ) - 1];

		if(
			$previous === DataModel_Query::L_O_AND ||
			$previous === DataModel_Query::L_O_OR
		) {
			throw new DataModel_Query_Exception(
				'Previous part of the query must be Expression. ' . $previous . ' given. Current where dump:' . $this->toString(), DataModel_Query_Exception::CODE_QUERY_NONSENSE
			);
		}

		$this->expressions[] = DataModel_Query::L_O_AND;
	}

	/**
	 *
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function addOR(): void
	{
		if( !$this->expressions ) {
			return;
		}

		$previous = $this->expressions[count( $this->expressions ) - 1];

		if(
			$previous === DataModel_Query::L_O_AND ||
			$previous === DataModel_Query::L_O_OR
		) {
			throw new DataModel_Query_Exception(
				'Previous part of the query must be Expression. ' . $previous . ' given. Current where dump:' . $this->toString(), DataModel_Query_Exception::CODE_QUERY_NONSENSE
			);
		}

		$this->expressions[] = DataModel_Query::L_O_OR;
	}

	/**
	 * @param DataModel_Query_Where $sub_expressions
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function addSubExpressions( DataModel_Query_Where $sub_expressions ): void
	{
		if( $this->expressions ) {
			$previous = $this->expressions[count( $this->expressions ) - 1];

			if(
				$previous !== DataModel_Query::L_O_AND &&
				$previous !== DataModel_Query::L_O_OR
			) {
				throw new DataModel_Query_Exception(
					'Previous part of the query must be Expression. ' . $previous . ' given. Current where dump:' . $this->toString(), DataModel_Query_Exception::CODE_QUERY_NONSENSE
				);
			}
		}

		$this->expressions[] = $sub_expressions;
	}

	/**
	 * @param DataModel_Query_Where $part
	 */
	public function attach( DataModel_Query_Where $part ): void
	{
		if( $this->expressions ) {
			$this->expressions[] = DataModel_Query::L_O_AND;
		}

		foreach( $part as $qp ) {
			$this->expressions[] = $qp;
		}
	}

	//------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------------------------------

	/**
	 * @see \Iterator
	 */
	public function current(): DataModel_Query_Where_Expression|DataModel_Query_Where|string
	{
		return current( $this->expressions );
	}

	/**
	 * @return string
	 * @see \Iterator
	 */
	public function key(): string
	{
		return key( $this->expressions );
	}

	/**
	 * @see \Iterator
	 */
	public function next(): void
	{
		next( $this->expressions );
	}

	/**
	 * @see \Iterator
	 */
	public function rewind(): void
	{
		reset( $this->expressions );
	}

	/**
	 * @return bool
	 * @see \Iterator
	 */
	public function valid(): bool
	{
		return key( $this->expressions ) !== null;
	}


	/**
	 * @return int
	 * @see \Countable
	 *
	 */
	public function count(): int
	{
		return count( $this->expressions );
	}

}