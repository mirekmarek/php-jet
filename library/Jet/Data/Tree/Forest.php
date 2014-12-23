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

class Data_Tree_Forest extends Object implements \Iterator,\Countable, Object_Serializable_REST {

	/**
	 *
	 * @var string
	 */
	protected $label_key;

	/**
	 *
	 * @var string
	 */
	protected $ID_key;

	/**
	 * Trees instances
	 *
	 * @var Data_Tree[]
	 */
	protected $trees = array();

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
	 * Key in data item representing ID
	 *
	 * @return string
	 */
	public function getIDKey(){
		return $this->ID_key;
	}

	/**
	 * Key in data item representing ID
	 *
	 * @param $ID_key
	 */
	public function setIDKey( $ID_key ) {
		$this->ID_key = $ID_key;
	}


	/**
	 *
	 * @param Data_Tree $tree
	 *
	 * @throws Data_Tree_Exception
	 */
	public function appendTree( Data_Tree $tree ){
		$tree_ID = $tree->getRootNode()->getID();

		if( isset($this->trees[$tree_ID]) ){
			throw new Data_Tree_Exception(
				'Tree \''.$tree_ID.'\' already exists in the forest',
				Data_Tree_Exception::CODE_TREE_ALREADY_IN_FOREST
			);
		}

		if( !$this->ID_key ) {
			$this->ID_key = $tree->getIDKey();
			$this->label_key = $tree->getLabelKey();
		}



		$this->trees[$tree_ID] = $tree;
	}

	/**
	 *
	 * @return Data_Tree[]
	 */
	public function getTrees(){
		return $this->trees;
	}

	/**
	 *
	 * @param string $tree_ID
	 *
	 * @return Data_Tree
	 */
	public function getTree( $tree_ID ){
		return $this->trees[$tree_ID];
	}

	/**
	 *
	 * @param string $tree_ID
	 */
	public function removeTree( $tree_ID ) {
		if( !isset($this->trees[$tree_ID]) ){
			return;
		}
		unset($this->trees[$tree_ID]);
	}

	/**
	 *
	 * @param string $tree_ID
	 *
	 * @return bool
	 */
	public function getTreeExists( $tree_ID ){
		return isset( $this->trees[$tree_ID] );
	}


	/**
	 *
	 * @param int $max_depth (optional)
	 *
	 * @return array
	 */
	public function toArray( $max_depth=null ){

		$output = array();
		foreach($this->trees as $tree){
			if($max_depth) {
				$tree->getRootNode()->setMaxDepth( $max_depth );
			}
			//$output[$tree->getRootNode()->getID()] = $tree->toArray();
			$output[] = $tree->toArray();
		}
		return $output;
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
		$data = array();

		foreach($this->trees as $tree) {
			$data[] =  $tree->toArray();
		}

		$data = array(
			'identifier' => $this->ID_key,
			'label' => $this->label_key,
			'items' => $data
		);

		return $data;

	}

	/**
	 * @param object|array $data
	 * @param string $tag
	 * @param string $prefix (optional)
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
				$result .= $prefix.JET_EOL.'<'.$key.'>'.Data_Text::htmlSpecialChars($val).'</'.$key.'>'.JET_EOL;
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
	 * @return Data_Tree
	 */
	public function current(){
		$current_tree_ID = key($this->trees);

		return $this->trees[$current_tree_ID]->current();
	}

	/**
	 *
	 * @return Data_Tree_Node
	 */
	public function next(){
		$current_tree_ID = key($this->trees);

		$this->trees[$current_tree_ID]->next();


		if( $this->trees[$current_tree_ID]->valid() ) {
			return false;
		}

		$this->trees[$current_tree_ID]->rewind();
		$next_tree = next($this->trees);
		if(!$next_tree) {
			return false;
		}
		$next_tree->rewind();

		return $next_tree->current();
	}

	/**
	 *
	 * @return string
	 */
	public function key(){
		$current_tree_ID = key($this->trees);

		return $this->trees[$current_tree_ID]->key();
	}

	/**
	 *
	 * @return bool
	 */
	public function valid(){
		if(!key($this->trees)) {
			return false;
		}
		$current_tree_ID = key($this->trees);

		return $this->trees[$current_tree_ID]->valid();
	}

	/**
	 *
	 */
	public function rewind(){
		foreach($this->trees as $tree) {
			$tree->rewind();
		}
		reset($this->trees);
	}

	//- Countable ---------------------------------------------------------------------------------
	//- Countable ---------------------------------------------------------------------------------
	//- Countable ---------------------------------------------------------------------------------
	//- Countable ---------------------------------------------------------------------------------
	/**
	 * @return int
	 */
	public function count(){
		$result = 0;

		foreach($this->trees as  $tree) {
			$result += count($tree);
		}

		return $result;
	}

}