<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
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
	 * @var Mvc_Dispatcher_Queue_Item
	 */
	protected $current_item = null;

	/**
	 *
	 */
	public function __construct() {
	}

	/**
	 * @see Iterator
	 */
	public function rewind() {
	}

	/**
	 * @see Iterator
	 *
	 * @return Mvc_Dispatcher_Queue_Item
	 */
	public function current() {
		return $this->current_item;
	}

	/**
	 * @see Iterator
	 */
	public function key() {
		return 0;
	}

	/**
	 * @see Iterator
	 */
	public function next() {
		if(!$this->items) {
			$this->current_item = null;
		} else {
			$this->current_item = array_shift( $this->items );
		}
	}

	/**
	 * @see Iterator
	 * @return bool
	 */
	public function valid() {
		return $this->current_item !== null;
	}

	/**
	 * @param Mvc_Dispatcher_Queue_Item $item
	 */
	public function addItem(  Mvc_Dispatcher_Queue_Item $item  ) {
		if(!$this->current_item) {
			$this->current_item = $item;
		} else {
			$this->items[] = $item;
		}
	}

	/**
	 * @param Mvc_Dispatcher_Queue_Item $item
	 */
	public function unShiftItem(  Mvc_Dispatcher_Queue_Item $item  ) {
		if(!$this->current_item) {
			$this->current_item = $item;
		} else {
			array_unshift($this->items, $item);
		}
	}

	/**
	 *
	 * @return Mvc_Dispatcher_Queue_Item
	 */
	public function getCurrentItem() {
		return $this->current_item;
	}

}