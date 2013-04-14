<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Query
 */
namespace Jet;

class DataModel_Query_OrderBy extends Object implements \Iterator {

	/**
	 * @var DataModel_Query_Where_Expression[]
	 */
	protected $items = array();


	/**
	 *
	 * @param DataModel_Query $query
	 * @param string[]|string $order_by
	 *
	 * @throws DataModel_Query_Exception
	 * @return \Jet\DataModel_Query_OrderBy
	 */
	public function __construct( DataModel_Query $query, $order_by ) {
		if(!is_array($order_by)) {
			$order_by = array($order_by);
		}

		$select = $query->getSelect();

		$this->items = array();
		foreach($order_by as $ob) {
			if(!$ob) {
				continue;
			}

			$desc = false;

			if($ob[0]=="-") {
				$desc = true;
			}

			if(
				$ob[0]=="+" ||
				$ob[0]=="-"
			) {
				$ob = substr($ob, 1);
			}

			$property = null;

			if(!$select->getHasItem($ob)) {
				if(strpos($ob, ".")) {
					$property = $query->_getPropertyAndSetRelation($ob);
				} else {
					$properties = $query->getMainDataModel()->getDataModelDefinition()->getProperties();
					if(isset($properties[$ob])) {
						$property = $properties[$ob];
					}
				}
			} else {
				$property = $select->getItem($ob);
			}

			if(!$property) {
				throw new DataModel_Query_Exception(
					"setOrderBy error. Undefined order by property: '{$ob}'",
					DataModel_Query_Exception::CODE_QUERY_PARSE_ERROR
				);
			}



			$this->items[] = new DataModel_Query_OrderBy_Item( $property, $desc );

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