<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	 * @var string|null
	 */
	protected string|null $parent_id = null;

	/**
	 * @var ?Mvc_Page_Interface
	 */
	protected ?Mvc_Page_Interface $__parent = null;


	/**
	 * @var array
	 */
	protected array $children = [];

	/**
	 * @var Mvc_Page[]|null
	 */
	protected array|null $__children = null;

	/**
	 *
	 * @var int
	 */
	protected int $order = 0;

	/**
	 *
	 * @return static|null
	 */
	public function getParent(): static|null
	{
		/**
		 * @var Mvc_Page_Interface|Mvc_Page_Trait_Tree $this
		 */
		if( !$this->parent_id ) {
			return null;
		}

		if( !$this->__parent ) {
			$this->__parent = static::get( $this->parent_id, $this->getLocale(), $this->getSite()->getId() );
		}

		return $this->__parent;
	}

	/**
	 * @return array
	 */
	public function getPath(): array
	{
		$path = [$this->getId()];

		$parent = $this;
		while( ($parent = $parent->getParent()) ) {

			array_unshift( $path, $parent->getId() );
		}

		return $path;
	}


	/**
	 * @return array
	 */
	public function getChildrenIds(): array
	{
		return $this->children;
	}

	/**
	 * @return Mvc_Page_Interface[]
	 */
	public function getChildren(): array
	{
		/**
		 * @var Mvc_Page_Interface|Mvc_Page_Trait_Tree $this
		 */

		if( $this->__children === null ) {
			$this->__children = [];

			foreach( $this->children as $id ) {
				$ch = static::get( $id, $this->getLocale(), $this->getSite()->getId() );

				if( $ch ) {
					$this->__children[$id] = $ch;
				}
			}

			uasort(
				$this->__children,
				function( Mvc_Page $a, Mvc_Page $b ) {
					$a_order = $a->getOrder();
					$b_order = $b->getOrder();

					if( $a_order == $b_order ) {
						return 0;
					}

					return ($a_order < $b_order) ? -1 : 1;
				}
			);

		}

		return $this->__children;
	}

	/**
	 * @return int
	 */
	public function getOrder(): int
	{
		return $this->order;
	}

	/**
	 * @param int $order
	 *
	 */
	public function setOrder( int $order ): void
	{
		$this->order = (int)$order;
	}

	/**
	 * @return array
	 */
	public function getChildrenKeys(): array
	{
		$result = [];

		foreach( $this->getChildren() as $page ) {
			$result[] = $page->getKey();
		}

		return $result;
	}

}