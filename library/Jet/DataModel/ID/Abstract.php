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
 * @package DataModel
 * @subpackage DataModel_ID
 */
namespace Jet;

abstract class DataModel_ID_Abstract extends BaseObject implements \ArrayAccess,\Iterator {

	/**
	 * @var DataModel
	 */
	protected $_data_model_instance;

	/**
	 *
	 * @var string
	 */
	protected $_data_model_class_name;

	/**
	 * array key: ID property name
	 * array value: ID value
	 *
	 * @var array
	 */
	protected $_values = [];


	/**
	 * @param DataModel_Definition_Model_Abstract $data_model_definition
	 * @param array $options
	 */
	public function  __construct( DataModel_Definition_Model_Abstract $data_model_definition, $options ) {
		$this->_data_model_class_name = $data_model_definition->getClassName();

		foreach(array_keys( $data_model_definition->getIDProperties() ) as $ID_p_n) {
			$this->_values[$ID_p_n] = null;
		}

		if($options) {
			$this->setOptions( $options );
		}

	}

	/**
	 * @param array $options
	 */
	public function setOptions( array $options ) {
		foreach( $options as $key=>$val ) {
			$this->{$key} = $val;
		}
	}

	/**
	 * @param DataModel_Interface $data_model
	 */
	public function joinDataModel( DataModel_Interface $data_model ) {
		$this->_data_model_instance = $data_model;
	}

	/**
	 * @param string $name
	 * @param mixed &$property
	 */
	public function joinObjectProperty( $name, &$property ) {
		$this->_values[$name] = &$property;
	}

	/**
	 * @param $ID_data
	 * @throws DataModel_ID_Exception
	 */
	public function init( $ID_data ) {

		$given_id_keys = [];
		if(!is_array($ID_data)) {
			foreach($this->_values as $key=> $val ) {
				$this->_values[$key] = $ID_data;
				$given_id_keys[] = $key;
				break;
			}
		} else {
			foreach($this->_values as $key=> $val ) {
				$this->_values[$key] = $ID_data[$key];
				$given_id_keys[] = $key;
				break;
			}
		}

		if( ($missing_keys=array_diff(array_keys($this->_values), $given_id_keys)) ) {
			throw new DataModel_ID_Exception('ID values missing: '.implode(', ', $missing_keys));
		}

	}

	/**
	 * @return string
	 */
	public function getDataModelClassName() {
		return $this->_data_model_class_name;
	}

	/**
	 * @return DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related_Abstract|DataModel_Definition_Model_Related_MtoN
	 */
	public function getDataModelDefinition() {
		return DataModel_Definition_Model_Abstract::getDataModelDefinition( $this->_data_model_class_name );
	}

	/**
	 *
	 *
	 * @return DataModel_Query
	 */
	public function getQuery() {
		$data_model_definition = $this->getDataModelDefinition();

		$query = new DataModel_Query( $data_model_definition );
		if($this->_data_model_instance) {
			$query->setMainDataModel( $this->_data_model_instance );
		}
		$query->setWhere([]);
		$where = $query->getWhere();

		$properties = $data_model_definition->getProperties();

		foreach($this->_values as $property_name => $value) {
			if($value===null) {
				continue;
			}


			$where->addAND();
			$where->addExpression( $properties[$property_name], DataModel_Query::O_EQUAL, $value);
		}

		return $query;
	}

	/**
	 *
	 */
	abstract public function generate();

	/**
	 * @param mixed $backend_save_result
	 *
	 */
	abstract public function afterSave( $backend_save_result );

	/**
	 * @param string $ID
	 *
	 * @return DataModel_ID_Abstract
	 */
	public function unserialize( $ID ) {
		$ID = explode(':', $ID);
		foreach(array_keys($this->_values) as $k ) {
			if(!$ID) {
				break;
			}
			$val = array_shift( $ID );

			$this->_values[$k] = $val;
		}

		return $this;
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
		return implode( ':', $this->_values );
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

		if(!array_key_exists($offset, $this->_values)) {
			throw new DataModel_Exception(
					'Undefined ID part \''.$offset.'\'',
					DataModel_Exception::CODE_UNDEFINED_ID_PART
				);
		}

		$this->_values[$offset] = $value;
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

		if(!array_key_exists($offset, $this->_values)) {
			throw new DataModel_Exception(
					'Undefined ID part \''.$offset.'\'',
					DataModel_Exception::CODE_UNDEFINED_ID_PART
			);
		}

		return $this->_values[$offset];
	}

	/**
	 * @see \ArrayAccess
	 *
	 * @param mixed $offset
	 *
	 * @return bool
	 */
	public function offsetExists($offset) {

		return array_key_exists($offset, $this->_values);
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
		return current($this->_values);
	}
	/**
	 * @see \Iterator
	 * @return string
	 */
	public function key() {
		return key($this->_values);
	}
	/**
	 * @see \Iterator
	 */
	public function next() {
		return next($this->_values);
	}
	/**
	 * @see \Iterator
	 */
	public function rewind() {
		reset($this->_values);
	}
	/**
	 * @see \Iterator
	 * @return bool
	 */
	public function valid()	{
		return key($this->_values)!==null;
	}
}