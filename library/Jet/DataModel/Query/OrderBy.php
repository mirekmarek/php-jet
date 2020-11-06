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
class DataModel_Query_OrderBy extends BaseObject implements BaseObject_Interface_IteratorCountable
{

	/**
	 * @var DataModel_Query_Where_Expression[]
	 */
	protected $items = [];


	/**
	 *
	 * @param DataModel_Query $query
	 * @param string[]|string $order_by
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function __construct( DataModel_Query $query, $order_by )
	{
		if( !is_array( $order_by ) ) {
			$order_by = [ $order_by ];
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

			if( $ob[0]=='-' ) {
				$desc = true;
			}

			if(
				$ob[0]=='+' ||
				$ob[0]=='-'
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
					'setOrderBy error. Undefined order by property: \''.$ob.'\'',
					DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
				);
			}


			$this->items[] = new DataModel_Query_OrderBy_Item( $property, $desc );

		}
	}

	/**
	 * @return bool
	 */
	public function getIsEmpty()
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
	 */
	public function current()
	{
		return current( $this->items );
	}

	/**
	 * @see \Iterator
	 * @return string
	 */
	public function key()
	{
		return key( $this->items );
	}

	/**
	 * @see \Iterator
	 */
	public function next()
	{
		return next( $this->items );
	}

	/**
	 * @see \Iterator
	 */
	public function rewind()
	{
		reset( $this->items );
	}

	/**
	 * @see \Iterator
	 * @return bool
	 */
	public function valid()
	{
		return key( $this->items )!==null;
	}


	/**
	 * @see \Countable
	 *
	 * @return int
	 */
	public function count()
	{
		return count($this->items);
	}

}