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

class Data_Tree extends Object implements \Iterator, \Countable,Object_Serializable_REST {

	/**
	 *
	 * @var string 
	 */
	protected $ID_key = 'ID';

	/**
	 *
	 * @var string 
	 */
	protected $parent_ID_key = 'parent_ID';

	/**
	 *
	 * @var string
	 */
	protected $label_key = 'name';

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
	protected $nodes_class_name = 'Jet\Data_Tree_Node';
		
	/**
	 *
	 * @var Data_Tree_Node[]
	 */
	protected $nodes = array();
	
	/**
	 *
	 * @var Data_Tree_Node
	 */
	protected $root_node = null;

	/**
	 *
	 * @var array
	 */
	protected $traversal_iterator_nodes = array();

	/**
	 *
	 * @param string $ID_key (optional, default: ID)
	 * @param string $parent_ID_key (optional,default: parent_ID)
	 *
	 */
	public function __construct($ID_key = 'ID', $parent_ID_key = 'parent_ID' ) {
		$this->ID_key = $ID_key;
		$this->parent_ID_key = $parent_ID_key;

	}

	/**
	 * @param string $nodes_class_name
	 *
	 *
	 * @throws Data_Tree_Exception
	 */
	public function setNodeClassName( $nodes_class_name ) {
		if(
			$nodes_class_name !== 'Jet\Data_Tree_Node' &&
			!is_subclass_of($nodes_class_name, 'Jet\Data_Tree_Node')
		) {
			throw new Data_Tree_Exception(
				'Tree node class \''.$nodes_class_name.'\' must be Jet\Data_Tree_Node class or descendant class',
				Data_Tree_Exception::CODE_INVALID_NODES_CLASS
			);
		}

		$this->nodes_class_name = $nodes_class_name;

	}

	/**
	 * Class of nodes
	 *
	 * @return string
	 */
	public function getNodesClassName(){
		return $this->nodes_class_name;
	}

	/**
	 * Key in data item representing ID
	 *
	 * @return string 
	 */
	public function getIDKey(){
		return $this->ID_key;
	}
	
	/**
	 * Key in data item representing parent ID
	 *
	 * @return string 
	 */
	public function getParentIDKey(){
		return $this->parent_ID_key;
	}

	/**
	 * @param string $label_key
	 */
	public function setLabelKey($label_key) {
		$this->label_key = $label_key;
	}

	/**
	 * @return string
	 */
	public function getLabelKey() {
		return $this->label_key;
	}

	/**
	 * @param string $children_key
	 */
	public function setChildrenKey( $children_key ) {
		$this->children_key = $children_key;
	}

	/**
	 * @return string
	 */
	public function getChildrenKey() {
		return $this->children_key;
	}


	/**
	 * @param string $depth_key
	 *
	 */
	public function setDepthKey( $depth_key ) {
		$this->depth_key = $depth_key;
	}

	/**
	 * @return string
	 */
	public function getDepthKey() {
		return $this->depth_key;
	}

	/**
	 * Get root node if defined, else NULL
	 *
	 * @return Data_Tree_Node
	 */
	public function getRootNode(){
		if(!$this->root_node) {
			return null;
		}
		return $this->root_node;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getRootID(){
		if(!$this->root_node) {
			return null;
		}
		return $this->root_node->getID();
	}
		
	/**
	 *
	 * @param string $node_ID
	 *
	 * @return Data_Tree_Node|null
	 */
	public function getNode($node_ID){
		if(!isset($this->nodes[$node_ID])) {
			return null;
		}

		return $this->nodes[$node_ID];
	}

	
	/**
	 * Returns all tree nodes
	 *
	 * @return Data_Tree_Node[]
	 */
	public function getNodes(){
		return $this->nodes;
	}

	/**
	 *
	 * @return string[]
	 */
	public function getNodesIDs(){
		return array_keys($this->nodes);
	}

	/**
	 *
	 * @param string $node_ID
	 *
	 * @return bool
	 */
	public function getNodeExists($node_ID) {
		return isset($this->nodes[$node_ID]);
	}
	
	/**
	 *
	 * @return array
	 */
	public function toArray(){
		return $this->root_node->toArray();
	}


	/**
	 *
	 * @param array $item_data
	 *
	 * @throws Data_Tree_Exception
	 * @return Data_Tree_Node
	 */
	public function appendNode( array $item_data ){

		if( !isset($item_data[$this->ID_key]) ){
			throw new Data_Tree_Exception(
				'Missing \''.$this->ID_key.'\' key in item data (ID_key)',
				Data_Tree_Exception::CODE_MISSING_VALUE
			);
		}
		if( !isset($item_data[$this->parent_ID_key]) ){
			throw new Data_Tree_Exception(
				'Missing \''.$this->parent_ID_key.'\' key in item data (parent_ID_key)',
				Data_Tree_Exception::CODE_MISSING_VALUE
			);
		}

		$ID = $item_data[$this->ID_key];

		if( isset($this->nodes[$ID]) ){
			throw new Data_Tree_Exception(
				'Node \''.$ID.'\' already exists',
				Data_Tree_Exception::CODE_NODE_ALREADY_EXISTS
			);
		}

		$this->nodes[$ID] = new $this->nodes_class_name( $this, $item_data );

		if( $this->nodes[$ID]->getIsRoot() ){
			$this->root_node = $this->nodes[$ID];
		}

		$this->resetIteratorData();

		return $this->nodes[$ID];
	}

	/**
	 * Sets tree data
	 *
	 * @param array $data
	 */
	public function setData( array $data ) {
		$this->_setData($data);
	}

	/**
	 *
	 * @param DataModel_Fetch_Data_Abstract $data
	 */
	public function setDataSource( DataModel_Fetch_Data_Abstract $data ) {
		$this->_setData($data);
	}

	public function getPath( $target_node_ID ) {
		$target_node_ID = (string)$target_node_ID;
		$target_node = $this->getNode( $target_node_ID );

		if(!$target_node) {
			return false;
		}

		$path = array();
		$path[] = $target_node->getID();

		while( ($parent=$target_node->getParent()) ) {
			$path[] = $parent->getID();
			$target_node = $parent;
		}

		$path = array_reverse($path);

		return $path;
	}

	/**
	 *
	 * @param array|DataModel_Fetch_Data_Abstract $items
	 *
	 * @throws Data_Tree_Exception
	 * @return Data_Tree
	 */
	protected function _setData( $items ){

		$parent_map = array();

		foreach( $items as $item ) {
			$ID = $item[$this->ID_key];
			$parent_ID = $item[$this->parent_ID_key];

			if(!$parent_ID) {
				$parent_ID = '';
			}

			if( !isset($parent_map[$parent_ID]) ) {
				$parent_map[$parent_ID] = array();
			}

			$parent_map[$parent_ID][$ID] = $item;
		}

		if(!isset($parent_map[''])) {
			throw new Data_Tree_Exception(
				'No root item defined',
				Data_Tree_Exception::CODE_INCONSISTENT_TREE_DATA
			);
		}

		if(count($parent_map[''])>1) {
			throw new Data_Tree_Exception(
				'Multiple roots in items or parent ID key \''.$this->parent_ID_key.'\' not found in item',
				Data_Tree_Exception::CODE_INCONSISTENT_TREE_DATA
			);

		}

		$this->__setData( '', $parent_map );

	}

	/**
	 * @param string $parent_ID
	 * @param array &$parent_map
	 */
	protected function __setData( $parent_ID, &$parent_map ) {
		if(!isset($parent_map[$parent_ID])) {
			return;
		}

		foreach( $parent_map[$parent_ID] as $ID=>$item_data ) {
			$this->appendNode( $item_data );
			unset($parent_map[$parent_ID][$ID]);
			$this->__setData($ID, $parent_map);
		}
		unset( $parent_map[$parent_ID] );

	}


	/**
	 *
	 */
	protected function resetIteratorData() {
		foreach($this->nodes as $node) {
			$node->resetIteratorData();
		}
	}



	//- Jet\Mvc_Controller_REST_Serializable ----------------------------------------------------------------------
	//- Jet\Mvc_Controller_REST_Serializable ----------------------------------------------------------------------
	//- Jet\Mvc_Controller_REST_Serializable ----------------------------------------------------------------------
	//- Jet\Mvc_Controller_REST_Serializable ----------------------------------------------------------------------
	//- Jet\Mvc_Controller_REST_Serializable ----------------------------------------------------------------------
	//- Jet\Mvc_Controller_REST_Serializable ----------------------------------------------------------------------

	/**
	 * @return string
	 */
	public function toJSON( ) {

		$data = $this->_toJsonOrXMLDataPrepare();

		return json_encode( $data );
	}

	/**
	 * @return string
	 */
	public function toXML() {
		$data = $this->_toJsonOrXMLDataPrepare();

		return $this->_XMLSerialize($data, 'tree');
	}

	/**
	 * @return array
	 */
	protected function _toJsonOrXMLDataPrepare() {

		$data = array(
			'identifier' => $this->ID_key,
			'label' => $this->label_key,
			'items' => array($this->toArray())
		);

		return $data;

	}

	/**
	 * @param mixed $data
	 * @param string $tag
	 * @param string $prefix
	 *
	 * @return string
	 */
	protected function _XMLSerialize( $data, $tag, $prefix='' ) {
		$result = $prefix.'<'.$tag.'>'.JET_EOL;

		if(is_object($data)) {
			$data = get_class_vars($data);
		}

		if(!is_array($data)) {
			$result = $prefix.'<'.$tag.'></'.$tag.'>'.JET_EOL;
			return $result;
		}

		foreach($data as $key=>$val) {
			if(is_array($val) || is_object($val)) {
				if(is_int($key)) {
					$key = 'item';
				}
				$result .= $this->_XMLSerialize($val, $key, $prefix . JET_TAB);
			} else {
				$result .= $prefix.JET_TAB.'<'.$key.'>'.htmlspecialchars($val).'</'.$key.'>'.JET_EOL;
			}
		}
		$result .= $prefix.'</'.$tag.'>'.JET_EOL;
		return $result;
	}


	//- Iterator ----------------------------------------------------------------------------------
	//- Iterator ----------------------------------------------------------------------------------
	//- Iterator ----------------------------------------------------------------------------------
	//- Iterator ----------------------------------------------------------------------------------
	//- Iterator ----------------------------------------------------------------------------------
	public function rewind() {
		$this->root_node->rewind();
	}

	/**
	 * @return Data_Tree_Node|null
	 */
	public function current() {
		return $this->root_node->current();
        }

	/**
	 * @return mixed
	 */
	public function key() {
		return $this->root_node->key();
	}

	/**
	 *
	 */
	public function next() {
		$this->root_node->next();
	}

	/**
	 * @return bool
	 */
	public function valid() {
		return $this->root_node->valid();
	}

	//- Countable ---------------------------------------------------------------------------------
	//- Countable ---------------------------------------------------------------------------------
	//- Countable ---------------------------------------------------------------------------------
	//- Countable ---------------------------------------------------------------------------------
	/**
	 * @return int
	 */
	public function count(){
		return count($this->nodes);
	}

}