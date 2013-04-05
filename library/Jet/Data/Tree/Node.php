<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
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
	protected $data = array();


	/**
	 * Children
	 *
	 * @var Data_Tree_Node[]
	 */
	protected $children = array();
	
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
	 *
	 * @var Data_Tree_Node[]
	 */
	protected $_iterator_data = array();

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

		$this->is_root = !(bool)$this->parent_ID;

		if( !$this->is_root ) {

			$this->parent = $tree->getNode( $this->parent_ID );

			if(!$this->parent) {
				throw new Data_Tree_Exception(
					"Inconsistent tree data. Parent node '{$this->parent_ID}' does not exist. Node ID: '{$this->ID}' ",
					Data_Tree_Exception::CODE_INCONSISTENT_TREE_DATA
				);
			}

			$this->depth = $this->parent->getDepth() + 1;

			$this->parent->appendChild($this);
		} else {
			$root_node = $this->tree->getRootNode();
			if( $root_node ) {
				throw new Data_Tree_Exception(
					"Node: '{$this->ID}'. Parent ID is not defined, but root node already exist (Root node ID: '{$root_node->getID()}'). There can be only one root. Please check data, or use Data_Forest if you need. ",
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
				"Child '{$ID}' already exists!",
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
	 *
	 * @return array
	 */
	public function getHasChildren(){
		return (bool)$this->children;
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
		$result = array();

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
		$this->resetIteratorData();
	}



	/**
	 *
	 * @return array
	 */
	public function jsonSerialize(){

		$props = array(
			"ID",
			"parent_ID",
			"depth",
			"data",
			"children",
			"is_root"
		);

		$output = array();
		foreach($props as $prop){
			$output[$prop] = $this->{$prop};
		}


		return $output;
	}

	/**
	 * Don't serialize bound tree
	 *
	 * @return array
	 */
	public function __sleep(){
		return array(
			"ID",
			"data",
			"parent",
			"children",
			"depth",
			"is_root"
		);
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

		$result = array();
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
			$item[$children_key] = array();

			foreach($this->children as $child) {
				$child->_toArray( $item[$children_key], $max_depth, $root_depth );
			}

		}

		if($this->is_root) {
			$_result = $item;
		} else {
			//$_result[$this->ID] = $item;
			$_result[] = $item;
		}

	}


	/**
	 *
	 */
	public function resetIteratorData() {
		$this->_iterator_data = array();
	}

	/**
	 *
	 */
	protected function _prepareIteratorData(){

		$this->__prepareIteratorData($this->_iterator_data, $this->_max_depth, $this->depth);
	}

	/**
	 * @param array &$result
	 * @param int|null $max_depth
	 * @param int|null $root_depth
	 */
	protected function __prepareIteratorData( &$result, $max_depth, $root_depth ){

		$result[$this->ID] = $this;

		if($max_depth) {
			if( ($root_depth-$this->depth)>$max_depth ) {
				return;
			}
		}

		foreach($this->children as $child){
			$child->__prepareIteratorData( $result, $max_depth, $root_depth );
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
		if(!$this->_iterator_data){
			$this->_prepareIteratorData();
		}
		reset($this->_iterator_data);
	}

	/**
	 * @return Data_Tree_Node
	 */
	public function current() {
		if(!$this->_iterator_data){
			$this->_prepareIteratorData();
		}
		return current($this->_iterator_data);
	}

	/**
	 * @return mixed
	 */
	public function key() {
		if(!$this->_iterator_data){
			$this->_prepareIteratorData();
		}
		return key($this->_iterator_data);
	}

	/**
	 *
	 */
	public function next() {
		if(!$this->_iterator_data){
			$this->_prepareIteratorData();
		}
		next($this->_iterator_data);
	}

	/**
	 * @return bool
	 */
	public function valid() {
		if(!$this->_iterator_data){
			$this->_prepareIteratorData();
		}

		return key($this->_iterator_data)!==null;
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