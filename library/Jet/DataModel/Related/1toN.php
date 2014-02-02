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
 * @abstract
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Related
 */
namespace Jet;

abstract class DataModel_Related_1toN extends DataModel_Related_Abstract implements \ArrayAccess, \Iterator, \Countable   {

	/**
	 * @var DataModel_Related_1toN[]
	 */
	public $__items = null;

	/**
	 * @var DataModel_Related_1to1[]
	 */
	private $__deleted_items = array();

	/**
	 * @var bool
	 */
	private $__is_item = false;


	/**
	 * @param $data_model_class_name
	 *
	 * @return DataModel_Definition_Model_Related_Abstract
	 */
	protected static function _getDataModelDefinitionInstance( $data_model_class_name ) {
		return new DataModel_Definition_Model_Related_1toN( $data_model_class_name );
	}


	/**
	 * @return mixed|null
	 */
	public function getArrayKeyValue() {
		return null;
	}

	/**
	 * Loads DataModel.
	 *
	 * @param DataModel $main_model_instance
	 * @param DataModel_Related_Abstract $parent_model_instance
	 *
	 * @throws DataModel_Exception
	 * @return DataModel
	 */
	public function loadRelated( DataModel $main_model_instance, DataModel_Related_Abstract $parent_model_instance=null  ) {

		$backend = $this->getBackendInstance();

		$model_definition = $this->getDataModelDefinition();

		$query = new DataModel_Query( $this );
		$query->setWhere(array());

		$query->getWhere()->attach(
			$main_model_instance->getIDQuery(  $model_definition->getMainModelRelationIDProperties() )->getWhere()
		);

		if($parent_model_instance) {
			$query->getWhere()->attach(
				$parent_model_instance->getIDQuery(  $model_definition->getParentModelRelationIDProperties() )->getWhere()
			);
		}

		$query->setSelect( $model_definition->getProperties() );

		$data = $backend->fetchAll( $query );

		$this->__items = array();

		if(!$data) {
			return $this;
		}

		foreach($data as $dat) {

			/**
			 * @var DataModel_Related_1toN $loaded_instance
			 */
			$loaded_instance = $this->_load_dataToInstance( $dat, $main_model_instance );
			$loaded_instance->__is_item = true;

			/**
			 * @var DataModel_Related_1toN $loaded_instance
			 */
			$key = $loaded_instance->getArrayKeyValue();


			if($key!==null) {
				$this->__items[$key] = $loaded_instance;
			} else {
				$this->__items[] = $loaded_instance;
			}
		}

		$this->__deleted_items = array();

		return $this;
	}

	/**
	 * Validates data and returns true if everything is OK and ready to save
	 *
	 * @throws DataModel_Exception
	 * @return bool
	 */
	public function validateProperties() {
		if( !$this->__items ) {
			return true;
		}

		foreach($this->__items as $d) {
			if( !$d->_validateItem() ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @return bool
	 */
	protected function _validateItem() {
		return parent::validateProperties();
	}

	/**
	 * Save data.
	 * CAUTION: Call validateProperties first!
	 *
	 * @param DataModel $main_model_instance (optional)
	 * @param DataModel_Related_Abstract $parent_model_instance (optional)
	 *
	 * @throws Exception
	 * @throws DataModel_Exception
	 */
	public function saveRelated( DataModel $main_model_instance, DataModel_Related_Abstract $parent_model_instance=null ) {

		if( !$this->__items ) {
			foreach($this->__deleted_items as $item) {
				$item->delete();
			}
			return;
		}

		if($parent_model_instance) {
			foreach($this->__items as $d) {
				$d->_saveItem( $main_model_instance, $parent_model_instance );
			}
		} else {
			foreach($this->__items as $d) {
				$d->_saveItem( $main_model_instance );
			}
		}

		foreach($this->__deleted_items as $item) {
			/**
			 * @var DataModel_Related_1toN $item
			 */
			$item->_deleteItem();
		}
	}

	/**
	 * Save data.
	 * CAUTION: Call validateProperties first!
	 *
	 * @param DataModel $main_model_instance (optional)
	 * @param DataModel_Related_Abstract $parent_model_instance (optional)
	 *
	 * @throws Exception
	 * @throws DataModel_Exception
	 */
	protected function _saveItem( DataModel $main_model_instance=null, DataModel_Related_Abstract $parent_model_instance=null ) {
		if($parent_model_instance) {
			parent::saveRelated( $main_model_instance, $parent_model_instance);
		} else {
			parent::saveRelated( $main_model_instance );
		}
	}

	/**
	 *
	 * @throws DataModel_Exception
	 */
	public function delete() {
		foreach($this->__deleted_items as $item) {
			$item->delete();
		}

		if( !$this->__items ) {
			return;
		}

		foreach($this->__items as $d) {
			if($d->getIsSaved()) {
				$d->_deleteItem();
			}
		}
	}

	protected function _deleteItem() {
		parent::delete();
	}


	/**
	 * @return array
	 */
	public function jsonSerialize() {

		if( $this->__is_item) {
			return parent::jsonSerialize();
		}


		$res = array();

		if(!$this->__items) {
			return $res;
		}

		foreach($this->__items as $k=>$d) {
			$res[$k] = $d->jsonSerialize();
		}

		return $res;

	}


	/**
	 * @param string $prefix
	 *
	 * @return string
	 */
	protected function _XMLSerialize( $prefix='' ) {

		if( $this->__is_item) {
			return parent::_XMLSerialize($prefix);
		}

		$res = array();
		foreach($this->__items as $d) {
			$res[] = $d->_XMLSerialize($prefix);
		}

		return implode(JET_EOL,$res);
	}

//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------

	public function clearData() {
		if($this->__items) {
			$this->__deleted_items = $this->__items;
		}
		$this->__items = array();
	}

	/**
	 * @see \Countable
	 *
	 * @return int
	 */
	public function count() {
		return count($this->__items);
	}

	/**
	 * @see \ArrayAccess
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists( $offset  ) {
		return isset($this->__items[$offset]);
	}
	/**
	 * @see \ArrayAccess
	 * @param mixed $offset
	 *
	 * @return DataModel
	 */
	public function offsetGet( $offset ) {
		return $this->__items[$offset];
	}

	/**
	 *
	 * @see \ArrayAccess
	 *
	 * @param mixed $offset
	 * @param DataModel_Related_1toN $value
	 *
	 * @throws DataModel_Exception
	 */
	public function offsetSet( $offset , $value ) {

		$valid_class = get_class($this);

		if( !($value instanceof $valid_class) ) {
			throw new DataModel_Exception(
				'New item must be instance of \''.$valid_class.'\' class. \''.get_class($value).'\' given.',
				DataModel_Exception::CODE_INVALID_CLASS
			);
		}

		if(is_null($offset)) {
			/**
			 * @var DataModel_Related_1toN $value
			 */
			$offset = $value->getArrayKeyValue();
		}

		$value->__is_item = true;

		if(is_null($offset)) {
			$this->__items[] = $value;
		} else {
			$this->__items[$offset] = $value;
		}
	}

	/**
	 * @see \ArrayAccess
	 * @param mixed $offset
	 */
	public function offsetUnset( $offset )	{
		$this->__deleted_items[] = $this->__items[$offset];
		unset( $this->__items[$offset] );
	}

	/**
	 * @see \Iterator
	 *
	 * @return DataModel
	 */
	public function current() {
		if( $this->__items===null ) {
			return null;
		}
		return current($this->__items);
	}
	/**
	 * @see \Iterator
	 *
	 * @return string
	 */
	public function key() {
		if( $this->__items===null ) {
			return null;
		}
		return key($this->__items);
	}
	/**
	 * @see \Iterator
	 */
	public function next() {
		if( $this->__items===null ) {
			return null;
		}
		return next($this->__items);
	}
	/**
	 * @see \Iterator
	 */
	public function rewind() {
		if( $this->__items!==null ) {
			reset($this->__items);
		}
	}
	/**
	 * @see \Iterator
	 * @return bool
	 */
	public function valid()	{
		if( $this->__items===null ) {
			return false;
		}
		return key($this->__items)!==null;
	}

	/**
	 *
	 * @return array
	 */
	public function __sleep() {
		if( $this->getIsSaved() ) {
			$items = array_keys($this->getDataModelDefinition()->getProperties());
			$items[] = '__is_item';

			return $items;
		} else {
			return array('__items', '__is_item');
		}
	}

	public function __wakeup() {
		if($this->__is_item) {
			parent::__wakeup();
		} else {
			if(!$this->__items) {
				$this->__items = array();
				$this->__deleted_items = array();
			}
		}
	}


}