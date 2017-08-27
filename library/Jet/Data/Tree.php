<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Data_Tree extends BaseObject implements BaseObject_Interface_IteratorCountable, BaseObject_Interface_Serializable_JSON
{

	/**
	 * @var bool
	 */
	protected $use_objects = false;

	/**
	 *
	 * @var string
	 */
	protected $id_key = 'id';

	/**
	 * @var string
	 */
	protected $id_getter_method_name = 'getId';

	/**
	 *
	 * @var string
	 */
	protected $parent_id_key = 'parent_id';

	/**
	 * @var string
	 */
	protected $parent_id_getter_method_name = 'getParentId';

	/**
	 *
	 * @var string
	 */
	protected $label_key = 'name';

	/**
	 * @var string
	 */
	protected $label_getter_method_name = 'getName';

	/**
	 *
	 * @var string
	 */
	protected $depth_key = 'depth';

	/**
	 * @var string
	 */
	protected $children_key = 'children';

	/**
	 *
	 * @var string
	 */
	protected $nodes_class_name = 'Data_Tree_Node';

	/**
	 *
	 * @var Data_Tree_Node[]
	 */
	protected $nodes = [];

	/**
	 *
	 * @var Data_Tree_Node
	 */
	protected $root_node = null;

	/**
	 * @var bool
	 */
	protected $adopt_orphans = false;

	/**
	 * @var bool
	 */
	protected $ignore_orphans = false;

	/**
	 * @var Data_Tree_Node[]
	 */
	protected $orphans_nodes = [];

	/**
	 *
	 * @var Data_Tree_Node[]
	 */
	protected $_iterator_map = [];

	/**
	 * @var array
	 */
	protected $__parent_map = [];

	/**
	 *
	 * @param string $id_key (optional, default: id)
	 * @param string $parent_id_key (optional,default: parent_id)
	 *
	 */
	public function __construct( $id_key = 'id', $parent_id_key = 'parent_id' )
	{
		$this->id_key = $id_key;
		$this->parent_id_key = $parent_id_key;
		$this->setNodesClassName( __NAMESPACE__.'\\'.$this->nodes_class_name );

	}

	/**
	 * Class of nodes
	 *
	 * @return string
	 */
	public function getNodesClassName()
	{
		return $this->nodes_class_name;
	}

	/**
	 * @param string $nodes_class_name
	 *
	 */
	public function setNodesClassName( $nodes_class_name )
	{
		$this->nodes_class_name = $nodes_class_name;
	}

	/**
	 * @return bool
	 */
	public function getAdoptOrphans()
	{
		return $this->adopt_orphans;
	}

	/**
	 * @param bool $adopt_orphans
	 */
	public function setAdoptOrphans( $adopt_orphans )
	{
		$this->adopt_orphans = $adopt_orphans;
	}

	/**
	 * @return bool
	 */
	public function getIgnoreOrphans()
	{
		return $this->ignore_orphans;
	}

	/**
	 * @param bool $ignore_orphans
	 */
	public function setIgnoreOrphans( $ignore_orphans )
	{
		$this->ignore_orphans = $ignore_orphans;
	}


	/**
	 * Key in data item representing id
	 *
	 * @return string
	 */
	public function getIdKey()
	{
		return $this->id_key;
	}

	/**
	 * @param string $id_key
	 */
	public function setIdKey( $id_key )
	{
		$this->id_key = $id_key;
	}

	/**
	 * @return string
	 */
	public function getIdGetterMethodName()
	{
		return $this->id_getter_method_name;
	}

	/**
	 * @param string $id_getter_method_name
	 */
	public function setIdGetterMethodName( $id_getter_method_name )
	{
		$this->id_getter_method_name = $id_getter_method_name;
	}

	/**
	 * Key in data item representing parent id
	 *
	 * @return string
	 */
	public function getParentIdKey()
	{
		return $this->parent_id_key;
	}

	/**
	 * @param string $parent_id_key
	 */
	public function setParentIdKey( $parent_id_key )
	{
		$this->parent_id_key = $parent_id_key;
	}

	/**
	 * @return string
	 */
	public function getParentIdGetterMethodName()
	{
		return $this->parent_id_getter_method_name;
	}

	/**
	 * @param string $parent_id_getter_method_name
	 */
	public function setParentIdGetterMethodName( $parent_id_getter_method_name )
	{
		$this->parent_id_getter_method_name = $parent_id_getter_method_name;
	}

	/**
	 * @return string
	 */
	public function getLabelKey()
	{
		return $this->label_key;
	}

	/**
	 * @param string $label_key
	 */
	public function setLabelKey( $label_key )
	{
		$this->label_key = $label_key;
	}

	/**
	 * @return string
	 */
	public function getLabelGetterMethodName()
	{
		return $this->label_getter_method_name;
	}

	/**
	 * @param string $label_getter_method_name
	 */
	public function setLabelGetterMethodName( $label_getter_method_name )
	{
		$this->label_getter_method_name = $label_getter_method_name;
	}

	/**
	 * @return string
	 */
	public function getChildrenKey()
	{
		return $this->children_key;
	}

	/**
	 * @param string $children_key
	 */
	public function setChildrenKey( $children_key )
	{
		$this->children_key = $children_key;
	}

	/**
	 * @return string
	 */
	public function getDepthKey()
	{
		return $this->depth_key;
	}

	/**
	 * @param string $depth_key
	 *
	 */
	public function setDepthKey( $depth_key )
	{
		$this->depth_key = $depth_key;
	}

	/**
	 * Returns all tree nodes
	 *
	 * @return Data_Tree_Node[]
	 */
	public function getNodes()
	{
		return $this->nodes;
	}

	/**
	 *
	 * @return string[]
	 */
	public function getNodesIds()
	{
		return array_keys( $this->nodes );
	}

	/**
	 *
	 * @param string $node_id
	 *
	 * @return bool
	 */
	public function getNodeExists( $node_id )
	{
		return isset( $this->nodes[$node_id] );
	}

	/**
	 * @param string $target_node_id
	 *
	 * @return array|bool
	 */
	public function getPath( $target_node_id )
	{
		$target_node_id = (string)$target_node_id;
		$target_node = $this->getNode( $target_node_id );

		if( !$target_node ) {
			return false;
		}

		$path = [];
		$path[] = $target_node->getId();

		while( ( $parent = $target_node->getParent() ) ) {
			/**
			 * @var Data_Tree_Node $parent
			 */
			if( $parent->getId()===null ) {
				break;
			}
			$path[] = $parent->getId();
			$target_node = $parent;
		}

		$path = array_reverse( $path );

		return $path;
	}

	/**
	 *
	 * @param string $node_id
	 *
	 * @return Data_Tree_Node|null
	 */
	public function getNode( $node_id )
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
	public function setData( array $data )
	{
		$this->use_objects = false;
		$this->_setData( $data );
	}

	/**
	 *
	 * @param array|\Iterator $items
	 *
	 * @throws Data_Tree_Exception
	 */
	protected function _setData( $items )
	{
		$this->__parent_map = [];

		/**
		 * @var array $root_item
		 */
		$root_item = null;

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
	 * @param array $item
	 *
	 * @return string
	 * @throws Data_Tree_Exception
	 */
	protected function getNodeData_id( $item )
	{

		if( $this->use_objects ) {
			return $item->{$this->id_getter_method_name}();
		}

		if( !isset( $item[$this->id_key] ) ) {
			throw new Data_Tree_Exception(
				'Missing \''.$this->id_key.'\' key in item data', Data_Tree_Exception::CODE_MISSING_VALUE
			);

		}

		return $item[$this->id_key];
	}

	/**
	 * @param array $item
	 *
	 * @throws Data_Tree_Exception
	 *
	 * @return string
	 */
	protected function getNodeData_parentId( $item )
	{
		if( $this->use_objects ) {
			return $item->{$this->parent_id_getter_method_name}();
		}

		if( !isset( $item[$this->parent_id_key] ) ) {
			throw new Data_Tree_Exception(
				'Missing \''.$this->parent_id_key.'\' key in item data', Data_Tree_Exception::CODE_MISSING_VALUE
			);

		}

		return $item[$this->parent_id_key];
	}

	/**
	 * Get root node if defined, else null
	 *
	 * @return Data_Tree_Node
	 */
	public function getRootNode()
	{
		if( !$this->root_node ) {
			$this->root_node = new $this->nodes_class_name( $this, null );
			$this->root_node->setIsRoot( true );
		}

		return $this->root_node;
	}

	/**
	 * @param Data_Tree_Node $node
	 */
	public function setRootNode( Data_Tree_Node $node )
	{

		$node->setIsRoot( true );
		$this->root_node = $node;

		$node->getChildren();
	}

	/**
	 * @param string $parent_id
	 */
	protected function __setData( $parent_id )
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
	 * @param array $item_data
	 *
	 * @throws Data_Tree_Exception
	 * @return Data_Tree_Node|null
	 */
	public function appendNode( $item_data )
	{

		$id = $this->getNodeData_id( $item_data );
		$parent_id = $this->getNodeData_parentId( $item_data );

		if( isset( $this->nodes[$id] ) ) {
			throw new Data_Tree_Exception(
				'Node \''.$id.'\' already exists', Data_Tree_Exception::CODE_NODE_ALREADY_EXISTS
			);
		}

		/**
		 * @var Data_Tree_Node $new_node
		 */
		$new_node = new $this->nodes_class_name( $this, $item_data );
		$new_node->setId( $id );
		$new_node->setParentId( $parent_id );
		$new_node->setLabel( $this->getNodeData_label( $item_data ) );

		$parent = $this->getNode( $new_node->getParentId() );

		if( !$parent ) {
			if( $this->ignore_orphans ) {
				return null;
			}

			if( $this->adopt_orphans ) {
				$new_node->setIsOrphan( true );

				$parent = $this->getRootNode();
			} else {
				throw new Data_Tree_Exception(
					'Inconsistent tree data. Parent node \''.$new_node->getParentId(
					).'\' does not exist. Node ID: \''.$new_node->getId().'\' ',
					Data_Tree_Exception::CODE_INCONSISTENT_TREE_DATA
				);

			}
		}

		$new_node->setParent( $parent );

		$this->nodes[$id] = $new_node;

		$parent->appendChild( $new_node );

		return $this->nodes[$id];
	}

	/**
	 * @param array $item
	 *
	 * @return string
	 * @throws Data_Tree_Exception
	 */
	protected function getNodeData_label( $item )
	{

		if( $this->use_objects ) {
			return $item->{$this->label_getter_method_name}();
		}

		if( !isset( $item[$this->label_key] ) ) {
			throw new Data_Tree_Exception(
				'Missing \''.$this->label_key.'\' key in item data', Data_Tree_Exception::CODE_MISSING_VALUE
			);

		}

		return $item[$this->label_key];
	}

	/**
	 *
	 * @param \Iterator|array $data
	 */
	public function setDataSource( $data )
	{
		$this->use_objects = true;
		$this->_setData( $data );
	}

	/**
	 *
	 */
	public function resetIteratorMap()
	{
		$this->_iterator_map = [];

		foreach( $this->nodes as $node ) {
			$node->resetIteratorMap();
		}
	}

	/**
	 * @return string
	 */
	public function toJSON()
	{

		$data = $this->jsonSerialize();

		return json_encode( $data );
	}

	/**
	 * @return array
	 */
	public function jsonSerialize()
	{

		return $this->toArray();
	}


	//- Mvc_Controller_REST_Serializable ----------------------------------------------------------------------
	//- Mvc_Controller_REST_Serializable ----------------------------------------------------------------------
	//- Mvc_Controller_REST_Serializable ----------------------------------------------------------------------
	//- Mvc_Controller_REST_Serializable ----------------------------------------------------------------------
	//- Mvc_Controller_REST_Serializable ----------------------------------------------------------------------
	//- Mvc_Controller_REST_Serializable ----------------------------------------------------------------------

	/**
	 *
	 * @return array
	 */
	public function toArray()
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
	public function rewind()
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
	public function getIteratorMap()
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
	public function current()
	{
		if( !$this->_iterator_map ) {
			$this->getIteratorMap();
		}

		return current( $this->_iterator_map );
	}

	/**
	 * @return mixed
	 */
	public function key()
	{
		if( !$this->_iterator_map ) {
			$this->getIteratorMap();
		}

		return key( $this->_iterator_map );
	}

	/**
	 *
	 */
	public function next()
	{
		if( !$this->_iterator_map ) {
			$this->getIteratorMap();
		}
		next( $this->_iterator_map );
	}

	/**
	 * @return bool
	 */
	public function valid()
	{
		if( !$this->_iterator_map ) {
			$this->getIteratorMap();
		}

		return key( $this->_iterator_map )!==null;
	}

	//- Countable ---------------------------------------------------------------------------------
	//- Countable ---------------------------------------------------------------------------------
	//- Countable ---------------------------------------------------------------------------------
	//- Countable ---------------------------------------------------------------------------------
	/**
	 * @return int
	 */
	public function count()
	{
		return count( $this->nodes );
	}

}