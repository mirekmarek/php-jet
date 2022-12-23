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
trait DataModel_Query_Where_Trait
{

	/**
	 * @var ?DataModel_Query
	 */
	protected ?DataModel_Query $query = null;


	/**
	 * @param string|array $val
	 *
	 * @throws DataModel_Query_Exception
	 */
	protected function _determineLogicalOperatorOrSubExpressions( string|array $val ) : void
	{
		if( is_array( $val ) ) {
			$this->addSubExpressions( new self( $this->query, $val ) );
		} else {
			switch( strtoupper( $val ) ) {
				case DataModel_Query::L_O_AND:
					$this->addAND();
					break;
				case DataModel_Query::L_O_OR:
					$this->addOR();
					break;
				default:
					throw new DataModel_Query_Exception(
						'Unknown logical operator \'' . strtoupper( $val ) . '\'. Available operators: AND, OR',
						DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
					);
			}

		}

	}

	/**
	 * @param string &$key
	 *
	 * @return string
	 */
	protected function _determineOperator( string &$key ): string
	{
		$operator = DataModel_Query::O_EQUAL;
		$key = trim( $key );

		foreach( DataModel_Query::AVAILABLE_OPERATORS as $s_operator ) {
			$len = strlen( $s_operator );
			if( substr( $key, -$len ) == $s_operator ) {
				$operator = $s_operator;
				$key = trim( substr( $key, 0, -$len ) );
				break;
			}
		}

		return $operator;
	}

}