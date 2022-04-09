<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
abstract class DataModel_IDController extends BaseObject
{

	/**
	 * @var ?DataModel
	 */
	protected ?DataModel $_data_model_instance = null;

	/**
	 *
	 * @var string
	 */
	protected string $data_model_class_name = '';

	/**
	 * array key: ID property name
	 * array value: ID value
	 *
	 * @var array
	 */
	protected array $values = [];


	/**
	 * @param DataModel_Definition_Model $data_model_definition
	 * @param array $options
	 */
	public function __construct( DataModel_Definition_Model $data_model_definition, array $options )
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
	 * @return string
	 */
	public function getDataModelClassName(): string
	{
		return $this->data_model_class_name;
	}

	/**
	 * @return DataModel_Definition_Model_Main|DataModel_Definition_Model_Related
	 */
	public function getDataModelDefinition(): DataModel_Definition_Model_Main|DataModel_Definition_Model_Related
	{
		return DataModel_Definition::get( $this->data_model_class_name );
	}

	/**
	 *
	 *
	 * @return DataModel_Query
	 */
	public function getQuery(): DataModel_Query
	{
		$data_model_definition = $this->getDataModelDefinition();

		$query = new DataModel_Query( $data_model_definition );

		$query->setWhere( [] );
		$where = $query->getWhere();

		$properties = $data_model_definition->getProperties();

		foreach( $this->values as $property_name => $value ) {
			if( $value === null ) {
				continue;
			}


			$where->addAND();
			$where->addExpression( $properties[$property_name], DataModel_Query::O_EQUAL, $value );
		}

		return $query;
	}


	/**
	 * @param array $options
	 */
	public function setOptions( array $options ): void
	{
		foreach( $options as $key => $val ) {
			$this->{$key} = $val;
		}
	}

	/**
	 * @param DataModel $data_model
	 */
	public function assocDataModelInstance( DataModel $data_model ): void
	{
		$this->_data_model_instance = $data_model;
	}

	/**
	 * @param string $name
	 * @param mixed  &$property
	 */
	public function assocDataModelInstanceProperty( string $name, mixed &$property ): void
	{
		$this->values[$name] = &$property;
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		return implode( ':', $this->values );
	}

	/**
	 * @param string $property_name
	 *
	 * @return mixed
	 *
	 * @throws DataModel_Exception
	 */
	public function getValue( string $property_name ): mixed
	{
		if( !array_key_exists( $property_name, $this->values ) ) {
			throw new DataModel_Exception(
				'Undefined ID property \'' . $property_name . '\''
			);
		}

		return $this->values[$property_name];

	}


	/**
	 * @param string $property_name
	 * @param mixed $value
	 *
	 * @throws DataModel_Exception
	 */
	public function setValue( string $property_name, mixed $value ): void
	{
		if( !array_key_exists( $property_name, $this->values ) ) {
			throw new DataModel_Exception(
				'Undefined ID property \'' . $property_name . '\''
			);
		}

		$this->values[$property_name] = $value;

	}

	/**
	 * @return array
	 */
	public function getPropertyNames(): array
	{
		return array_keys( $this->values );
	}


	/**
	 *
	 */
	public function generate(): void
	{
		$this->beforeSave();
	}

	/**
	 *
	 */
	abstract public function beforeSave(): void;

	/**
	 * @param mixed $backend_save_result
	 *
	 */
	abstract public function afterSave( mixed $backend_save_result ): void;

}