<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;


/**
 * Class DataModel_Definition_Relations
 * @package Jet
 */
class DataModel_Definition_Relations extends BaseObject implements \ArrayAccess, \Iterator, \Countable {

	/**
	 * @var DataModel_Definition_Model_Abstract
	 */
	protected $data_model_definition;

	/**
	 * @var DataModel_Definition_Relation_Abstract[]
	 */
	protected $relations = [];

	/**
	 * DataModel_Definition_Relations constructor.
	 * @param DataModel_Definition_Model_Abstract $data_model_definition
	 */
	public function __construct( DataModel_Definition_Model_Abstract  $data_model_definition )
	{
		$this->data_model_definition = $data_model_definition;
	}

	/**
	 * @param string $related_model_name
	 * @param DataModel_Definition_Relation_Abstract $relation
	 *
	 * @throws DataModel_Exception
	 */
	public function addRelation( $related_model_name, DataModel_Definition_Relation_Abstract $relation) {


		if(isset($this->relations[ $related_model_name ])) {
			$prev = $this->relations[$related_model_name]->getRelatedDataModelClassName();
			$current = $relation->getRelatedDataModelClassName();


			throw new DataModel_Exception(
				'Data model name ('.$related_model_name.') collision: '.$prev.' vs '.$current,
				DataModel_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		$this->relations[$related_model_name] = $relation;

	}

	/**
	 * @param string $related_model_name
	 *
	 * @return DataModel_Definition_Relation_Abstract
	 *
	 * @throws DataModel_Exception
	 */
	public function getRelation( $related_model_name ) {

		if(!isset($this->relations[$related_model_name])) {
			throw new DataModel_Exception(
				'Unknown relation \''.$this->data_model_definition->getModelName().'\' <-> \''.$related_model_name.'\' (Class: \''.$this->data_model_definition->getClassName().'\') '
			);
		}
		return $this->relations[$related_model_name];

	}

//----------------------------------------------

	/**
	 * @see \Countable
	 *
	 * @return int
	 */
	public function count() {
		return count($this->relations);
	}

	/**
	 * @see \ArrayAccess
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists( $offset  ) {
		return isset($this->relations[$offset]);
	}
	/**
	 * @see \ArrayAccess
	 * @param mixed $offset
	 *
	 * @return DataModel_Definition_Relation_Abstract
	 */
	public function offsetGet( $offset ) {
		return $this->getRelation($offset);
	}

	/**
	 *
	 * @see \ArrayAccess
	 *
	 * @param string $offset
	 * @param DataModel_Definition_Relation_Abstract $value
	 *
	 */
	public function offsetSet( $offset , $value ) {
		$this->addRelation($offset, $value);
	}

	/**
	 * @see \ArrayAccess
	 *
	 * @param string $offset
	 */
	public function offsetUnset( $offset )	{
		unset($this->relations[$offset]);
	}

	/**
	 * @see \Iterator
	 *
	 * @return DataModel
	 */
	public function current() {
		if( $this->relations===null ) {
			return null;
		}
		return current($this->relations);
	}
	/**
	 * @see \Iterator
	 *
	 * @return string
	 */
	public function key() {
		if( $this->relations===null ) {
			return null;
		}
		return key($this->relations);
	}
	/**
	 * @see \Iterator
	 */
	public function next() {
		if( $this->relations===null ) {
			return null;
		}
		return next($this->relations);
	}
	/**
	 * @see \Iterator
	 */
	public function rewind() {
		if( $this->relations!==null ) {
			reset($this->relations);
		}
	}
	/**
	 * @see \Iterator
	 * @return bool
	 */
	public function valid()	{
		if( $this->relations===null ) {
			return false;
		}
		return key($this->relations)!==null;
	}


}
