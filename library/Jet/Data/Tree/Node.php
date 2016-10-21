<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Data
 * @subpackage Data_Tree
 */
namespace Jet;


class Data_Tree_Node extends BaseObject implements \Iterator, \Countable, \JsonSerializable, Form_Field_Select_Option_Interface  {

	/**
	 *
	 * @var Data_Tree
	 */
	protected $_tree;

	/**
	 *
	 * @var string 
	 */
	protected $ID;

	/**
	 *
	 * @var string
	 */
	protected $parent_ID = '';
	/**
	 *
	 * @var string
	 */
	protected $real_parent_ID = '';

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
	 * @param mixed $data
	 * @param bool $is_root
	 * @param int|string $ID
	 * @param int|string $parent_ID
	 * @param string $label
	 */
	public function __construct( Data_Tree $tree, $data, $is_root=false, $ID, $parent_ID, $label ){
		$this->_tree = $tree;
		$this->is_root = $is_root;
		$this->ID = $ID;
		$this->parent_ID = $parent_ID;
		$this->label = $label;

		$this->data = $data;

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
			isset($this->_children[$ID])
		) {
			throw new Data_Tree_Exception(
				'Child \''.$ID.'\' already exists!',
				Data_Tree_Exception::CODE_NODE_ALREADY_EXISTS
			);
		}

		$this->_children[$ID] = $node;
	}


	/**
	 *
	 * @return Data_Tree
	 */
	public function getTree(){
		return $this->_tree;
	}

	/**
	 * @param string $ID
	 */
	public function setID($ID)
	{
		$this->ID = $ID;
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
	 * @return string
	 */
	public function getRealParentID()
	{
		return $this->real_parent_ID;
	}

	/**
	 * @param Data_Tree_Node $_parent
	 */
	public function setParent( Data_Tree_Node $_parent)
	{
		$this->_parent = $_parent;
		$this->parent_ID = $_parent->getID();

		$this->depth = $_parent->getDepth() + 1;
	}

	/**
	 *
	 * @return Data_Tree_Node
	 */
	public function getParent(){
		return $this->_parent;
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
	 * @return mixed
	 */
	public function getData(){
		return $this->data;
	}

	/**
	 * @param mixed $data
	 */
	public function setData($data) {
		$this->data = $data;
	}

	/**
	 *
	 * @return bool
	 */
	public function getHasChildren(){
		return (bool)$this->_children;
	}

	/**
	 *
	 */
	public function setHasChildren(){
		$this->_children = true;
	}

	/**
	 *
	 * @return array
	 */
	public function getChildren(){
		return $this->_children;
	}

	/**
	 *
	 * @param string $ID
	 *
	 * @return bool
	 */
	public function getChildExists($ID){
		return isset( $this->_children[$ID] );
	}


	/**
	 *
	 * @param string $child_ID
	 *
	 * @return Data_Tree_Node
	 */
	public function getChild($child_ID){
		return $this->_children[$child_ID];
	}


	/**
	 *
	 * @return Data_Tree_Node[]
	 */
	public function getPathToRoot(){
		$result = [];

		$result[$this->ID] = $this;
		$_node = $this;

		while( $_node->_parent ){
			$_node = $_node->_parent;
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
	 * @param string $label
	 */
	public function setLabel($label)
	{
		$this->label = $label;
	}

	/**
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
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

		$ID_key = $this->_tree->getIDKey();
		$parent_ID_key = $this->_tree->getParentIDKey();
		$children_key = $this->_tree->getChildrenKey();
		$depth_key = $this->_tree->getDepthKey();
		$label_key = $this->_tree->getLabelKey();

		$next_children = true;
		if($max_depth) {
			if( ($root_depth-$this->depth)>$max_depth ) {
				$next_children = false;
			}
		}

		$item = $this->data;
		$item[$ID_key] = $this->ID;
		$item[$parent_ID_key] = $this->is_orphan ? $this->real_parent_ID : $this->parent_ID;
		$item[$label_key] = $this->label;
		$item[$depth_key] = $this->depth;

		if($next_children && $this->_children){

			if(
				$this->is_root ||
				!$this->_tree->getLazyMode()
			) {
				$item[$children_key] = [];

				foreach($this->_children as $child) {
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

		foreach($this->_children as $child){
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

	//- Form_Field_Select_Option_Interface --------------------------------------------------------
	//- Form_Field_Select_Option_Interface --------------------------------------------------------
	//- Form_Field_Select_Option_Interface --------------------------------------------------------
	//- Form_Field_Select_Option_Interface --------------------------------------------------------
	//- Form_Field_Select_Option_Interface --------------------------------------------------------
	/**
	 * @param string $css_style
	 */
	public function setSelectOptionCssStyle($css_style)
	{
		$this->select_option_css_style = $css_style;
	}

	/**
	 * @return string
	 */
	public function getSelectOptionCssStyle()
	{
		return $this->select_option_css_style;
	}

	/**
	 * @param string $css_class
	 */
	public function setSelectOptionCssClass($css_class)
	{
		$this->select_option_css_class = $css_class;
	}

	/**
	 * @return string
	 */
	public function getSelectOptionCssClass()
	{
		return $this->select_option_css_class;
	}
}