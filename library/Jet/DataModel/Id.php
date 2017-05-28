<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
abstract class DataModel_Id extends BaseObject implements \ArrayAccess, \Iterator
{

	/**
	 * @var DataModel
	 */
	protected $_data_model_instance;

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
	protected $values = [];


	/**
	 * @param DataModel_Definition_Model $data_model_definition
	 * @param array                      $options
	 */
	public function __construct( DataModel_Definition_Model $data_model_definition, $options )
	{
		$this->data_model_class_name = $data_model_definition->getClassName();

		foreach( array_keys( $data_model_definition->getIdProperties() ) as $id_p_n ) {
			$this->values[$id_p_n] = null;
		}

		if( $options ) {
			$this->setOptions( $options );
		}

	}

	/**
	 * @param array|string|int $id_data
	 *
	 * @throws DataModel_Id_Exception
	 */
	public function init( $id_data )
	{

		$given_id_keys = [];
		if( !is_array( $id_data ) ) {
			foreach( $this->values as $key => $val ) {
				$this->values[$key] = $id_data;
				$given_id_keys[] = $key;
				break;
			}
		} else {
			foreach( $this->values as $key => $val ) {
				$this->values[$key] = $id_data[$key];
				$given_id_keys[] = $key;
				break;
			}
		}

		if( ( $missing_keys = array_diff( array_keys( $this->values ), $given_id_keys ) ) ) {
			throw new DataModel_Id_Exception( 'ID values missing: '.implode( ', ', $missing_keys ) );
		}

	}

	/**
	 * @param array $options
	 */
	public function setOptions( array $options )
	{
		foreach( $options as $key => $val ) {
			$this->{$key} = $val;
		}
	}

	/**
	 * @param DataModel_Interface $data_model
	 */
	public function joinDataModel( DataModel_Interface $data_model )
	{
		$this->_data_model_instance = $data_model;
	}

	/**
	 * @param string $name
	 * @param mixed  &$property
	 */
	public function joinObjectProperty( $name, &$property )
	{
		$this->values[$name] = &$property;
	}

	/**
	 * @return string
	 */
	public function getDataModelClassName()
	{
		return $this->data_model_class_name;
	}

	/**
	 *
	 *
	 * @return DataModel_Query
	 */
	public function getQuery()
	{
		$data_model_definition = $this->getDataModelDefinition();

		$query = new DataModel_Query( $data_model_definition );

		$query->setWhere( [] );
		$where = $query->getWhere();

		$properties = $data_model_definition->getProperties();

		foreach( $this->values as $property_name => $value ) {
			if( $value===null ) {
				continue;
			}


			$where->addAND();
			$where->addExpression( $properties[$property_name], DataModel_Query::O_EQUAL, $value );
		}

		return $query;
	}

	/**
	 * @return DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related|DataModel_Definition_Model_Related_MtoN
	 */
	public function getDataModelDefinition()
	{
		return DataModel_Definition::get( $this->data_model_class_name );
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
	 * @param string $id
	 *
	 * @return DataModel_Id
	 */
	public function unserialize( $id )
	{
		$id = explode( ':', $id );
		foreach( array_keys( $this->values ) as $k ) {
			if( !$id ) {
				break;
			}
			$val = array_shift( $id );

			$this->values[$k] = $val;
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString()
	{
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
	public function offsetSet( $offset, $value )
	{

		if( !array_key_exists( $offset, $this->values ) ) {
			throw new DataModel_Exception(
				'Undefined ID part \''.$offset.'\'', DataModel_Exception::CODE_UNDEFINED_ID_PART
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
	public function offsetGet( $offset )
	{

		if( !array_key_exists( $offset, $this->values ) ) {
			throw new DataModel_Exception(
				'Undefined ID part \''.$offset.'\'', DataModel_Exception::CODE_UNDEFINED_ID_PART
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
	public function offsetExists( $offset )
	{

		return array_key_exists( $offset, $this->values );
	}

	/**
	 * @see \ArrayAccess
	 *
	 * Do nothing
	 *
	 * @param mixed $offset
	 */
	public function offsetUnset( $offset )
	{
	}

	/**
	 * @see \Iterator
	 */
	public function current()
	{
		return current( $this->values );
	}

	/**
	 * @see \Iterator
	 * @return string
	 */
	public function key()
	{
		return key( $this->values );
	}

	/**
	 * @see \Iterator
	 */
	public function next()
	{
		return next( $this->values );
	}

	/**
	 * @see \Iterator
	 */
	public function rewind()
	{
		reset( $this->values );
	}

	/**
	 * @see \Iterator
	 * @return bool
	 */
	public function valid()
	{
		return key( $this->values )!==null;
	}
}