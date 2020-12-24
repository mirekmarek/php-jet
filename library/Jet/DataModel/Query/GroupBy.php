<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
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
			$group_by = [ $group_by ];
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
					'setGroupBy error. Undefined group by property: \''.$gb.'\'',
					DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
				);
			}

			$this->items[] = $property;
		}
	}

	/**
	 * @return bool
	 */
	public function getIsEmpty() : bool
	{
		return ( count( $this->items )==0 );
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
	 * @return DataModel_Query_Select_Item|DataModel_Definition_Property
	 */
	public function current() : DataModel_Query_Select_Item|DataModel_Definition_Property
	{
		return current( $this->items );
	}

	/**
	 * @see \Iterator
	 * @return string
	 */
	public function key() : string
	{
		return key( $this->items );
	}

	/**
	 * @see \Iterator
	 */
	public function next() : mixed
	{
		return next( $this->items );
	}

	/**
	 * @see \Iterator
	 */
	public function rewind() : void
	{
		reset( $this->items );
	}

	/**
	 * @see \Iterator
	 * @return bool
	 */
	public function valid() : bool
	{
		return key( $this->items )!==null;
	}


	/**
	 * @see \Countable
	 *
	 * @return int
	 */
	public function count() : int
	{
		return count( $this->items );
	}

}