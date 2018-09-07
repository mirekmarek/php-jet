<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
trait Mvc_Page_Trait_Tree
{

	/**
	 * @var string
	 */
	protected $parent_id;

	/**
	 * @var Mvc_Page_Interface
	 */
	protected $__parent;


	/**
	 * @var array
	 */
	protected $children = [];

	/**
	 * @var Mvc_Page[]
	 */
	protected $__children;

	/**
	 *
	 * @var int
	 */
	protected $order = 0;

	/**
	 *
	 * @return Mvc_Page
	 */
	public function getParent()
	{
		/**
		 * @var Mvc_Page_Interface|Mvc_Page_Trait_Tree $this
		 */
		if(!$this->parent_id) {
			return null;
		}

		if(!$this->__parent) {
			/** @noinspection PhpUndefinedMethodInspection */
			$this->__parent = static::get( $this->parent_id, $this->getLocale(), $this->getSite()->getId() );
		}

		return $this->__parent;
	}

	/**
	 * @return array
	 */
	public function getPath()
	{
		$path = [$this->getId()];

		$parent = $this;
		while( ( $parent = $parent->getParent() ) ) {

			array_unshift( $path, $parent->getId() );
		}

		return $path;
	}

	/**
	 * @param Mvc_Page_Interface $parent
	 */
	public function setParent( Mvc_Page_Interface $parent )
	{
		/**
		 * @var Mvc_Page_Trait_Tree|Mvc_Page $this
		 */
		$this->parent_id = $parent->getId();
		$this->__parent = $parent;

		if($parent->getRelativePath()) {
			$this->relative_path = $parent->getRelativePath().'/'.$this->relative_path_fragment;
		} else {
			$this->relative_path = $this->relative_path_fragment;

		}

		/** @noinspection PhpParamsInspection */
		$parent->appendChild( $this );
	}

	/**
	 * @param Mvc_Page_Interface $child
	 */
	public function appendChild( Mvc_Page_Interface $child )
	{
		/**
		 * @var Mvc_Page_Trait_Tree|Mvc_Page $this
		 */

		/** @noinspection PhpUndefinedFieldInspection */
		$child->parent_id = $this->getId();
		/** @noinspection PhpUndefinedFieldInspection */
		$child->__parent = $this;

		$this->children[] = $child->getId();
		$this->__children = null;
	}

	/**
	 * @return array
	 */
	public function getChildrenIds()
	{
		return $this->children;
	}

	/**
	 * @return Mvc_Page_Interface[]
	 */
	public function getChildren()
	{
		/**
		 * @var Mvc_Page_Interface|Mvc_Page_Trait_Tree $this
		 */

		if($this->__children===null) {
			$this->__children = [];

			foreach( $this->children as $id ) {
				/** @noinspection PhpUndefinedMethodInspection */
				$ch = static::get( $id, $this->getLocale(), $this->getSite()->getId() );

				if($ch) {
					$this->__children[$id] = $ch;
				}
			}

			uasort(
				$this->__children,
				function( Mvc_Page $a, Mvc_Page $b ) {
					$a_order = $a->getOrder();
					$b_order = $b->getOrder();

					if( $a_order==$b_order ) {
						return 0;
					}

					return ( $a_order<$b_order ) ? -1 : 1;
				}
			);

		}

		return $this->__children;
	}

	/**
	 * @return int
	 */
	public function getOrder()
	{
		return $this->order;
	}

	/**
	 * @param int $order
	 *
	 */
	public function setOrder( $order )
	{
		$this->order = (int)$order;
	}

	/**
	 * @return array
	 */
	public function getChildrenKeys()
	{
		$result = [];

		foreach( $this->getChildren() as $page ) {
			$result[] = $page->getKey();
		}

		return $result;
	}

}