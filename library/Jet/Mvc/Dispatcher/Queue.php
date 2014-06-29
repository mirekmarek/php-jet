<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Dispatcher
 */
namespace Jet;

class Mvc_Dispatcher_Queue extends Object implements \Iterator {
	/**
	 *
	 * @var Mvc_Dispatcher_Queue_Item[]
	 */
	protected $items = array();

	/**
	 *
	 */
	public function __construct() {
	}

	/**
	 * @see Iterator
	 */
	public function rewind() {
		reset($this->items);
	}

	/**
	 * @see Iterator
	 *
	 * @return Mvc_Dispatcher_Queue_Item
	 */
	public function current() {
		return current($this->items);
	}

	/**
	 * @see Iterator
	 */
	public function key() {
		return key($this->items);
	}

	/**
	 * @see Iterator
	 */
	public function next() {
		return next($this->items);
	}

	/**
	 * @see Iterator
	 * @return bool
	 */
	public function valid() {
		return key($this->items)!==null;
	}

	/**
	 * @param Mvc_Dispatcher_Queue_Item $item
	 */
	public function addItem(  Mvc_Dispatcher_Queue_Item $item  ) {
		$this->items[] = $item;
	}

	/**
	 * @param Mvc_Dispatcher_Queue_Item $new_item
	 */
	public function unShiftItem(  Mvc_Dispatcher_Queue_Item $new_item  ) {
		$current_index = key($this->items);

		$items = array();
		foreach( $this->items as $index=>$item ) {
			$items[] = $item;
			if($index==$current_index) {
				$items[] = $new_item;
			}
		}

		$this->items = $items;

		for( $c=0; $c<$current_index; $c++ ) {
			next( $this->items );
		}
	}

	/**
	 *
	 * @return Mvc_Dispatcher_Queue_Item
	 */
	public function getCurrentItem() {
		if(!$this->valid()) {
			return null;
		}

		return current($this->items);
	}

}