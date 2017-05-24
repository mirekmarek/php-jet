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
class DataModel_Definition_Property_DynamicValue extends DataModel_Definition_Property
{
	/**
	 * @var string
	 */
	protected $_type = DataModel::TYPE_DYNAMIC_VALUE;

	/**
	 * @var string
	 */
	protected $getter_name = null;

	/**
	 * @var mixed
	 */
	protected $default_value = null;

	/**
	 * @param array $definition_data
	 *
	 * @throws DataModel_Exception
	 */
	public function setUp( $definition_data )
	{

		if( $definition_data ) {
			parent::setUp( $definition_data );

			if( !$this->getter_name ) {
				throw new DataModel_Exception(
					'Property '.$this->data_model_class_name.'::'.$this->_name.' is Dynamic Value, but getter_name is missing in definition data.',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
			}
		}

	}


	/**
	 * @param mixed               &$property
	 * @param DataModel_Interface $data_model_instance
	 */
	public function initPropertyDefaultValue( &$property, /** @noinspection PhpUnusedParameterInspection */
	                                          DataModel_Interface $data_model_instance )
	{
	}

	/**
	 * Converts property form jsonSerialize
	 *
	 * Example: Locale to string
	 *
	 * @param DataModel_Interface $data_model_instance
	 * @param mixed               &$property
	 *
	 * @return mixed
	 */
	public function getJsonSerializeValue( DataModel_Interface $data_model_instance, &$property )
	{
		return $data_model_instance->{$this->getGetterName()}();
	}

	/**
	 * @return mixed
	 */
	public function getGetterName()
	{
		return $this->getter_name;
	}

	/**
	 *
	 * @param DataModel_Interface $data_model_instance
	 * @param mixed               &$property
	 *
	 * @return mixed
	 */
	public function getXmlExportValue( DataModel_Interface $data_model_instance, &$property )
	{
		return $data_model_instance->{$this->getGetterName()}();
	}

	/**
	 * @return bool
	 */
	public function getCanBeTableField()
	{
		return false;
	}

	/**
	 * @return bool
	 */
	public function getCanBeInSelectPartOfQuery()
	{
		return false;
	}

	/**
	 * @return bool
	 */
	public function getCanBeInInsertRecord()
	{
		return false;
	}

	/**
	 * @return bool
	 */
	public function getCanBeInUpdateRecord()
	{
		return false;
	}

	/**
	 * @return bool
	 */
	public function getCanBeFormField()
	{
		return false;
	}

	/**
	 *
	 * @param DataModel_Interface $data_model_instance
	 * @param mixed               $property_value
	 * @param array               $related_data
	 *
	 * @return Form_Field|Form_Field[]
	 */
	public function createFormField( DataModel_Interface $data_model_instance, $property_value, array $related_data )
	{
		return null;
	}

	/**
	 *
	 * @return void
	 *
	 * @throws DataModel_Exception
	 */
	public function getDefaultValue()
	{
		throw new DataModel_Exception(
			'You can not use getDefaultValue for the property that is DynamicValue (property: '.$this->_name.')'
		);
	}

	/**
	 * @param mixed &$property
	 * @param mixed $data
	 *
	 */
	public function loadPropertyValue( &$property, array $data )
	{
	}

	/**
	 * @param mixed $value
	 *
	 * @throws DataModel_Exception
	 */
	public function checkValueType( &$value )
	{
		throw new DataModel_Exception(
			'You can not use checkValueType for the property that is DynamicValue (property: '.$this->_name.')'
		);
	}
}