<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Backend
 */
namespace Jet;

class DataModel_RecordData implements \Iterator {

	/**
	 * @var DataModel_RecordData_Item[]
	 */
	protected $items = array();

	/**
	 * @var DataModel_Definition_Model_Abstract
	 */
	protected $data_model;

	/**
	 * @param DataModel_Definition_Model_Abstract $data_model_definition
	 */
	public function __construct( DataModel_Definition_Model_Abstract $data_model_definition ) {
		$this->data_model_definition = $data_model_definition;
	}

	/**
	 * @return DataModel_Definition_Model_Abstract
	 */
	public function getDataModelDefinition() {
		return $this->data_model_definition;
	}


	/**
	 * @param DataModel_Definition_Property_Abstract $property_definition
	 * @param mixed $value
	 */
	public function addItem(  DataModel_Definition_Property_Abstract $property_definition,  $value   ) {
		$this->items[] = new DataModel_RecordData_Item($property_definition, $value);
	}

	/**
	 * @see \Iterator
	 * @return DataModel_RecordData_Item
	 */
	public function current() {
		return current($this->items);
	}
	/**
	 * @see \Iterator
	 * @return string
	 */
	public function key() {
		return key($this->items);
	}
	/**
	 * @see \Iterator
	 */
	public function next() {
		return next($this->items);
	}
	/**
	 * @see \Iterator
	 */
	public function rewind() {
		reset($this->items);
	}
	/**
	 * @see \Iterator
	 * @return bool
	 */
	public function valid()	{
		return key($this->items)!==null;
	}

	/**
	 * @static
	 *
	 * @param DataModel $data_model
	 * @param array $properties_and_values - property_name => value
	 *
	 * @throws DataModel_Exception
	 * @return DataModel_RecordData
	 */
	public static function createRecordData( DataModel $data_model, array $properties_and_values ) {
		$definition = $data_model->getDataModelDefinition();
		$result = new self( $definition );
		$properties = $definition->getProperties();

		foreach($properties_and_values as $property_name=>$value) {
			if(!isset($properties[$property_name])) {
				throw new DataModel_Exception(
					"Unknown property '{$property_name}'",
					DataModel_Exception::CODE_UNKNOWN_PROPERTY
				);
			}

			$property = $properties[$property_name];

			$result->addItem($property, $value);
		}

		return $result;
	}

}