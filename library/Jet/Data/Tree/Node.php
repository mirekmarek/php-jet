<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Data
 * @subpackage Data_Tree
 */
namespace Jet;


class Data_Tree_Node extends Object implements \Iterator, \Countable, \JsonSerializable  {

	/**
	 *
	 * @var Data_Tree
	 */
	protected $tree;

	/**
	 *
	 * @var string 
	 */
	protected $ID;

	/**
	 *
	 * @var string
	 */
	protected $parent_ID;

	/**
	 * Parent node
	 *
	 * @var Data_Tree_Node
	 */
	protected $parent;


	/**
	 * Node data
	 *
	 * @var array 
	 */
	protected $data = [];


	/**
	 * Children
	 *
	 * @var Data_Tree_Node[]
	 */
	protected $children = [];

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
	protected $is_root = true;

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
	 *
	 * @param Data_Tree $tree
	 * @param array $data
	 *
	 * @throws Data_Tree_Exception
	 */
	public function __construct( Data_Tree $tree, array $data ){
		$this->tree = $tree;
		$this->ID = $data[$tree->getIDKey()];
		$this->parent_ID = $data[$tree->getParentIDKey()];
		$this->data = $data;



		$this->is_root = ($this->ID==$tree->getRootNodeID());

		if( !$this->is_root ) {

			$this->parent = $tree->getNode( $this->parent_ID );

			if(!$this->parent) {
				if($tree->getAdoptOrphans()) {
					$this->is_orphan = true;
				} else {
					throw new Data_Tree_Exception(
						'Inconsistent tree data. Parent node \''.$this->parent_ID.'\' does not exist. Node ID: \''.$this->ID.'\' ',
						Data_Tree_Exception::CODE_INCONSISTENT_TREE_DATA
					);

				}
			} else {
				$this->depth = $this->parent->getDepth() + 1;

				$this->parent->appendChild($this);
			}

		} else {
			$root_node = $this->tree->getRootNode();
			if( $root_node ) {
				throw new Data_Tree_Exception(
					'Multiple roots in items (root ID: \''.$this->ID.'\') ',
					Data_Tree_Exception::CODE_INCONSISTENT_TREE_DATA
				);

			}
		}

	}

	/**
	 * @param Data_Tree_Node $node
	 *
	 * @throws Data_Tree_Exception
	 *
	 */
	public function appendChild( Data_Tree_Node $node ){

		$ID = $node->getID();

		if(
			$this->tree->getNodeExists($ID) ||
			isset($this->children[$ID])
		) {
			throw new Data_Tree_Exception(
				'Child \''.$ID.'\' already exists!',
				Data_Tree_Exception::CODE_NODE_ALREADY_EXISTS
			);
		}

		$this->children[$ID] = $node;
	}


	/**
	 *
	 * @return Data_Tree
	 */
	public function getTree(){
		return $this->tree;
	}

	/**
	 * Node ID
	 *
	 * @return string 
	 */
	public function getID(){
		return $this->ID;
	}


	/**
	 *
	 * @return string
	 */
	public function getParentID(){
		return $this->parent_ID;
	}

	/**
	 *
	 * @return Data_Tree_Node
	 */
	public function getParent(){
		return $this->parent;
	}


	/**
	 *
	 * @return bool
	 */
	public function getIsRoot(){
		return $this->is_root;
	}

	/**
	 * @param boolean $is_root
	 */
	public function setIsRoot($is_root) {
		$this->is_root = (bool)$is_root;
	}

	/**
	 * @param boolean $is_orphan
	 */
	public function setIsOrphan($is_orphan) {
		$this->is_orphan = $is_orphan;
	}

	/**
	 * @return boolean
	 */
	public function getIsOrphan() {
		return $this->is_orphan;
	}



	/**
	 *
	 * @return int
	 */
	public function getDepth(){
		return $this->depth;
	}

	/**
	 *
	 * @return array
	 */
	public function getData(){
		return $this->data;
	}

	/**
	 * @param array $data
	 */
	public function setData($data) {
		$this->data = $data;
	}



	/**
	 *
	 * @return bool
	 */
	public function getHasChildren(){
		return (bool)$this->children;
	}

	/**
	 *
	 */
	public function setHasChildren(){
		$this->children = true;
	}

	/**
	 *
	 * @return array
	 */
	public function getChildren(){
		return $this->children;
	}

	/**
	 *
	 * @param string $ID
	 *
	 * @return bool
	 */
	public function getChildExists($ID){
		return isset( $this->children[$ID] );
	}


	/**
	 *
	 * @param string $child_ID
	 *
	 * @return Data_Tree_Node
	 */
	public function getChild($child_ID){
		return $this->children[$child_ID];
	}


	/**
	 *
	 * @return Data_Tree_Node[]
	 */
	public function getPathToRoot(){
		$result = [];

		$result[$this->ID] = $this;
		$_node = $this;

		while( $_node->parent ){
			$_node = $_node->parent;
			$result[$_node->ID] = $_node;
		}

		return $result;
	}

	/**
	 * @param int|null $max_depth
	 */
	public function setMaxDepth( $max_depth ) {
		$this->_max_depth = $max_depth;
		$this->resetIteratorMap();
	}



	/**
	 *
	 * @return array
	 */
	public function jsonSerialize(){

		$props = [
			'ID',
			'parent_ID',
			'depth',
			'data',
			'children',
			'is_root'
		];

		$output = [];
		foreach($props as $prop){
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
	public function __sleep(){
		return [
			'ID',
			'data',
			'parent',
			'children',
			'depth',
			'is_root'
		];
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->getLabel();
	}

	/**
	 * @return string
	 */
	public function toString() {
		return $this->getLabel();
	}

	/**
	 * @return string
	 */
	public function getLabel() {
		if( !$this->tree->getLabelKey() ) {
			return $this->ID;
		}

		return $this->data[$this->tree->getLabelKey()];
	}


	/**
	 *
	 * @throws Data_Tree_Exception
	 *
	 * @return array
	 */
	public function toArray(){

		$result = [];
		$this->_toArray($result, $this->_max_depth, $this->depth);

		return $result;
	}

	/**
	 * @param array &$_result
	 * @param int $max_depth
	 * @param int $root_depth
	 */
	public function _toArray( &$_result, $max_depth, $root_depth ){

		$ID_key = $this->tree->getIDKey();
		$parent_ID_key = $this->tree->getParentIDKey();
		$children_key = $this->tree->getChildrenKey();
		$depth_key = $this->tree->getDepthKey();

		$next_children = true;
		if($max_depth) {
			if( ($root_depth-$this->depth)>$max_depth ) {
				$next_children = false;
			}
		}

		$item = $this->data;
		$item[$ID_key] = $this->ID;
		$item[$parent_ID_key] = $this->parent_ID;
		$item[$depth_key] = $this->depth;

		if($next_children && $this->children){

			if(
				$this->is_root ||
				!$this->tree->getLazyMode()
			) {
				$item[$children_key] = [];

				foreach($this->children as $child) {
					$child->_toArray( $item[$children_key], $max_depth, $root_depth );
				}
			} else {
				$item[$children_key] = true;
			}


		}

		if($this->is_root) {
			$_result = $item;
		} else {
			$_result[] = $item;
		}

	}


	/**
	 *
	 */
	public function resetIteratorMap() {
		$this->_iterator_map = [];
	}

	/**
	 *
	 */
	public function getIteratorMap(){
		if($this->_iterator_map) {
			return $this->_iterator_map;
		}

		$this->_prepareIteratorMap($this->_iterator_map, $this->_max_depth, $this->depth);

		return $this->_iterator_map;
	}

	/**
	 * @param array &$result
	 * @param int|null $max_depth
	 * @param int|null $root_depth
	 */
	protected function _prepareIteratorMap( &$result, $max_depth, $root_depth ){

		$result[(string)$this->ID] = $this;

		if($max_depth) {
			if( ($root_depth-$this->depth)>$max_depth ) {
				return;
			}
		}

		foreach($this->children as $child){
			$child->_prepareIteratorMap( $result, $max_depth, $root_depth );
		}

	}



	//- Iterator ----------------------------------------------------------------------------------
	//- Iterator ----------------------------------------------------------------------------------
	//- Iterator ----------------------------------------------------------------------------------
	//- Iterator ----------------------------------------------------------------------------------
	//- Iterator ----------------------------------------------------------------------------------

	/**
	 *
	 */
	public function rewind() {
		if(!$this->_iterator_map){
			$this->getIteratorMap();
		}
		reset($this->_iterator_map);
	}

	/**
	 * @return Data_Tree_Node
	 */
	public function current() {
		if(!$this->_iterator_map){
			$this->getIteratorMap();
		}
		return current($this->_iterator_map);
	}

	/**
	 * @return mixed
	 */
	public function key() {
		if(!$this->_iterator_map){
			$this->getIteratorMap();
		}
		return key($this->_iterator_map);
	}

	/**
	 *
	 */
	public function next() {
		if(!$this->_iterator_map){
			$this->getIteratorMap();
		}
		next($this->_iterator_map);
	}

	/**
	 * @return bool
	 */
	public function valid() {
		if(!$this->_iterator_map){
			$this->getIteratorMap();
		}

		return key($this->_iterator_map)!==null;
	}

	//- Countable ---------------------------------------------------------------------------------
	//- Countable ---------------------------------------------------------------------------------
	//- Countable ---------------------------------------------------------------------------------
	//- Countable ---------------------------------------------------------------------------------
	/**
	 * @return int
	 */
	public function count() {
		return count( $this->toArray() );
	}

}