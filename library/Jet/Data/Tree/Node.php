<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;


use JsonSerializable;

/**
 *
 */
class Data_Tree_Node extends BaseObject implements BaseObject_Interface_IteratorCountable, BaseObject_Interface_Serializable_JSON, Form_Field_Select_Option_Interface
{
	use Form_Field_Select_Option_Trait;
	
	/**
	 *
	 * @var ?Data_Tree
	 */
	protected ?Data_Tree $_tree = null;

	/**
	 *
	 * @var string
	 */
	protected string $id = '';

	/**
	 *
	 * @var ?string
	 */
	protected ?string $parent_id = null;
	/**
	 *
	 * @var ?string
	 */
	protected ?string $real_parent_id = null;

	/**
	 *
	 * @var ?Data_Tree_Node
	 */
	protected ?Data_Tree_Node $_parent = null;

	/**
	 * @var ?string
	 */
	protected ?string $label = null;

	/**
	 * Node data
	 *
	 * @var mixed
	 */
	protected mixed $data;


	/**
	 *
	 * @var Data_Tree_Node[]
	 */
	protected array $_children = [];

	/**
	 *
	 * @var int
	 */
	protected int $depth = 0;

	/**
	 *
	 * @var bool
	 */
	protected bool $is_root = false;

	/**
	 * @var bool
	 */
	protected bool $is_orphan = false;

	/**
	 *
	 * @var Data_Tree_Node[]
	 */
	protected array $_iterator_map = [];

	/**
	 * @var ?int
	 */
	protected ?int $_max_depth = null;

	/**
	 * @var ?array
	 */
	protected ?array $_all_children_ids = null;

	/**
	 *
	 * @param Data_Tree $tree
	 * @param mixed $data
	 */
	public function __construct( Data_Tree $tree, mixed $data )
	{
		$this->_tree = $tree;
		$this->data = $data;

	}

	/**
	 * @param Data_Tree_Node $node
	 *
	 * @throws Data_Tree_Exception
	 *
	 */
	public function _appendChild( Data_Tree_Node $node ): void
	{

		$id = $node->getId();

		if( isset( $this->_children[$id] ) ) {
			throw new Data_Tree_Exception(
				'Child \'' . $id . '\' already exists!', Data_Tree_Exception::CODE_NODE_ALREADY_EXISTS
			);
		}

		$this->_children[$id] = $node;
	}

	/**
	 *
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
	}

	/**
	 * @param string $id
	 */
	public function setId( string $id )
	{
		$this->id = $id;
	}

	/**
	 *
	 * @return Data_Tree
	 */
	public function getTree(): Data_Tree
	{
		return $this->_tree;
	}

	/**
	 *
	 * @return string
	 */
	public function getParentId(): string
	{
		return $this->parent_id;
	}

	/**
	 * @param string $parent_id
	 */
	public function _setParentId( string $parent_id ): void
	{
		$this->real_parent_id = $parent_id;
		$this->parent_id = $parent_id;
	}

	/**
	 * @return string
	 */
	public function getRealParentId(): string
	{
		return $this->real_parent_id;
	}

	/**
	 *
	 * @return Data_Tree_Node|null
	 */
	public function getParent(): Data_Tree_Node|null
	{
		return $this->_parent;
	}

	/**
	 * @param Data_Tree_Node $_parent
	 */
	public function _setParent( Data_Tree_Node $_parent ): void
	{
		$this->_parent = $_parent;
		$this->parent_id = $_parent->getId();
		if( !$this->is_orphan ) {
			$this->real_parent_id = $_parent->getId();
		}

		$this->depth = $_parent->getDepth() + 1;
	}

	/**
	 *
	 * @return int
	 */
	public function getDepth(): int
	{
		return $this->depth;
	}

	/**
	 *
	 * @return bool
	 */
	public function getIsRoot(): bool
	{
		return $this->is_root;
	}

	/**
	 * @param bool $is_root
	 */
	public function _setIsRoot( bool $is_root ): void
	{
		$this->is_root = $is_root;
	}

	/**
	 * @return bool
	 */
	public function getIsOrphan(): bool
	{
		return $this->is_orphan;
	}

	/**
	 * @param bool $is_orphan
	 */
	public function _setIsOrphan( bool $is_orphan ): void
	{
		$this->is_orphan = $is_orphan;
	}

	/**
	 *
	 * @return mixed
	 */
	public function getData(): mixed
	{
		return $this->data;
	}

	/**
	 * @param mixed $data
	 */
	public function setData( mixed $data )
	{
		$this->data = $data;
	}

	/**
	 *
	 * @return bool
	 */
	public function getHasChildren(): bool
	{
		return (bool)$this->_children;
	}

	/**
	 *
	 * @return Data_Tree_Node[]
	 */
	public function getChildren(): array
	{
		return $this->_children;
	}

	/**
	 * @return array
	 */
	public function getAllChildrenIds(): array
	{
		if( $this->_all_children_ids === null ) {
			$this->_all_children_ids = [];

			foreach( $this->_children as $ch ) {
				$this->_all_children_ids[] = $ch->getId();
				$this->_all_children_ids = array_merge( $this->_all_children_ids, $ch->getAllChildrenIds() );
			}
		}

		return $this->_all_children_ids;
	}

	/**
	 *
	 * @param string $id
	 *
	 * @return bool
	 */
	public function getChildExists( string $id ): bool
	{
		return isset( $this->_children[$id] );
	}


	/**
	 *
	 * @param string $child_id
	 *
	 * @return Data_Tree_Node
	 */
	public function getChild( string $child_id ): Data_Tree_Node
	{
		return $this->_children[$child_id];
	}


	/**
	 *
	 * @return Data_Tree_Node[]
	 */
	public function getPathToRoot(): array
	{
		$result = [];

		$result[$this->id] = $this;
		$_node = $this;

		while( $_node->_parent ) {
			$_node = $_node->_parent;
			$result[$_node->id] = $_node;
		}

		return $result;
	}

	/**
	 *
	 * @return Data_Tree_Node[]
	 */
	public function getPathFromRoot(): array
	{
		return array_reverse( $this->getPathToRoot() );
	}

	/**
	 *
	 * @return Data_Tree_Node[]
	 */
	public function getPath(): array
	{
		return array_reverse( $this->getPathToRoot() );
	}


	/**
	 * @param int|null $max_depth
	 */
	public function setMaxDepth( int|null $max_depth ): void
	{
		$this->_max_depth = $max_depth;
		$this->resetIteratorMap();
	}

	/**
	 *
	 */
	public function resetIteratorMap(): void
	{
		$this->_iterator_map = [];
	}

	/**
	 * @return string
	 */
	public function toJSON(): string
	{
		return json_encode( $this );
	}

	/**
	 *
	 * @return array
	 */
	public function jsonSerialize(): array
	{

		$props = [
			'id',
			'parent_id',
			'depth',
			'data',
			'children',
			'is_root',
		];

		$output = [];
		foreach( $props as $prop ) {
			$output[$prop] = $this->{$prop};
		}


		return $output;
	}

	/**
	 *
	 * @return array
	 */
	public function __sleep(): array
	{
		return [
			'id',
			'data',
			'parent',
			'children',
			'depth',
			'is_root',
		];
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->getLabel();
	}

	/**
	 * @return string
	 */
	public function getLabel(): string
	{
		return $this->label;
	}

	/**
	 * @param string $label
	 */
	public function setLabel( string $label ): void
	{
		$this->label = $label;
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		return $this->getLabel();
	}

	/**
	 *
	 */
	public function rewind(): void
	{
		if( !$this->_iterator_map ) {
			$this->getIteratorMap();
		}
		reset( $this->_iterator_map );
	}

	/**
	 * @return Data_Tree_Node[]
	 */
	public function getIteratorMap(): array
	{
		if( !$this->_iterator_map ) {
			$this->_prepareIteratorMap( $this->_iterator_map, $this->_max_depth, $this->depth );
		}

		return $this->_iterator_map;
	}

	/**
	 * @param array    &$result
	 * @param int|null $max_depth
	 * @param int|null $root_depth
	 */
	protected function _prepareIteratorMap( array &$result, int|null $max_depth, int|null $root_depth ): void
	{

		$result[$this->id] = $this;

		if( $max_depth ) {
			if( ($root_depth - $this->depth) > $max_depth ) {
				return;
			}
		}

		foreach( $this->_children as $child ) {
			$child->_prepareIteratorMap( $result, $max_depth, $root_depth );
		}

	}

	/**
	 * @return Data_Tree_Node
	 */
	public function current(): Data_Tree_Node
	{
		if( !$this->_iterator_map ) {
			$this->getIteratorMap();
		}

		return current( $this->_iterator_map );
	}



	//- Iterator ----------------------------------------------------------------------------------
	//- Iterator ----------------------------------------------------------------------------------
	//- Iterator ----------------------------------------------------------------------------------
	//- Iterator ----------------------------------------------------------------------------------
	//- Iterator ----------------------------------------------------------------------------------

	/**
	 * @return int|string|null
	 */
	public function key(): int|string|null
	{
		if( !$this->_iterator_map ) {
			$this->getIteratorMap();
		}

		return key( $this->_iterator_map );
	}

	/**
	 *
	 */
	public function next(): void
	{
		if( !$this->_iterator_map ) {
			$this->getIteratorMap();
		}
		next( $this->_iterator_map );
	}

	/**
	 * @return bool
	 */
	public function valid(): bool
	{
		if( !$this->_iterator_map ) {
			$this->getIteratorMap();
		}

		return key( $this->_iterator_map ) !== null;
	}

	/**
	 * @return int
	 */
	public function count(): int
	{
		return count( $this->toArray() );
	}

	/**
	 *
	 *
	 * @return array
	 */
	public function toArray(): array
	{

		$result = [];
		$this->_toArray( $result, $this->_max_depth, $this->depth );

		return $result;
	}

	//- Countable ---------------------------------------------------------------------------------
	//- Countable ---------------------------------------------------------------------------------
	//- Countable ---------------------------------------------------------------------------------
	//- Countable ---------------------------------------------------------------------------------

	/**
	 * @param array &$_result
	 * @param ?int $max_depth
	 * @param int $root_depth
	 */
	public function _toArray( array &$_result, ?int $max_depth, int $root_depth ): void
	{

		$id_key = $this->_tree->getIdKey();
		$parent_id_key = $this->_tree->getParentIdKey();
		$children_key = $this->_tree->getChildrenKey();
		$depth_key = $this->_tree->getDepthKey();
		$label_key = $this->_tree->getLabelKey();

		$next_children = true;
		if( $max_depth ) {
			if( ($root_depth - $this->depth) > $max_depth ) {
				$next_children = false;
			}
		}

		$item = $this->data;
		if( is_object( $item ) ) {
			if( $item instanceof JsonSerializable ) {
				$item = $item->jsonSerialize();
			} else {
				$item = [];
			}
		}

		$item[$id_key] = $this->id;
		$item[$parent_id_key] = $this->real_parent_id;
		$item[$label_key] = $this->label;
		$item[$depth_key] = $this->depth;

		if( $next_children && $this->_children ) {

			if( $this->is_root ) {
				$item[$children_key] = [];

				foreach( $this->_children as $child ) {
					$child->_toArray( $item[$children_key], $max_depth, $root_depth );
				}
			} else {
				$item[$children_key] = true;
			}


		}

		if( $this->is_root ) {
			$_result = $item;
		} else {
			$_result[] = $item;
		}

	}
	
}