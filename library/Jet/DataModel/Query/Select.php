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

class DataModel_Query_Select extends BaseObject implements \Iterator {

	/**
	 * @var DataModel_Query_Select_Item[]
	 */
	protected $items = [];


	/**
	 *
	 * @param DataModel_Query $query
	 * @param array $items
	 *
	 * @throws DataModel_Query_Exception
	 */
	public function __construct( DataModel_Query $query, array $items= []) {

		foreach( $items as $key=>$val ) {
			if(is_string($val) && strpos($val, '.')) {
				$val = $query->getPropertyAndSetRelation( $val );
			}

			if($val instanceof DataModel_Definition_Property_Abstract) {
				if( !$val->getCanBeInSelectPartOfQuery() ) {
					continue;
				}

				if(is_string($key)) {
					$select_as = $key;
				} else {
					$select_as = $val->getName();
				}

				$item = new DataModel_Query_Select_Item($val, $select_as);
				$this->addItem($item);

				continue;
			}

			if(is_array($val)) {
				if(!isset($val[1])) {
					throw new DataModel_Query_Exception(
						'Invalid backend function call specification. Example: array( array(\'this.prop_a\', \'this.prop_b\'), \'SUM(%prop_a%)+%prop_b%\'  )',
						DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
					);

				}

				$property_names = $val[0];
				if(!is_array($property_names)) {
					$property_names = [$property_names];
				}

				$properties = [];
				foreach($property_names as $property_name) {
					if($property_name instanceof DataModel_Definition_Property_Abstract) {
						$properties[] = $property_name;
					} else {
						$properties[] = $query->getPropertyAndSetRelation( $property_name );
					}
				}

				$val = new DataModel_Query_Select_Item_BackendFunctionCall($properties, $val[1]);

			}


			if( $val instanceof DataModel_Query_Select_Item_BackendFunctionCall ) {
				if(!is_string($key)) {
					throw new DataModel_Query_Exception(
						'The item is DataModel_Query_Select_Item_BackendFunctionCall. So the key must be string. Example: Special item is \'sum(something) as total_sum\' and then the array key is \'total_sum\'.',
						DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
					);
				}

				$select_as = $key;

				$item = new DataModel_Query_Select_Item($val, $select_as);

				$this->addItem($item);
				continue;
			}


			throw new DataModel_Query_Exception(
				'I\'m sorry, but I did not understand what you want to define ...',
				DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
			);

		}

	}

	/**
	 * @return bool
	 */
	public function getIsEmpty() {
		return (count($this->items)==0);
	}

	/**
	 *
	 *
	 * @param DataModel_Query_Select_Item|DataModel_Query_Select_Item $item
	 *
	 * @throws DataModel_Query_Exception
	 *
	 */
	public function addItem( DataModel_Query_Select_Item $item ) {
		$select_as = $item->getSelectAs();

		if(array_key_exists($select_as, $this->items)) {
			throw new DataModel_Query_Exception(
				'Item \''.$select_as.'\' is already in the list',
				DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
			);

		}

		$this->items[$select_as] = $item;
	}

	/**
	 * @param string $select_as
	 *
	 * @return DataModel_Query_Select_Item
	 */
	public function getItem( $select_as ) {
		return $this->items[$select_as];
	}

	/**
	 * @param string $select_as
	 *
	 * @return bool
	 */
	public function getHasItem( $select_as ) {
		return array_key_exists($select_as, $this->items);
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
	 * @return DataModel_Query_Select_Item
	 */
	public function current() {
		return current($this->items);
	}
	/**
	 * @see \Iterator
	 * @return string
	 */
	public function key() {
		return key($this->items);
	}
	/**
	 * @see \Iterator
	 */
	public function next() {
		return next($this->items);
	}
	/**
	 * @see \Iterator
	 */
	public function rewind() {
		reset($this->items);
	}
	/**
	 * @see \Iterator
	 * @return bool
	 */
	public function valid()	{
		return key($this->items)!==null;
	}
}