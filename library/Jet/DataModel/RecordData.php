<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class DataModel_RecordData implements BaseObject_Interface_IteratorCountable
{

	/**
	 * @var DataModel_RecordData_Item[]
	 */
	protected array $items = [];

	/**
	 * @var ?DataModel_Definition_Model
	 */
	protected ?DataModel_Definition_Model $data_model_definition = null;

	/**
	 *
	 * @param string  $data_model_class_name
	 * @param array   $properties_and_values ( array(property_name => value) )
	 *
	 * @throws DataModel_Exception
	 * @return DataModel_RecordData
	 */
	public static function createRecordData( string $data_model_class_name, array $properties_and_values ) : DataModel_RecordData
	{
		$definition = DataModel::getDataModelDefinition( $data_model_class_name );
		$result = new self( $definition );
		$properties = $definition->getProperties();

		foreach( $properties_and_values as $property_name => $value ) {
			if( !isset( $properties[$property_name] ) ) {
				throw new DataModel_Exception(
					'Unknown property \''.$property_name.'\'', DataModel_Exception::CODE_UNKNOWN_PROPERTY
				);
			}

			$property = $properties[$property_name];

			$result->addItem( $property, $value );
		}

		return $result;
	}

	/**
	 * @param DataModel_Definition_Model $data_model_definition
	 */
	public function __construct( DataModel_Definition_Model $data_model_definition )
	{
		$this->data_model_definition = $data_model_definition;
	}

	/**
	 * @param DataModel_Definition_Property $property_definition
	 * @param mixed                         $value
	 */
	public function addItem( DataModel_Definition_Property $property_definition, mixed $value ) : void
	{
		$this->items[] = new DataModel_RecordData_Item( $property_definition, $value );
	}

	/**
	 * @return DataModel_Definition_Model|null
	 */
	public function getDataModelDefinition() : DataModel_Definition_Model|null
	{
		return $this->data_model_definition;
	}

	/**
	 * @see \Iterator
	 * @return DataModel_RecordData_Item
	 */
	public function current() : DataModel_RecordData_Item
	{
		return current( $this->items );
	}

	/**
	 * @see \Iterator
	 * @return string
	 */
	public function key() : string
	{
		return key( $this->items );
	}

	/**
	 * @see \Iterator
	 */
	public function next() : DataModel_RecordData_Item|bool
	{
		return next( $this->items );
	}

	/**
	 * @see \Iterator
	 */
	public function rewind() : void
	{
		reset( $this->items );
	}

	/**
	 * @see \Iterator
	 * @return bool
	 */
	public function valid() : bool
	{
		return key( $this->items )!==null;
	}


	/**
	 * @see \Countable
	 *
	 * @return int
	 */
	public function count() : int
	{
		return count($this->items );
	}


	/**
	 * @return bool
	 */
	public function getIsEmpty() : bool
	{
		return !(bool)count( $this->items );
	}

}