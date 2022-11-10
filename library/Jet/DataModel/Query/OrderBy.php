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
class DataModel_Query_OrderBy extends BaseObject implements BaseObject_Interface_IteratorCountable
{

	/**
	 * @var DataModel_Query_OrderBy_Item[]
	 */
	protected array $items = [];


	/**
	 *
	 * @param DataModel_Query $query
	 * @param string[]|string $order_by
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function __construct( DataModel_Query $query, array|string $order_by )
	{
		if( !is_array( $order_by ) ) {
			$order_by = [$order_by];
		}

		$select = $query->getSelect();

		if( !$select ) {
			throw new DataModel_Query_Exception(
				'Query SELECT is not defined. Please use $query->setSelect()',
				DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
			);

		}

		$this->items = [];
		foreach( $order_by as $ob ) {
			if( !$ob ) {
				continue;
			}

			$desc = false;

			if( $ob[0] == '-' ) {
				$desc = true;
			}

			if(
				$ob[0] == '+' ||
				$ob[0] == '-'
			) {
				$ob = substr( $ob, 1 );
			}

			$property = null;

			if( !$select->hasItem( $ob ) ) {
				if( strpos( $ob, '.' ) ) {
					$property = $query->getPropertyAndSetRelation( $ob );
				} else {
					$properties = $query->getDataModelDefinition()->getProperties();
					if( isset( $properties[$ob] ) ) {
						$property = $properties[$ob];
					}
				}
			} else {
				$property = $select->getItem( $ob );
			}

			if( !$property ) {
				throw new DataModel_Query_Exception(
					'setOrderBy error. Undefined order by property: \'' . $ob . '\'',
					DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
				);
			}


			$this->items[] = new DataModel_Query_OrderBy_Item( $property, $desc );

		}
	}

	/**
	 * @return bool
	 */
	public function getIsEmpty(): bool
	{
		return (count( $this->items ) == 0);
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
	public function current(): DataModel_Query_OrderBy_Item
	{
		return current( $this->items );
	}

	/**
	 * @return string
	 * @see \Iterator
	 */
	public function key(): string
	{
		return key( $this->items );
	}

	/**
	 * @see \Iterator
	 */
	public function next(): void
	{
		next( $this->items );
	}

	/**
	 * @see \Iterator
	 */
	public function rewind(): void
	{
		reset( $this->items );
	}

	/**
	 * @return bool
	 * @see \Iterator
	 */
	public function valid(): bool
	{
		return key( $this->items ) !== null;
	}


	/**
	 * @return int
	 * @see \Countable
	 *
	 */
	public function count(): int
	{
		return count( $this->items );
	}

}