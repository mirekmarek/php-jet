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
trait MVC_Page_Trait_Tree
{

	/**
	 * @var string|null
	 */
	protected string|null $parent_id = null;

	/**
	 * @var ?MVC_Page_Interface
	 */
	protected ?MVC_Page_Interface $__parent = null;


	/**
	 * @var array
	 */
	protected array $children = [];

	/**
	 * @var MVC_Page[]|null
	 */
	protected array|null $__children = null;

	/**
	 *
	 * @var int
	 */
	protected int $order = 0;

	public function setParentId( string $page_id ) : void
	{
		$this->parent_id = $page_id;
	}

	/**
	 *
	 * @return static|null
	 */
	public function getParent(): static|null
	{
		/**
		 * @var MVC_Page_Interface|MVC_Page_Trait_Tree $this
		 */
		if( !$this->parent_id ) {
			return null;
		}

		if( !$this->__parent ) {
			$this->__parent = static::_get( $this->parent_id, $this->getLocale(), $this->getBase()->getId() );
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
	 * @return MVC_Page_Interface[]
	 */
	public function getChildren(): array
	{
		/**
		 * @var MVC_Page_Interface|MVC_Page_Trait_Tree $this
		 */

		if( $this->__children === null ) {
			$this->__children = [];

			foreach( $this->children as $id ) {
				$ch = static::_get( $id, $this->getLocale(), $this->getBase()->getId() );

				if( $ch ) {
					$this->__children[$id] = $ch;
				}
			}

			uasort(
				$this->__children,
				function( MVC_Page $a, MVC_Page $b ) {
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

	/**
	 * @return array
	 */
	public function getChildrenIds(): array
	{
		return $this->children;
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
		$this->order = $order;
	}

}