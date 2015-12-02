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
	 * @var string
	 */
	protected $root_node_ID = '';

	/**
	 * @var bool
	 */
	protected $lazy_mode = false;

	/**
	 * @var bool
	 */
	protected $adopt_orphans = false;

	/**
	 * @var Data_Tree_Node[]
	 */
	protected $orphans_nodes = [];

	/**
	 *
	 * @var Data_Tree_Node[]
	 */
	protected $_iterator_map = array();

	/**
	 * @var array
	 */
	protected $__parent_map = [];

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
	 * @param string $root_node_parent_ID
	 */
	public function setRootNodeID($root_node_parent_ID) {
		$this->root_node_ID = $root_node_parent_ID;
	}

	/**
	 * @return string
	 */
	public function getRootNodeID() {
		return $this->root_node_ID;
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
	 * @param bool $lazy_mode
	 */
	public function setLazyMode($lazy_mode) {
		$this->lazy_mode = (bool)$lazy_mode;
	}

	/**
	 * @return bool
	 */
	public function getLazyMode() {
		return $this->lazy_mode;
	}

	/**
	 * @param boolean $adopt_orphans
	 */
	public function setAdoptOrphans($adopt_orphans) {
		$this->adopt_orphans = $adopt_orphans;
	}

	/**
	 * @return boolean
	 */
	public function getAdoptOrphans() {
		return $this->adopt_orphans;
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
	 * @param Data_Tree_Node $node
	 */
	public function setRootNode( Data_Tree_Node $node ) {

		$node->setIsRoot( true );
		$this->root_node = $node;

		$node->getChildren();
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
		$result = [];

		if($this->root_node) {
			$result[] = $this->root_node->toArray();
		}

		foreach( $this->orphans_nodes as $orphan ) {
			$result = array_merge( $result, $orphan->toArray() );
		}

		return $result;
	}


	/**
	 * @param string $target_node_ID
	 * @return array|bool
	 */
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
     * @param array $item
     *
     * @throws Data_Tree_Exception
     *
     * @return
     */
    protected function getDataItemID( $item ) {

        if(!isset($item[$this->ID_key])) {
            throw new Data_Tree_Exception(
                'Missing \''.$this->ID_key.'\' key in item data',
                Data_Tree_Exception::CODE_MISSING_VALUE
            );

        }
        return $item[$this->ID_key];
    }

    /**
     * @param array $item
     *
     * @throws Data_Tree_Exception
     *
     * @return
     */
    protected function getDataParentItemID( $item ) {
        if(!isset($item[$this->parent_ID_key])) {
            throw new Data_Tree_Exception(
                'Missing \''.$this->parent_ID_key.'\' key in item data',
                Data_Tree_Exception::CODE_MISSING_VALUE
            );

        }
        return $item[$this->parent_ID_key];
    }


	/**
	 *
	 * @param array $item_data
	 *
	 * @throws Data_Tree_Exception
	 * @return Data_Tree_Node
	 */
	public function appendNode( array $item_data ){

		$ID = $this->getDataItemID($item_data);
        $this->getDataParentItemID($item_data);

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

		if( $this->nodes[$ID]->getIsOrphan() ) {
			$this->orphans_nodes[$ID] = $this->nodes[$ID];
		}

		//$this->resetIteratorMap();

		return $this->nodes[$ID];
	}

	/**
	 * Sets tree data
	 *
	 * @param array $data
	 */
	public function setData( array $data ) {
		$this->_setData( $data );
	}

	/**
	 *
	 * @param DataModel_Fetch_Data_Abstract $data
	 */
	public function setDataSource( DataModel_Fetch_Data_Abstract $data ) {
		$this->_setData($data);
	}

	/**
	 *
	 * @param array|DataModel_Fetch_Data_Abstract $items
	 *
	 * @throws Data_Tree_Exception
	 * @return Data_Tree
	 */
	protected function _setData( $items ){

		$this->__parent_map = array();

		$root_item = null;

		$IDs = [];

		foreach( $items as $item ) {
			$ID = $this->getDataItemID($item);
			$parent_ID = $this->getDataParentItemID($item);

			$IDs[] = $ID;

			if(!$parent_ID) {
				$parent_ID = '';
			}

			if(
				!$this->root_node_ID &&
				!$parent_ID
			) {
				$this->root_node_ID = $ID;
			}

			if($ID==$this->root_node_ID) {

				if($root_item) {
					throw new Data_Tree_Exception(
						'Multiple roots in items (root ID: \''.$this->root_node_ID.'\') ',
						Data_Tree_Exception::CODE_INCONSISTENT_TREE_DATA
					);

				}

				$root_item = $item;
			} else {

				if( !isset($this->__parent_map[$parent_ID]) ) {
					$this->__parent_map[$parent_ID] = array();
				}

				$this->__parent_map[$parent_ID][$ID] = $item;
			}


		}

		if(!$root_item) {
			if(!$this->adopt_orphans) {
				throw new Data_Tree_Exception(
					'No root item defined (root ID: \''.$this->root_node_ID.'\')',
					Data_Tree_Exception::CODE_INCONSISTENT_TREE_DATA
				);
			}
		} else {
			$this->appendNode( $root_item );

			$this->__setData( $this->root_node_ID );
		}


		if($this->__parent_map ) {
			if($this->adopt_orphans) {

				$parent_IDs = array_keys($this->__parent_map);

				$non_exists_parent_IDs = array_diff($parent_IDs, $IDs);

				foreach( $non_exists_parent_IDs as $non_exists_parent_ID ) {
					foreach( $this->__parent_map[$non_exists_parent_ID] as $orphan_ID=>$orphan_item ) {
						$this->appendNode( $orphan_item );

						$this->__setData( $orphan_ID );
					}
				}
			} else {
				throw new Data_Tree_Exception(
					'Inconsistent tree data. There are orphans.',
					Data_Tree_Exception::CODE_INCONSISTENT_TREE_DATA
				);

			}
		}
	}

	/**
	 * @param string $parent_ID
	 */
	protected function __setData( $parent_ID ) {
		if(!isset($this->__parent_map[$parent_ID])) {
			return;
		}

		foreach( $this->__parent_map[$parent_ID] as $ID=>$item_data ) {
			$node = $this->appendNode( $item_data );
			unset($this->__parent_map[$parent_ID][$ID]);

			if(!$this->lazy_mode) {
				$this->__setData($ID);
			} else {
				if(
					$node->getDepth()<1
				) {
					$this->__setData($ID);
				} else {
					if(!empty($this->__parent_map[$ID])) {
						$node->setHasChildren();
					}
				}

			}
		}
		unset( $this->__parent_map[$parent_ID] );

	}


	/**
	 *
	 */
	public function resetIteratorMap() {
		$this->_iterator_map = [];

		foreach($this->nodes as $node) {
			$node->resetIteratorMap();
		}
	}

	/**
	 *
	 */
	public function getIteratorMap(){
		if($this->_iterator_map) {
			return $this->_iterator_map;
		}

		$this->_iterator_map = [];

		if($this->root_node) {
			$this->_iterator_map = $this->root_node->getIteratorMap();
		}

		foreach( $this->orphans_nodes as $orphan ) {
			$this->_iterator_map += $orphan->getIteratorMap();
		}

		return $this->_iterator_map;
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

		$data = $this->jsonSerialize();

		return json_encode( $data );
	}

	/**
	 * @return string
	 */
	public function toXML() {
		$data = $this->jsonSerialize();

		return $this->_XMLSerialize($data, 'tree');
	}

	/**
	 * @return array
	 */
	public function jsonSerialize() {


		$data = array(
			'identifier' => $this->ID_key,
			'label' => $this->label_key,
			'items' => $this->toArray()
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

		foreach($data as $key=>$val) {
			if(is_array($val) || is_object($val)) {
				if(is_int($key)) {
					$key = 'item';
				}
				$result .= $this->_XMLSerialize($val, $key, $prefix . JET_TAB);
			} else {
				if(is_bool($val)) {
					$result .= $prefix.JET_TAB.'<'.$key.'>'.($val?1:0).'</'.$key.'>'.JET_EOL;

				} else {
					$result .= $prefix.JET_TAB.'<'.$key.'>'.Data_Text::htmlSpecialChars($val).'</'.$key.'>'.JET_EOL;
				}
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
	public function count(){
		return count($this->nodes);
	}

}