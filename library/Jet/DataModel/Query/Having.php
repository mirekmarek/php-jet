<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Query
 */
namespace Jet;

class DataModel_Query_Having extends BaseObject implements \Iterator{
	use DataModel_Query_Where_Trait;

	/**
	 * @var DataModel_Query_Having_Expression[]
	 */
	protected $expressions = [];

	/**
	 *
	 * @param DataModel_Query $query
	 * @param array $having
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function __construct( DataModel_Query $query, array $having= []) {
		$this->query = $query;


		foreach( $having as $key=>$val ) {
			if(is_int($key)) {
				$this->_determineLogicalOperatorOrSubExpressions($val);

				continue;
			}


			$operator = $this->_determineOperator($key);

			if(!$query->getSelect()->getHasItem($key)) {
				throw new DataModel_Query_Exception(
					'There is not item \''.$key.'\' in the query select items list! In the having clause can only use items that are defined in the select',
					DataModel_Query_Exception::CODE_QUERY_NONSENSE
				);
			}

			$property = $query->getSelect()->getItem($key);

			$this->addExpression( $property, $operator, $val);
		}
	}

	/**
	 * @return bool
	 */
	public function getIsEmpty() {
		return (count($this->expressions)==0);
	}


	/**
	 *
	 * @param DataModel_Query_Select_Item $property_definition
	 * @param string $operator (DataModel_Query::O_OR,  DataModel_Query::O_AND, ... )
	 * @param mixed $value
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function addExpression( DataModel_Query_Select_Item $property_definition, $operator, $value  ) {
		if($this->expressions) {
			$previous = $this->expressions[count($this->expressions)-1];

			if(
				$previous!==DataModel_Query::L_O_AND &&
				$previous!==DataModel_Query::L_O_OR
			) {

				throw new DataModel_Query_Exception(
					'Previous part of the query must be AND or OR. '.$previous.' given. Current having dump:'.$this->toString(),
					DataModel_Query_Exception::CODE_QUERY_NONSENSE
				);
			}
		}

		$this->expressions[] = new DataModel_Query_Having_Expression( $property_definition, $operator, $value);
	}


	/**
	 *
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function addAND() {
		//for easier implementation of the query ... (often is associated with multiple conditions in the cycle)
		if(!$this->expressions) {
			return;
		}

		$previous = $this->expressions[count($this->expressions)-1];

		if( $previous===DataModel_Query::L_O_AND || $previous===DataModel_Query::L_O_OR ) {
			throw new DataModel_Query_Exception(
				'Previous part of the query must be Expression. '.$previous.' given. Current having dump:'.$this->toString(),
				DataModel_Query_Exception::CODE_QUERY_NONSENSE
			);
		}

		$this->expressions[] = DataModel_Query::L_O_AND;
	}

	/**
	 *
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function addOR() {
		//for easier implementation of the query ... (often is associated with multiple conditions in the cycle)
		if(!$this->expressions) {
			return;
		}

		$previous = $this->expressions[count($this->expressions)-1];

		if( $previous===DataModel_Query::L_O_AND || $previous===DataModel_Query::L_O_OR ) {
			throw new DataModel_Query_Exception(
				'Previous part of the query must be Expression. '.$previous.' given. Current having dump:'.$this->toString(),
				DataModel_Query_Exception::CODE_QUERY_NONSENSE
			);
		}

		$this->expressions[] = DataModel_Query::L_O_OR;
	}

	/**
	 * @param DataModel_Query_Having $sub_expressions
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function addSubExpressions( DataModel_Query_Having $sub_expressions ) {
		if($this->expressions) {
			$previous = $this->expressions[count($this->expressions)-1];

			if( $previous!==DataModel_Query::L_O_AND && $previous!==DataModel_Query::L_O_OR ) {
				throw new DataModel_Query_Exception(
					'Previous part of the query must be Expression. '.$previous.' given. Current having dump:'.$this->toString(),
					DataModel_Query_Exception::CODE_QUERY_NONSENSE
				);
			}
		}

		$this->expressions[] = $sub_expressions;
	}

	/**
	 * @param DataModel_Query_Having $part
	 */
	public function attach( DataModel_Query_Having $part ) {
		if($this->expressions) {
			$this->expressions[] = DataModel_Query::L_O_AND;
		}

		foreach( $part as $qp ) {
			$this->expressions[] = $qp;
		}
	}

	/**
	 * @return string
	 */
	public function toString() {
		$result = [];

		foreach($this as $expression) {
			if(is_object($expression)) {
				$result[] = $expression->toString();
			} else {
				$result[] = (string)$expression;
			}
		}

		return '( '.implode(' ', $result).' )';
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
	 *
	 * @return DataModel_Query_Having_Expression
	 */
	public function current() {
		return current($this->expressions);
	}
	/**
	 * @see \Iterator
	 * @return string
	 */
	public function key() {
		return key($this->expressions);
	}
	/**
	 * @see \Iterator
	 */
	public function next() {
		return next($this->expressions);
	}
	/**
	 * @see \Iterator
	 */
	public function rewind() {
		reset($this->expressions);
	}
	/**
	 * @see \Iterator
	 * @return bool
	 */
	public function valid()	{
		return key($this->expressions)!==null;
	}
}