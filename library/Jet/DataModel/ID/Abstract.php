<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2014 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	const MIN_LEN = 3;
	const MAX_LEN = 50;
	const MAX_SUFFIX_NO = 9999;
	const DELIMITER = '_';

	/**
	 * @var DataModel
	 */
	protected $data_model_instance;

	/**
	 * @return int
	 */
	public function getMaxLength() {
		return static::MAX_LEN;
	}

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
	 * @param DataModel_Definition_Model_Abstract $data_model_definition
	 */
	public function  __construct( DataModel_Definition_Model_Abstract $data_model_definition ) {
		$this->data_model_class_name = $data_model_definition->getClassName();

		foreach(array_keys( $data_model_definition->getIDProperties() ) as $ID_p_n) {
			$this->values[$ID_p_n] = null;
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
	 * @return string
	 */
	public function getDataModelClassName() {
		return $this->data_model_class_name;
	}

	/**
	 * @return DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related_Abstract|DataModel_Definition_Model_Related_MtoN
	 */
	public function getDataModelDefinition() {
		return DataModel_Definition_Model_Abstract::getDataModelDefinition( $this->data_model_class_name );
	}

	/**
	 * @return array
	 */
	public function getValues() {
		return $this->values;
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
	 * @param DataModel $data_model_instance
	 * @param bool $called_after_save
	 * @param mixed $backend_save_result
	 *
	 * @return void
	 */
	abstract public function generate( DataModel $data_model_instance, $called_after_save = false, $backend_save_result = null );

	/**
	 *
	 *
	 * @return DataModel_Query
	 */
	public function getQuery() {
		$data_model_definition = $this->getDataModelDefinition();

		$query = new DataModel_Query( $data_model_definition );
		if($this->data_model_instance) {
			$query->setMainDataModel( $this->data_model_instance );
		}
		$query->setWhere(array());
		$where = $query->getWhere();

        $properties = $data_model_definition->getProperties();

        foreach($this->values as $property_name => $value) {
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
	 * @return bool
	 */
	public function getExists() {
		$query = $this->getQuery();

		return (bool)$this->getDataModelDefinition()->getBackendInstance()->getCount( $query );
	}


	/**
	 * @param DataModel $data_model_instance
	 * @param string $ID_property_name
	 */
	public function generateUniqueID(
		DataModel $data_model_instance,
		$ID_property_name
	) {
		$this->data_model_instance = $data_model_instance;

		//do {
		$time = floor(microtime(true) * 1000);

		$unique_ID = uniqid('', true);

		$u_name = substr(php_uname('n'), 0,14);

		$ID = $u_name.$time .$unique_ID;

		$ID = substr( preg_replace('~[^a-zA-Z0-9]~', '_', $ID), 0, 50);

		$this->values[$ID_property_name] = $ID;
		//} while( $this->getExists() );
	}


	/**
	 *
	 * @param DataModel $data_model_instance
	 * @param string $ID_property_name
	 * @param string $object_name
	 *
	 * @throws DataModel_ID_Exception
	 * @return string
	 */
	public function generateNameID(
		DataModel $data_model_instance,
		$ID_property_name, $object_name
	) {
		$this->data_model_instance = $data_model_instance;

		$object_name  = trim( $object_name );

		$ID = Data_Text::removeAccents( $object_name );
		$ID = str_replace(' ', static::DELIMITER, $ID);
		$ID = preg_replace('/[^a-z0-9'.static::DELIMITER.']/i', '', $ID);
		$ID = strtolower($ID);
		$ID = preg_replace( '~(['.static::DELIMITER.']{2,})~', static::DELIMITER , $ID );
		$ID = substr($ID, 0, static::MAX_LEN);


		$this->values[$ID_property_name] = $ID;

		if( $this->getExists() ) {
			$_ID = substr($ID, 0, static::MAX_LEN - strlen( (string)static::MAX_SUFFIX_NO )  );

			for($c=1; $c<=static::MAX_SUFFIX_NO; $c++) {
				$this->values[$ID_property_name] = $_ID.$c;

				if( !$this->getExists()) {
					return;
				}
			}

			throw new DataModel_ID_Exception(
				'ID generate: Reached the maximum numbers of attempts. (Maximum: '.static::MAX_SUFFIX_NO.')',
				DataModel_ID_Exception::CODE_ID_GENERATE_REACHED_THE_MAXIMUM_NUMBER_OF_ATTEMPTS
			);
		}
	}


	/**
	 * Returns true if the ID format is valid
	 *
	 * @param string $ID
	 * @return bool
	 */
	public function checkFormat( $ID ) {
		return (bool)preg_match('/^([a-z0-9'.static::DELIMITER.']{'.static::MIN_LEN.','.static::MAX_LEN.'})$/', $ID);
	}



	/**
	 * @param string $ID
	 *
	 * @return DataModel_ID_Abstract
	 */
	public function createID( $ID ) {
		return $this->unserialize( $ID );
	}

	/**
	 *
	 */
	public function reset() {
		foreach( $this->values as $k=>$val ) {
			$this->values[$k] = null;
		}
	}


	/**
	 * @return array
	 */
	public function __sleep() {
		return array(
			'data_model_class_name',
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