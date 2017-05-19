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
class Data_Tree_Node extends BaseObject implements \Iterator, \Countable, \JsonSerializable, Form_Field_Select_Option_Interface
{

	/**
	 *
	 * @var Data_Tree
	 */
	protected $_tree;

	/**
	 *
	 * @var string
	 */
	protected $id;

	/**
	 *
	 * @var string
	 */
	protected $parent_id = null;
	/**
	 *
	 * @var string
	 */
	protected $real_parent_id = null;

	/**
	 * Parent node
	 *
	 * @var Data_Tree_Node
	 */
	protected $_parent;

	/**
	 * @var string|null
	 */
	protected $label;

	/**
	 * Node data
	 *
	 * @var mixed
	 */
	protected $data;


	/**
	 * Children
	 *
	 * @var Data_Tree_Node[]
	 */
	protected $_children = [];

	/**
	 * Range from root
	 *
	 * @var int
	 */
	protected $depth = 0;

	/**
	 * Is node root?
	 *
	 * @var bool
	 */
	protected $is_root = false;

	/**
	 * @var bool
	 */
	protected $is_orphan = false;

	/**
	 *
	 * @var Data_Tree_Node[]
	 */
	protected $_iterator_map = [];

	/**
	 * @var int
	 */
	protected $_max_depth;

	/**
	 * @var string
	 */
	protected $select_option_css_style = '';

	/**
	 * @var string
	 */
	protected $select_option_css_class = '';

	/**
	 *
	 * @param Data_Tree $tree
	 * @param mixed     $data
	 */
	public function __construct( Data_Tree $tree, $data )
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
	public function appendChild( Data_Tree_Node $node )
	{

		$id = $node->getId();

		if( isset( $this->_children[$id] ) ) {
			throw new Data_Tree_Exception(
				'Child \''.$id.'\' already exists!', Data_Tree_Exception::CODE_NODE_ALREADY_EXISTS
			);
		}

		$this->_children[$id] = $node;
	}

	/**
	 * Node ID
	 *
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param string $id
	 */
	public function setId( $id )
	{
		$this->id = $id;
	}

	/**
	 *
	 * @return Data_Tree
	 */
	public function getTree()
	{
		return $this->_tree;
	}

	/**
	 *
	 * @return string
	 */
	public function getParentId()
	{
		return $this->parent_id;
	}

	/**
	 * @param string $parent_id
	 */
	public function setParentId( $parent_id )
	{
		$this->real_parent_id = $parent_id;
		$this->parent_id = $parent_id;
	}

	/**
	 * @return string
	 */
	public function getRealParentId()
	{
		return $this->real_parent_id;
	}

	/**
	 * @param string $real_parent_id
	 */
	public function setRealParentId( $real_parent_id )
	{
		$this->real_parent_id = $real_parent_id;
	}

	/**
	 *
	 * @return Data_Tree_Node
	 */
	public function getParent()
	{
		return $this->_parent;
	}

	/**
	 * @param Data_Tree_Node $_parent
	 */
	public function setParent( Data_Tree_Node $_parent )
	{
		$this->_parent = $_parent;
		$this->parent_id = $_parent->getId();
		if( !$this->is_orphan ) {
			$this->real_parent_id = $_parent->getId();
		}

		$this->depth = $_parent->getDepth()+1;
	}

	/**
	 *
	 * @return int
	 */
	public function getDepth()
	{
		return $this->depth;
	}

	/**
	 *
	 * @return bool
	 */
	public function getIsRoot()
	{
		return $this->is_root;
	}

	/**
	 * @param bool $is_root
	 */
	public function setIsRoot( $is_root )
	{
		$this->is_root = (bool)$is_root;
	}

	/**
	 * @return bool
	 */
	public function getIsOrphan()
	{
		return $this->is_orphan;
	}

	/**
	 * @param bool $is_orphan
	 */
	public function setIsOrphan( $is_orphan )
	{
		$this->is_orphan = $is_orphan;
	}

	/**
	 *
	 * @return mixed
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @param mixed $data
	 */
	public function setData( $data )
	{
		$this->data = $data;
	}

	/**
	 *
	 * @return bool
	 */
	public function getHasChildren()
	{
		return (bool)$this->_children;
	}

	/**
	 *
	 */
	public function setHasChildren()
	{
		$this->_children = true;
	}

	/**
	 *
	 * @return Data_Tree_Node[]
	 */
	public function getChildren()
	{
		return $this->_children;
	}

	/**
	 *
	 * @param string $id
	 *
	 * @return bool
	 */
	public function getChildExists( $id )
	{
		return isset( $this->_children[$id] );
	}


	/**
	 *
	 * @param string $child_id
	 *
	 * @return Data_Tree_Node
	 */
	public function getChild( $child_id )
	{
		return $this->_children[$child_id];
	}


	/**
	 *
	 * @return Data_Tree_Node[]
	 */
	public function getPathToRoot()
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
	 * @param int|null $max_depth
	 */
	public function setMaxDepth( $max_depth )
	{
		$this->_max_depth = $max_depth;
		$this->resetIteratorMap();
	}

	/**
	 *
	 */
	public function resetIteratorMap()
	{
		$this->_iterator_map = [];
	}

	/**
	 *
	 * @return array
	 */
	public function jsonSerialize()
	{

		$props = [
			'id', 'parent_id', 'depth', 'data', 'children', 'is_root',
		];

		$output = [];
		foreach( $props as $prop ) {
			$output[$prop] = $this->{$prop};
		}


		return $output;
	}

	/**  @noinspection PhpMissingParentCallMagicInspection
	 *
	 * Don't serialize bound tree
	 *
	 * @return array
	 */
	public function __sleep()
	{
		return [
			'id', 'data', 'parent', 'children', 'depth', 'is_root',
		];
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->getLabel();
	}

	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @param string $label
	 */
	public function setLabel( $label )
	{
		$this->label = $label;
	}

	/**
	 * @return string
	 */
	public function toString()
	{
		return $this->getLabel();
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

	/**
	 *
	 */
	public function getIteratorMap()
	{
		if( $this->_iterator_map ) {
			return $this->_iterator_map;
		}

		$this->_prepareIteratorMap( $this->_iterator_map, $this->_max_depth, $this->depth );

		return $this->_iterator_map;
	}

	/**
	 * @param array    &$result
	 * @param int|null $max_depth
	 * @param int|null $root_depth
	 */
	protected function _prepareIteratorMap( &$result, $max_depth, $root_depth )
	{

		$result[(string)$this->id] = $this;

		if( $max_depth ) {
			if( ( $root_depth-$this->depth )>$max_depth ) {
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
	public function current()
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

	/**
	 * @return int
	 */
	public function count()
	{
		return count( $this->toArray() );
	}

	/**
	 *
	 * @throws Data_Tree_Exception
	 *
	 * @return array
	 */
	public function toArray()
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
	 * @param int   $max_depth
	 * @param int   $root_depth
	 */
	public function _toArray( &$_result, $max_depth, $root_depth )
	{

		$id_key = $this->_tree->getIdKey();
		$parent_id_key = $this->_tree->getParentIdKey();
		$children_key = $this->_tree->getChildrenKey();
		$depth_key = $this->_tree->getDepthKey();
		$label_key = $this->_tree->getLabelKey();

		$next_children = true;
		if( $max_depth ) {
			if( ( $root_depth-$this->depth )>$max_depth ) {
				$next_children = false;
			}
		}

		$item = $this->data;
		if( is_object( $item ) ) {
			if( $item instanceof \JsonSerializable ) {
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

	//- Form_Field_Select_Option_Interface --------------------------------------------------------
	//- Form_Field_Select_Option_Interface --------------------------------------------------------
	//- Form_Field_Select_Option_Interface --------------------------------------------------------
	//- Form_Field_Select_Option_Interface --------------------------------------------------------
	//- Form_Field_Select_Option_Interface --------------------------------------------------------

	/**
	 * @return string
	 */
	public function getSelectOptionCssStyle()
	{
		return $this->select_option_css_style;
	}

	/**
	 * @param string $css_style
	 */
	public function setSelectOptionCssStyle( $css_style )
	{
		$this->select_option_css_style = $css_style;
	}

	/**
	 * @return string
	 */
	public function getSelectOptionCssClass()
	{
		return $this->select_option_css_class;
	}

	/**
	 * @param string $css_class
	 */
	public function setSelectOptionCssClass( $css_class )
	{
		$this->select_option_css_class = $css_class;
	}
}