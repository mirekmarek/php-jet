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
 * @package DataModel
 * @subpackage DataModel_ID
 */
namespace Jet;

abstract class DataModel_ID_Abstract extends Object implements \ArrayAccess,\Iterator {
	/**
	 *
	 * @var string
	 */
	protected $data_model_class_name;

	/**
	 * array key: ID property name
	 * array value: ID value
	 *
	 * @var array
	 */
	protected $values = array();


	/**
	 * @param DataModel $data_model
	 */
	public function  __construct( DataModel $data_model ) {
		$this->data_model_class_name = $data_model->getDataModelDefinition()->getClassName();

		foreach(array_keys( $data_model->getIDProperties() ) as $ID_p_n) {
			$this->values[$ID_p_n] = null;
		}
	}

	/**
	 * @return string
	 */
	public function getDataModelClassName() {
		return $this->data_model_class_name;
	}

	/**
	 * @return array
	 */
	public function getValues() {
		return $this->values;
	}

	/**
	 * @return array
	 */
	public function __sleep() {
		return array(
				'data_model_class_name',
				//'ID_properties_names',
				'values'
			);
	}

	/**
	 *
	 */
	public function __wakeup() {
	}

	/**
	 * @return string
	 */
	public function  __toString() {
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString() {
		return implode( ':', $this->values );
	}

	/**
	 * @param string $ID
	 *
	 * @return DataModel_ID_Abstract
	 */
	public function unserialize( $ID ) {
		$ID = explode(':', $ID);
		foreach(array_keys($this->values) as $k ) {
			if(!$ID) {
				break;
			}
			$val = array_shift( $ID );

			$this->values[$k] = $val;
		}

		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function getWhere() {
		$where = array();

		foreach($this->values as $key=>$value) {
			if($value===null) {
				continue;
			}

			if($where) {
				$where[] = 'AND';
			}

			$where['this.'.$key] = $value;
		}

		return $where;
	}

	/**
	 * @return int
	 */
	abstract public function getMaxLength();

	/**
	 *
	 * @param DataModel $data_model_instance
	 * @param string $object_name
	 *
	 * @return string
	 */
	abstract public function generateID( DataModel $data_model_instance, $object_name );

	/**
	 * @param DataModel $data_model_instance
	 * @param string|DataModel_ID_Abstract $ID
	 *
	 * @return bool
	 */
	public function getIDExists( DataModel $data_model_instance, $ID ) {
		return $data_model_instance->getIDExists($ID);
	}


	/**
	 * Returns true if the ID format is valid
	 *
	 * @param string $ID
	 * @return bool
	 */
	abstract public function checkFormat( $ID );


	/**
	 * @return string
	 */
	public static function generateUniqueID() {
		$time = floor(microtime(true) * 1000);

		$unique_ID = uniqid('', true);

		$u_name = substr(php_uname('n'), 0,14);

		$ID = $u_name.$time .$unique_ID;

		$ID = substr( preg_replace('~[^a-zA-Z0-9]~', '_', $ID), 0, 50);

		return $ID;
	}


	/**
	 * @param string $ID
	 *
	 * @return DataModel_ID_Abstract
	 */
	public function createID( $ID ) {
		return $this->unserialize($ID);
	}

//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
	/**
	 * @see \ArrayAccess
	 *
	 * @param mixed $offset
	 * @param mixed $value
	 *
	 * @throws DataModel_Exception
	 */
	public function offsetSet($offset, $value) {

		if(!array_key_exists($offset, $this->values)) {
			throw new DataModel_Exception(
					'Undefined ID part \''.$offset.'\'',
					DataModel_Exception::CODE_UNDEFINED_ID_PART
				);
		}

		$this->values[$offset] = $value;
	}

	/**
	 * @see \ArrayAccess
	 *
	 * @param mixed $offset
	 *
	 * @return mixed
	 * @throws DataModel_Exception
	 */
	public function offsetGet($offset) {

		if(!array_key_exists($offset, $this->values)) {
			throw new DataModel_Exception(
					'Undefined ID part \''.$offset.'\'',
					DataModel_Exception::CODE_UNDEFINED_ID_PART
			);
		}

		return $this->values[$offset];
	}

	/**
	 * @see \ArrayAccess
	 *
	 * @param mixed $offset
	 *
	 * @return bool
	 */
	public function offsetExists($offset) {

		return array_key_exists($offset, $this->values);
	}

	/**
	 * @see \ArrayAccess
	 *
	 * Do nothing
	 *
	 * @param mixed $offset
	 */
	public function offsetUnset($offset) {
	}

	/**
	 * @see \Iterator
	 */
	public function current() {
		return current($this->values);
	}
	/**
	 * @see \Iterator
	 * @return string
	 */
	public function key() {
		return key($this->values);
	}
	/**
	 * @see \Iterator
	 */
	public function next() {
		return next($this->values);
	}
	/**
	 * @see \Iterator
	 */
	public function rewind() {
		reset($this->values);
	}
	/**
	 * @see \Iterator
	 * @return bool
	 */
	public function valid()	{
		return key($this->values)!==null;
	}
}