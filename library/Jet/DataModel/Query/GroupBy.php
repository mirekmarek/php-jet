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
class DataModel_Query_GroupBy extends BaseObject implements BaseObject_Interface_IteratorCountable
{

	/**
	 * @var DataModel_Query_Select_Item[]|DataModel_Definition_Property[]
	 */
	protected array $items = [];


	/**
	 *
	 * @param DataModel_Query $query
	 * @param string[]|string $group_by
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function __construct( DataModel_Query $query, array|string $group_by )
	{
		if( !is_array( $group_by ) ) {
			$group_by = [$group_by];
		}

		$select = $query->getSelect();
		$this->items = [];
		foreach( $group_by as $gb ) {

			$property = null;

			if( !$select->hasItem( $gb ) ) {
				if( strpos( $gb, '.' ) ) {
					$property = $query->getPropertyAndSetRelation( $gb );
				} else {
					$properties = $query->getDataModelDefinition()->getProperties();
					if( isset( $properties[$gb] ) ) {
						$property = $properties[$gb];
					}
				}
			} else {
				$property = $select->getItem( $gb );
			}

			if( !$property ) {
				throw new DataModel_Query_Exception(
					'setGroupBy error. Undefined group by property: \'' . $gb . '\'',
					DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
				);
			}

			$this->items[] = $property;
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
	 * @return DataModel_Query_Select_Item|DataModel_Definition_Property
	 * @see \Iterator
	 */
	public function current(): DataModel_Query_Select_Item|DataModel_Definition_Property
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