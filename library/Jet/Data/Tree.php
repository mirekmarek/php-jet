<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use Iterator;

/**
 *
 */
class Data_Tree extends BaseObject implements BaseObject_Interface_IteratorCountable, BaseObject_Interface_Serializable_JSON
{

	/**
	 * @var bool
	 */
	protected bool $use_objects = false;

	/**
	 *
	 * @var string
	 */
	protected string $id_key = 'id';

	/**
	 * @var string
	 */
	protected string $id_getter_method_name = 'getId';

	/**
	 *
	 * @var string
	 */
	protected string $parent_id_key = 'parent_id';

	/**
	 * @var string
	 */
	protected string $parent_id_getter_method_name = 'getParentId';

	/**
	 *
	 * @var string
	 */
	protected string $label_key = 'name';

	/**
	 * @var string
	 */
	protected string $label_getter_method_name = 'getName';

	/**
	 *
	 * @var string
	 */
	protected string $depth_key = 'depth';

	/**
	 * @var string
	 */
	protected string $children_key = 'children';

	/**
	 *
	 * @var string
	 */
	protected string $nodes_class_name = Data_Tree_Node::class;

	/**
	 *
	 * @var Data_Tree_Node[]
	 */
	protected array $nodes = [];

	/**
	 *
	 * @var ?Data_Tree_Node
	 */
	protected ?Data_Tree_Node $root_node = null;

	/**
	 * @var bool
	 */
	protected bool $adopt_orphans = false;

	/**
	 * @var bool
	 */
	protected bool $ignore_orphans = false;

	/**
	 * @var Data_Tree_Node[]
	 */
	protected array $orphans_nodes = [];

	/**
	 *
	 * @var Data_Tree_Node[]
	 */
	protected array $_iterator_map = [];

	/**
	 * @var array
	 */
	protected array $__parent_map = [];

	/**
	 *
	 * @param string $id_key (optional, default: id)
	 * @param string $parent_id_key (optional,default: parent_id)
	 *
	 */
	public function __construct( string $id_key = 'id', string $parent_id_key = 'parent_id' )
	{
		$this->id_key = $id_key;
		$this->parent_id_key = $parent_id_key;
		$this->setNodesClassName( $this->nodes_class_name );

	}

	/**
	 * Class of nodes
	 *
	 * @return string
	 */
	public function getNodesClassName(): string
	{
		return $this->nodes_class_name;
	}

	/**
	 * @param string $nodes_class_name
	 */
	public function setNodesClassName( string $nodes_class_name ): void
	{
		$this->nodes_class_name = $nodes_class_name;
	}

	/**
	 * @return bool
	 */
	public function getAdoptOrphans(): bool
	{
		return $this->adopt_orphans;
	}

	/**
	 * @param bool $adopt_orphans
	 */
	public function setAdoptOrphans( bool $adopt_orphans ): void
	{
		$this->adopt_orphans = $adopt_orphans;
	}

	/**
	 * @return bool
	 */
	public function getIgnoreOrphans(): bool
	{
		return $this->ignore_orphans;
	}

	/**
	 * @param bool $ignore_orphans
	 */
	public function setIgnoreOrphans( bool $ignore_orphans ): void
	{
		$this->ignore_orphans = $ignore_orphans;
	}


	/**
	 * Key in data item representing id
	 *
	 * @return string
	 */
	public function getIdKey(): string
	{
		return $this->id_key;
	}

	/**
	 * @param string $id_key
	 */
	public function setIdKey( string $id_key ): void
	{
		$this->id_key = $id_key;
	}

	/**
	 * @return string
	 */
	public function getIdGetterMethodName(): string
	{
		return $this->id_getter_method_name;
	}

	/**
	 * @param string $id_getter_method_name
	 */
	public function setIdGetterMethodName( string $id_getter_method_name ): void
	{
		$this->id_getter_method_name = $id_getter_method_name;
	}

	/**
	 * Key in data item representing parent id
	 *
	 * @return string
	 */
	public function getParentIdKey(): string
	{
		return $this->parent_id_key;
	}

	/**
	 * @param string $parent_id_key
	 */
	public function setParentIdKey( string $parent_id_key ): void
	{
		$this->parent_id_key = $parent_id_key;
	}

	/**
	 * @return string
	 */
	public function getParentIdGetterMethodName(): string
	{
		return $this->parent_id_getter_method_name;
	}

	/**
	 * @param string $parent_id_getter_method_name
	 */
	public function setParentIdGetterMethodName( string $parent_id_getter_method_name ): void
	{
		$this->parent_id_getter_method_name = $parent_id_getter_method_name;
	}

	/**
	 * @return string
	 */
	public function getLabelKey(): string
	{
		return $this->label_key;
	}

	/**
	 * @param string $label_key
	 */
	public function setLabelKey( string $label_key ): void
	{
		$this->label_key = $label_key;
	}

	/**
	 * @return string
	 */
	public function getLabelGetterMethodName(): string
	{
		return $this->label_getter_method_name;
	}

	/**
	 * @param string $label_getter_method_name
	 */
	public function setLabelGetterMethodName( string $label_getter_method_name ): void
	{
		$this->label_getter_method_name = $label_getter_method_name;
	}

	/**
	 * @return string
	 */
	public function getChildrenKey(): string
	{
		return $this->children_key;
	}

	/**
	 * @param string $children_key
	 */
	public function setChildrenKey( string $children_key ): void
	{
		$this->children_key = $children_key;
	}

	/**
	 * @return string
	 */
	public function getDepthKey(): string
	{
		return $this->depth_key;
	}

	/**
	 * @param string $depth_key
	 *
	 */
	public function setDepthKey( string $depth_key ): void
	{
		$this->depth_key = $depth_key;
	}

	/**
	 * Returns all tree nodes
	 *
	 * @return Data_Tree_Node[]
	 */
	public function getNodes(): array
	{
		return $this->nodes;
	}

	/**
	 *
	 * @return array
	 */
	public function getNodesIds(): array
	{
		return array_keys( $this->nodes );
	}

	/**
	 *
	 * @param string $node_id
	 *
	 * @return bool
	 */
	public function getNodeExists( string $node_id ): bool
	{
		return isset( $this->nodes[$node_id] );
	}

	/**
	 * @param string $target_node_id
	 *
	 * @return array|bool
	 */
	public function getPath( string $target_node_id ): array|bool
	{
		$target_node = $this->getNode( $target_node_id );

		if( !$target_node ) {
			return false;
		}

		$path = [];
		$path[] = $target_node->getId();

		while( ($parent = $target_node->getParent()) ) {
			if( $parent->getId() === null ) {
				break;
			}
			$path[] = $parent->getId();
			$target_node = $parent;
		}

		return array_reverse( $path );

	}

	/**
	 *
	 * @param string $node_id
	 *
	 * @return Data_Tree_Node|null
	 */
	public function getNode( string $node_id ): Data_Tree_Node|null
	{
		if( !isset( $this->nodes[$node_id] ) ) {
			return null;
		}

		return $this->nodes[$node_id];
	}

	/**
	 * Sets tree data
	 *
	 * @param array $data
	 */
	public function setData( array $data ): void
	{
		$this->use_objects = false;
		$this->_setData( $data );
	}

	/**
	 *
	 * @param array|Iterator $items
	 *
	 * @throws Data_Tree_Exception
	 */
	protected function _setData( array|Iterator $items ): void
	{
		$this->__parent_map = [];

		$ids = [];

		foreach( $items as $item ) {
			/**
			 * @var array $item
			 */
			$id = $this->getNodeData_id( $item );
			$parent_id = $this->getNodeData_parentId( $item );

			$ids[] = $id;

			if( !$parent_id ) {
				$parent_id = '';
			}


			if( !isset( $this->__parent_map[$parent_id] ) ) {
				$this->__parent_map[$parent_id] = [];
			}

			$this->__parent_map[$parent_id][$id] = $item;

		}


		$root_node = $this->getRootNode();
		$root_id = $root_node->getId();

		$this->nodes[$root_id] = $root_node;

		$this->__setData( $root_id );


		if( $this->__parent_map ) {
			if( $this->ignore_orphans ) {
				$this->__parent_map = [];

				return;
			}

			if( $this->adopt_orphans ) {

				$parent_ids = array_keys( $this->__parent_map );

				$non_exists_parent_ids = array_diff( $parent_ids, $ids );

				foreach( $non_exists_parent_ids as $non_exists_parent_id ) {
					foreach( $this->__parent_map[$non_exists_parent_id] as $orphan_id => $orphan_item ) {
						$this->appendNode( $orphan_item );

						$this->__setData( $orphan_id );
					}
				}

				return;
			}

			throw new Data_Tree_Exception(
				'Inconsistent tree data. There are orphans.', Data_Tree_Exception::CODE_INCONSISTENT_TREE_DATA
			);

		}
	}

	/**
	 * @param array|object $item
	 *
	 * @return string
	 * @throws Data_Tree_Exception
	 */
	protected function getNodeData_id( array|object $item ): string
	{

		if( $this->use_objects ) {
			return $item->{$this->id_getter_method_name}();
		}

		if( !isset( $item[$this->id_key] ) ) {
			throw new Data_Tree_Exception(
				'Missing \'' . $this->id_key . '\' key in item data', Data_Tree_Exception::CODE_MISSING_VALUE
			);

		}

		return $item[$this->id_key];
	}

	/**
	 * @param array|object $item
	 *
	 * @return string
	 * @throws Data_Tree_Exception
	 *
	 */
	protected function getNodeData_parentId( array|object $item ): string
	{
		if( $this->use_objects ) {
			return $item->{$this->parent_id_getter_method_name}();
		}

		if( !isset( $item[$this->parent_id_key] ) ) {
			throw new Data_Tree_Exception(
				'Missing \'' . $this->parent_id_key . '\' key in item data', Data_Tree_Exception::CODE_MISSING_VALUE
			);

		}

		return $item[$this->parent_id_key];
	}

	/**
	 * Get root node if defined, else null
	 *
	 * @return Data_Tree_Node|null
	 */
	public function getRootNode(): Data_Tree_Node|null
	{
		if( !$this->root_node ) {
			$this->root_node = new $this->nodes_class_name( $this, null );
			$this->root_node->_setIsRoot( true );
		}

		return $this->root_node;
	}

	/**
	 * @param Data_Tree_Node $node
	 */
	public function setRootNode( Data_Tree_Node $node ): void
	{
		$node->_setIsRoot( true );
		$this->root_node = $node;
	}

	/**
	 * @param string $parent_id
	 */
	protected function __setData( string $parent_id ): void
	{
		if( !isset( $this->__parent_map[$parent_id] ) ) {
			return;
		}

		foreach( $this->__parent_map[$parent_id] as $id => $item_data ) {
			$this->appendNode( $item_data );
			unset( $this->__parent_map[$parent_id][$id] );

			$this->__setData( $id );
		}
		unset( $this->__parent_map[$parent_id] );

	}

	/**
	 *
	 * @param array|object $item_data
	 *
	 * @return Data_Tree_Node|null
	 * @throws Data_Tree_Exception
	 */
	public function appendNode( array|object $item_data ): Data_Tree_Node|null
	{

		$id = $this->getNodeData_id( $item_data );
		$parent_id = $this->getNodeData_parentId( $item_data );

		if(!$parent_id) {
			$parent_id = '';
		}

		if( isset( $this->nodes[$id] ) ) {
			throw new Data_Tree_Exception(
				'Node \'' . $id . '\' already exists', Data_Tree_Exception::CODE_NODE_ALREADY_EXISTS
			);
		}

		/**
		 * @var Data_Tree_Node $new_node
		 */
		$new_node = new $this->nodes_class_name( $this, $item_data );
		$new_node->setId( $id );
		$new_node->_setParentId( $parent_id );
		$new_node->setLabel( $this->getNodeData_label( $item_data ) );

		$parent = $this->getNode( $new_node->getParentId() );

		if( !$parent ) {
			if( $this->ignore_orphans ) {
				return null;
			}

			if( $this->adopt_orphans ) {
				$new_node->_setIsOrphan( true );

				$parent = $this->getRootNode();
			} else {
				throw new Data_Tree_Exception(
					'Inconsistent tree data. Parent node \'' . $new_node->getParentId() . '\' does not exist. Node ID: \'' . $new_node->getId() . '\' ',
					Data_Tree_Exception::CODE_INCONSISTENT_TREE_DATA
				);

			}
		}

		$new_node->_setParent( $parent );

		$this->nodes[$id] = $new_node;

		$parent->_appendChild( $new_node );

		return $this->nodes[$id];
	}

	/**
	 * @param object|array $item
	 *
	 * @return string
	 * @throws Data_Tree_Exception
	 */
	protected function getNodeData_label( object|array $item ): string
	{

		if( $this->use_objects ) {
			return $item->{$this->label_getter_method_name}();
		}

		if( !isset( $item[$this->label_key] ) ) {
			throw new Data_Tree_Exception(
				'Missing \'' . $this->label_key . '\' key in item data', Data_Tree_Exception::CODE_MISSING_VALUE
			);

		}

		return $item[$this->label_key];
	}

	/**
	 *
	 * @param Iterator|array $data
	 */
	public function setDataSource( Iterator|array $data ): void
	{
		$this->use_objects = true;
		$this->_setData( $data );
	}

	/**
	 *
	 */
	public function resetIteratorMap(): void
	{
		$this->_iterator_map = [];

		foreach( $this->nodes as $node ) {
			$node->resetIteratorMap();
		}
	}

	/**
	 * @return string
	 */
	public function toJSON(): string
	{

		$data = $this->jsonSerialize();

		return json_encode( $data );
	}

	/**
	 * @return array
	 */
	public function jsonSerialize(): array
	{
		return $this->toArray();
	}


	//- MVC_Controller_REST_Serializable ----------------------------------------------------------------------
	//- MVC_Controller_REST_Serializable ----------------------------------------------------------------------
	//- MVC_Controller_REST_Serializable ----------------------------------------------------------------------
	//- MVC_Controller_REST_Serializable ----------------------------------------------------------------------
	//- MVC_Controller_REST_Serializable ----------------------------------------------------------------------
	//- MVC_Controller_REST_Serializable ----------------------------------------------------------------------

	/**
	 *
	 * @return array
	 */
	public function toArray(): array
	{
		$result = [];

		if( $this->root_node ) {
			$result[] = $this->root_node->toArray();
		}

		return $result;
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


	//- Iterator ----------------------------------------------------------------------------------
	//- Iterator ----------------------------------------------------------------------------------
	//- Iterator ----------------------------------------------------------------------------------
	//- Iterator ----------------------------------------------------------------------------------
	//- Iterator ----------------------------------------------------------------------------------

	/**
	 *
	 */
	public function getIteratorMap(): array
	{
		if( $this->_iterator_map ) {
			return $this->_iterator_map;
		}

		$this->_iterator_map = [];

		if( $this->root_node ) {
			$this->_iterator_map = $this->root_node->getIteratorMap();
		}

		/*
		foreach( $this->orphans_nodes as $orphan ) {
			$this->_iterator_map += $orphan->getIteratorMap();
		}
		*/

		return $this->_iterator_map;
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

	/**
	 * @return string
	 */
	public function key(): string
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

	//- Countable ---------------------------------------------------------------------------------
	//- Countable ---------------------------------------------------------------------------------
	//- Countable ---------------------------------------------------------------------------------
	//- Countable ---------------------------------------------------------------------------------
	/**
	 * @return int
	 */
	public function count(): int
	{
		return count( $this->nodes );
	}

}