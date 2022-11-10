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
class DataModel_Query_Select extends BaseObject implements BaseObject_Interface_IteratorCountable
{

	/**
	 * @var DataModel_Query_Select_Item[]
	 */
	protected array $items = [];


	/**
	 *
	 * @param DataModel_Query $query
	 * @param array $items
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function __construct( DataModel_Query $query, array $items = [] )
	{

		foreach( $items as $key => $val ) {
			if( is_string( $val ) ) {
				$val = $query->getPropertyAndSetRelation( $val );
			}

			if( $val instanceof DataModel_Definition_Property ) {
				if( !$val->getCanBeInSelectPartOfQuery() ) {
					continue;
				}

				if( is_string( $key ) ) {
					$select_as = $key;
				} else {
					$select_as = $val->getName();
				}

				$item = new DataModel_Query_Select_Item( $val, $select_as );
				$this->addItem( $item );

				continue;
			}


			if( $val instanceof DataModel_Query_Select_Item_Expression ) {

				$properties = [];
				foreach( $val->getProperties() as $k => $p ) {
					$properties[$k] = $query->getPropertyAndSetRelation( $p );
				}
				$val->setProperties( $properties );

				$select_as = $key;

				$item = new DataModel_Query_Select_Item( $val, $select_as );

				$this->addItem( $item );
				continue;
			}


			throw new DataModel_Query_Exception(
				'I\'m sorry, but I did not understand what you want to define ...',
				DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
			);

		}

	}

	/**
	 *
	 *
	 * @param DataModel_Query_Select_Item $item
	 *
	 * @throws DataModel_Query_Exception
	 *
	 */
	public function addItem( DataModel_Query_Select_Item $item ): void
	{
		$select_as = $item->getSelectAs();

		if( array_key_exists( $select_as, $this->items ) ) {
			throw new DataModel_Query_Exception(
				'Item \'' . $select_as . '\' is already in the list', DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
			);

		}

		$this->items[$select_as] = $item;
	}

	/**
	 * @return bool
	 */
	public function getIsEmpty(): bool
	{
		return (count( $this->items ) == 0);
	}

	/**
	 * @param string $select_as
	 *
	 * @return DataModel_Query_Select_Item
	 */
	public function getItem( string $select_as ): DataModel_Query_Select_Item
	{
		return $this->items[$select_as];
	}

	/**
	 * @param string $select_as
	 *
	 * @return bool
	 */
	public function hasItem( string $select_as ): bool
	{
		return array_key_exists( $select_as, $this->items );
	}



	//------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------------------------------
	/**
	 * @return DataModel_Query_Select_Item
	 * @see \Iterator
	 *
	 */
	public function current(): DataModel_Query_Select_Item
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