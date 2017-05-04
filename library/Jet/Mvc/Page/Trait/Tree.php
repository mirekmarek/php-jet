<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
trait Mvc_Page_Trait_Tree {

	/**
	 * @var Mvc_Page
	 */
	protected $_parent;

	/**
	 * @var bool
	 */
	protected $_children_sorted = false;

	/**
	 * @var Mvc_Page[]
	 */
	protected $_children = [];


	/**
	 *
	 * @var int
	 */
	protected $order = 0;

	/**
	 * @param Mvc_Page_Interface $_parent
	 */
	public function setParent( Mvc_Page_Interface $_parent ) {
		/**
		 * @var Mvc_Page_Trait_Tree|Mvc_Page $this
		 */
		$this->_parent = $_parent;

		$_parent->appendChild($this);
	}

	/**
	 *
	 * @return Mvc_Page
	 */
	public function getParent() {
		return $this->_parent;
	}

	/**
	 * @param Mvc_Page_Interface $child
	 */
	public function appendChild( Mvc_Page_Interface $child ) {
		/** @noinspection PhpUndefinedFieldInspection */
		$child->_parent = $this;
		$this->_children[$child->getKey()] = $child;
	}

	/**
	 * @return array
	 */
	public function getChildrenIds() {
		$result = [];

		foreach( $this->getChildren() as $page ) {
			$result[] = $page->getId();
		}

		return $result;
	}

	/**
	 * @return array
	 */
	public function getChildrenKeys() {
		$result = [];

		foreach( $this->getChildren() as $page ) {
			$result[] = $page->getKey();
		}

		return $result;
	}

	/**
	 *
	 */
	public function sortChildren() {
		if($this->_children_sorted) {
			return;
		}

		uasort( $this->_children, function(Mvc_Page $a, Mvc_Page $b ) {
			$a_order = $a->getOrder();
			$b_order = $b->getOrder();

			if($a_order==$b_order) {
				return 0;
			}

			return ($a_order < $b_order) ? -1 : 1;
		} );

		$this->_children_sorted = true;
	}

	/**
	 * @return Mvc_Page_Interface[]
	 */
	public function getChildren() {
		$this->sortChildren();

		return $this->_children;
	}


	/**
	 * @return int
	 */
	public function getOrder() {
		return $this->order;
	}

	/**
	 * @param int $order
	 *
	 */
	public function setOrder( $order ) {
		$this->order = (int)$order;
	}

}